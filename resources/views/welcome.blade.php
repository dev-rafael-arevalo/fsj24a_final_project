<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Backend</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            margin: 20px auto;
            max-width: 900px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido al backend del proyecto final de FSJ24a de KODIGO</h1>
        <p>Gestión de usuarios y productos</p>
    </header>
    <div class="container">
        <h2>Información General</h2>
        <p>Este backend proporciona servicios API para la gestión de usuarios, productos y reseñas.</p>

        <h2>Documentación</h2>
        <ul>
            <li><a href="/docs/">Documentación de Swagger</a></li>
        </ul>

        <h2>Rutas Disponibles</h2>
        <table>
            <thead>
                <tr>
                    <th>Método</th>
                    <th>Ruta</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>POST</td><td>/login</td><td>Iniciar sesión</td></tr>
                <tr><td>POST</td><td>/register</td><td>Registrar un nuevo usuario</td></tr>
                <tr><td>GET</td><td>/v1/users</td><td>Obtener todos los usuarios</td></tr>
                <tr><td>GET</td><td>/v1/users/{id}</td><td>Obtener un usuario específico</td></tr>
                <tr><td>PUT</td><td>/v1/users/{id}</td><td>Actualizar un usuario</td></tr>
                <tr><td>DELETE</td><td>/v1/users/{id}</td><td>Eliminar un usuario</td></tr>
                <tr><td>GET</td><td>/v1/users-stats</td><td>Estadísticas de usuarios</td></tr>
                <tr><td>GET</td><td>/v1/products</td><td>Listar productos</td></tr>
                <tr><td>POST</td><td>/v1/products</td><td>Crear producto</td></tr>
                <tr><td>GET</td><td>/v1/products/{id}</td><td>Ver un producto</td></tr>
                <tr><td>PUT</td><td>/v1/products/{id}</td><td>Actualizar producto</td></tr>
                <tr><td>DELETE</td><td>/v1/products/{id}</td><td>Eliminar producto</td></tr>
            </tbody>
        </table>

        <h2>Soporte</h2>
        <p>Si tienes algún problema o duda, contacta al soporte técnico en:
            <a href="mailto:rafael.edgardo.arevalo@gmail.com">rafael.edgardo.arevalo@gmail.com</a>
        </p>
    </div>
</body>
</html>
