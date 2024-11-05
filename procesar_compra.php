<?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "pedido");

if (!$conexion) {
    die("Problemas con la conexión: " . mysqli_connect_error());
}

// Leer el cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"));

// Verificar si los datos del carrito fueron enviados
if (isset($data->carrito) && isset($data->carritoId)) {
    $carritoId = mysqli_real_escape_string($conexion, $data->carritoId);

    // Iniciar transacción para insertar todos los productos del carrito
    mysqli_begin_transaction($conexion);

    try {
        // Insertar cada producto del carrito en la base de datos
        foreach ($data->carrito as $producto) {
            $nombre = mysqli_real_escape_string($conexion, $producto->nombre);
            $precio = mysqli_real_escape_string($conexion, $producto->precio);
            $imagen = mysqli_real_escape_string($conexion, $producto->imagen);

            // Consulta SQL para insertar el producto en la tabla de pedidos
            $query = "INSERT INTO pedidoss (carrito_id, nombre, precio, imagen) 
                      VALUES ('$carritoId', '$nombre', '$precio', '$imagen')";

            if (!mysqli_query($conexion, $query)) {
                throw new Exception("Error al insertar el producto en la base de datos");
            }
        }

        // Si todo va bien, commit de la transacción
        mysqli_commit($conexion);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        mysqli_roll_back($conexion);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}

// Cerrar la conexión
mysqli_close($conexion);
?>
