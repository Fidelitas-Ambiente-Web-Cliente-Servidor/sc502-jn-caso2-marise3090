<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado Talleres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/taller.js"></script>
    <script src="public/js/solicitud.js"></script>
</head>
<body class="container mt-5">

    <nav class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="index.php?page=talleres" class="btn btn-outline-primary btn-sm">Talleres</a>
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin" class="btn btn-outline-dark btn-sm">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div>
            <span><?= htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['user'] ?? 'Usuario') ?></span>
            <button id="btnLogout" class="btn btn-danger btn-sm">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h3>Talleres disponibles</h3>

        <div id="mensaje" class="mb-3"></div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cupo máximo</th>
                    <th>Cupo disponible</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="tabla-talleres-body">
                <tr>
                    <td colspan="5">Cargando talleres...</td>
                </tr>
            </tbody>
        </table>
    </main>

</body>
</html>