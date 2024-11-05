<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "pedido");

if (!$conexion) {
    die("Problemas con la conexión: " . mysqli_connect_error());
}

// Manejo de la actualización del pedido
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Actualizar el estado del pedido
    $updateQuery = "UPDATE clientes SET estado='Listo' WHERE id=$id";
    mysqli_query($conexion, $updateQuery);
}

// Obtener solo los pedidos listos para retirar por local (nombre y comprobante)
$query = "SELECT nombre, comprobante FROM clientes WHERE estado='Listo' AND local LIKE '%R%'";
$result = mysqli_query($conexion, $query);

// Verifica si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Listos para Retirar</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center; /* Centrar el contenido */
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e2e6ea;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px auto;
            text-align: center;
        }
        a:hover {
            background-color: #0056b3;
        }
        .nombre {
            font-weight: bold; /* Negrita */
            text-transform: uppercase; /* Mayúsculas */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Pedidos Listos para Retirar por Local</h1>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Comprobante</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td class="nombre"><?php echo htmlspecialchars($row['nombre']); ?></td>
            <td class="nombre"><?php echo htmlspecialchars($row['comprobante']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="admin.php">Volver a Administración</a>
</div>
</body>
</html>

<?php mysqli_close($conexion); ?>
