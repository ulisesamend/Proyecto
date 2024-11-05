<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirige a la página de inicio de sesión si no está autenticado
    exit;
}

// Conectar a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "pedido");

if (!$conexion) {
    die("Problemas con la conexión: " . mysqli_connect_error());
}

// Manejo de la búsqueda
$search = '';
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conexion, $_POST['search']);
}

// Obtener todos los pedidos, con opción de búsqueda
$query = "SELECT * FROM clientes";
if ($search) {
    $query .= " WHERE comprobante LIKE '%$search%'"; // Filtrar por comprobante
}
$result = mysqli_query($conexion, $query);

// Manejo del botón "Pedido Listo"
if (isset($_POST['pedido_id'])) {
    $id = intval($_POST['pedido_id']);
    // Actualizar el estado del pedido
    $updateQuery = "UPDATE clientes SET estado='Listo' WHERE id=$id";
    mysqli_query($conexion, $updateQuery);
    
    // Redirigir a la misma página para evitar reenvíos al actualizar
    header("Location: admin.php"); // Asegúrate de que 'admin.php' sea el nombre correcto
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Pedidos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
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
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            text-align: center;
            margin: 20px auto;
            width: 150px;
        }
        a:hover {
            background-color: #0056b3;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-container input {
            padding: 10px;
            width: 70%;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px 15px;
            margin-left: 5px;
            background-color: #007BFF;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Pedidos Registrados</h1>
    <div class="search-container">
        <form method="POST">
            <input type="text" name="search" placeholder="Buscar por comprobante..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍 Buscar</button>
        </form>
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Local</th>
            <th>Método de Pago</th>
            <th>Comprobante</th>
            <th>Acción</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo $row['direccion']; ?></td>
            <td><?php echo $row['local']; ?></td>
            <td><?php echo $row['pago']; ?></td>
            <td><?php echo $row['comprobante']; ?></td>
            <td>
                <!-- Corrección: Mostrar el botón según el estado -->
                <?php if ($row['estado'] == 'Listo'): ?>
                    <button type="button" class="disabled" disabled>Pedido Listo</button> <!-- Botón deshabilitado -->
                <?php else: ?>
                    <form method="POST" action="admin.php" style="display:inline;">
                        <input type="hidden" name="pedido_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Marcar como Listo</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Cerrar sesión</a>
</div>
</body>
</html>

<?php mysqli_close($conexion); ?>
