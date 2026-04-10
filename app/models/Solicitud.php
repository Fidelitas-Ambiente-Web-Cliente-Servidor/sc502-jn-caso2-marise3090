<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function existeSolicitudActiva($usuarioId, $tallerId)
    {
        $query = "SELECT id 
                  FROM solicitudes 
                  WHERE usuario_id = ? 
                  AND taller_id = ? 
                  AND estado IN ('pendiente', 'aprobada')
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $usuarioId, $tallerId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function crear($tallerId, $usuarioId)
    {
        $query = "INSERT INTO solicitudes (taller_id, usuario_id, estado) 
                  VALUES (?, ?, 'pendiente')";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function getPendientes()
    {
        $query = "SELECT 
                    s.id,
                    t.nombre AS taller,
                    u.username AS usuario,
                    s.fecha_solicitud,
                    s.estado
                  FROM solicitudes s
                  INNER JOIN talleres t ON s.taller_id = t.id
                  INNER JOIN usuarios u ON s.usuario_id = u.id
                  WHERE s.estado = 'pendiente'
                  ORDER BY s.fecha_solicitud ASC";

        $result = $this->conn->query($query);
        $solicitudes = [];

        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }

        return $solicitudes;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM solicitudes WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function aprobar($solicitudId)
    {
        $query = "UPDATE solicitudes 
                  SET estado = 'aprobada' 
                  WHERE id = ? AND estado = 'pendiente'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function rechazar($solicitudId)
    {
        $query = "UPDATE solicitudes 
                  SET estado = 'rechazada' 
                  WHERE id = ? AND estado = 'pendiente'";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }
}