<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado Del Pedido</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(white, red, black);
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
        .success {
            color: #4caf50;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        .error {
            color: #f44336;
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
        <h1>Resultado Del Pedido</h1>
        <?php
        $conexion = mysqli_connect("localhost", "root", "", "pedidos");

        if (!$conexion) {
            die("Problemas con la conexión: " . mysqli_connect_error());
        }

        $nombre = mysqli_real_escape_string($conexion, $_POST['firstName']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['lastName']);
        $direccion = isset($_POST['address']) ? mysqli_real_escape_string($conexion, $_POST['address']) : 'No aplicable';
        $opcionEntrega = isset($_POST['deliveryOption']) ? mysqli_real_escape_string($conexion, $_POST['deliveryOption']) : 'No especificado';
        $metodoPago = mysqli_real_escape_string($conexion, $_POST['paymentMethod']);

        do {
            $numeroPedido = rand(1, 99);
            $letraAleatoria = chr(rand(65, 90)); 
            $idPedido = $numeroPedido . $letraAleatoria;

            $verificarQuery = $conexion->prepare("SELECT * FROM pedido WHERE id_pedido = ?");
            $verificarQuery->bind_param("s", $idPedido);
            $verificarQuery->execute();
            $resultado = $verificarQuery->get_result();
        } while ($resultado->num_rows > 0);

        $query = "INSERT INTO pedido (nombre, apellido, direccion, opcion_entrega, metodo_pago, id_pedido) VALUES ('$nombre', '$apellido', '$direccion', '$opcionEntrega', '$metodoPago', '$idPedido')";

        if (mysqli_query($conexion, $query)) {
            echo "<p class='success'>El pedido fue realizado con éxito.</p>";
            echo "<p>Tu número de pedido es: <strong>$idPedido</strong></p>";
            echo "<p>Nombre: $nombre $apellido</p>";
            echo "<p>Dirección: $direccion</p>";
            echo "<p>Opción de entrega: $opcionEntrega</p>";
            echo "<p>Método de pago: $metodoPago</p>";
            echo "<a href='menu.php?idPedido=$idPedido'>Ver Menú de Comidas Rápidas</a>"; // Enlace al menú
        } else {
            echo "<p class='error'>Error: " . mysqli_error($conexion) . "</p>";
        }

        mysqli_close($conexion);
        ?>
        <a href="index.html">Volver al formulario</a>
    </div>
</body>
</html>
