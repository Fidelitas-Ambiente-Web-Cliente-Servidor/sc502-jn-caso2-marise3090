<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }

    public function getSolicitudesJson()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode([]);
            return;
        }

        $solicitudes = $this->solicitudModel->getPendientes();
        echo json_encode($solicitudes);
    }

    public function aprobar()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }

        $solicitudId = isset($_POST['id_solicitud']) ? (int) $_POST['id_solicitud'] : 0;

        if ($solicitudId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
            return;
        }

        $solicitud = $this->solicitudModel->getById($solicitudId);

        if (!$solicitud) {
            echo json_encode(['success' => false, 'error' => 'La solicitud no existe']);
            return;
        }

        if ($solicitud['estado'] !== 'pendiente') {
            echo json_encode(['success' => false, 'error' => 'La solicitud ya fue procesada']);
            return;
        }

        $taller = $this->tallerModel->getById($solicitud['taller_id']);

        if (!$taller) {
            echo json_encode(['success' => false, 'error' => 'El taller no existe']);
            return;
        }

        if ((int)$taller['cupo_disponible'] <= 0) {
            echo json_encode(['success' => false, 'error' => 'Ya no hay cupos disponibles']);
            return;
        }

        if (!$this->tallerModel->descontarCupo($solicitud['taller_id'])) {
            echo json_encode(['success' => false, 'error' => 'No se pudo descontar el cupo']);
            return;
        }

        if (!$this->solicitudModel->aprobar($solicitudId)) {
            echo json_encode(['success' => false, 'error' => 'No se pudo aprobar la solicitud']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Solicitud aprobada correctamente']);
    }

    public function rechazar()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }

        $solicitudId = isset($_POST['id_solicitud']) ? (int) $_POST['id_solicitud'] : 0;

        if ($solicitudId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
            return;
        }

        if ($this->solicitudModel->rechazar($solicitudId)) {
            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada correctamente']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }
}