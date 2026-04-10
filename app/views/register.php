<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="public/js/jquery-4.0.0.min.js"></script>
    <script src="public/js/register.js"></script>
</head>
<body class="container mt-5">

    <h2>Registro</h2>

    <form id="formRegister">
        <input class="form-control mb-2" name="username" id="username" placeholder="Usuario">
        <input type="password" class="form-control mb-2" name="password" id="password" placeholder="Contraseña">

        <button type="submit" class="btn btn-primary">Registrarse</button>
        <a href="index.php?page=login" class="btn btn-secondary">Ir a login</a>
    </form>

</body>
</html>