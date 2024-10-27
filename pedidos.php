<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de pago</title>
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
        .error {
            color: red;
            font-size: 1rem;
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
    <h1>Comprobante de pago</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conectar a la base de datos
        $conexion = mysqli_connect("localhost", "root", "", "pedido");

        if (!$conexion) {
            die("Problemas con la conexión: " . mysqli_connect_error());
        }

        // Preparar los datos del formulario
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
        $pago = mysqli_real_escape_string($conexion, $_POST['pago']);

        // Generar un número aleatorio entre 1 y 100
        $numeroAleatorio = rand(1, 100);
        // Generar una letra aleatoria (A-Z)
        $letraAleatoria = chr(mt_rand(65, 90)); // 65 es 'A' y 90 es 'Z'
        // Crear el comprobante
        $comprobante = $numeroAleatorio . $letraAleatoria;

        // Insertar los datos en la base de datos
        $query = "INSERT INTO clientes (nombre, direccion, pago, comprobante) VALUES ('$nombre', '$direccion', '$pago', '$comprobante')";

        if (mysqli_query($conexion, $query)) {
            echo "<p class='info'><strong>Nombre:</strong> $nombre</p>";
            echo "<p class='info'><strong>Dirección:</strong> $direccion</p>";
            echo "<p class='info'><strong>Método de Pago:</strong> $pago</p>";
            echo "<p class='info'><strong>Numero:</strong> $comprobante</p>"; // Mostrar el comprobante
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
