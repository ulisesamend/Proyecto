<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff5f6d, #ffc371);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1s ease-out;
        }
        h1 {
            margin-bottom: 1rem;
            color: #333;
        }
        .info {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        a {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #333;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #555;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }    
    </style>
</head>
<body>
<div class="container">
    <h1>Comprobante de Pago</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conectar a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "pedidos");

        if (!$conexion) {
            die("Problemas con la conexión: " . mysqli_connect_error());
        }

        // Preparar los datos del formulario
        $nombre = mysqli_real_escape_string($conexion, $_POST['firstName']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['lastName']);
        $direccion = isset($_POST['address']) ? mysqli_real_escape_string($conexion, $_POST['address']) : 'No aplicable';
        $opcionEntrega = isset($_POST['deliveryOption']) ? mysqli_real_escape_string($conexion, $_POST['deliveryOption']) : 'No especificado';
        $metodoPago = mysqli_real_escape_string($conexion, $_POST['paymentMethod']);

        // Insertar los datos en la base de datos
        $query = "INSERT INTO pedido (nombre, apellido, direccion, opcion_entrega, metodo_pago, ) VALUES ('$nombre', '$apellido', '$direccion', '$opcionEntrega', '$metodoPago')";

        if (mysqli_query($conexion, $query)) {
            echo "<p class='info'>El pedido fue realizado con éxito.</p>";
            echo "<p class='info'><strong>Nombre:</strong> $nombre</p>";
            echo "<p class='info'><strong>Apellido:</strong> $apellido</p>";
            echo "<p class='info'><strong>Dirección:</strong> $direccion</p>";
            echo "<p class='info'><strong>Opción de Entrega:</strong> $opcionEntrega</p>";
            echo "<p class='info'><strong>Método de Pago:</strong> $metodoPago</p>";
        } else {
            echo "<p class='error'>Error: " . mysqli_error($conexion) . "</p>";
        }

        // Cerrar la conexión
        mysqli_close($conexion);
    } else {
        echo "<p class='error'>No se recibieron datos del formulario.</p>";
    }
    ?>
    <a href="index.html">Volver al formulario</a>
</div>
</body>
</html>
