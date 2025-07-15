<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'])) {
    // Validar ID del producto
    $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
    if ($id_producto === false || $id_producto <= 0) {
        header('Location: index.php');
        exit();
    }

    // Obtener información del producto (con manejo de errores)
    try {
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
        $stmt->execute([$id_producto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en la consulta SQL: " . $e->getMessage());
        header('Location: index.php');
        exit();
    }

    if ($producto) {
        // Verificar si el producto ya está en el carrito
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id_producto'] == $id_producto) {
                $item['cantidad'] += 1;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            // Agregar nuevo producto al carrito (con sanitización)
            $_SESSION['carrito'][] = array(
                'id_producto' => $producto['id_producto'],
                'nombre' => htmlspecialchars($producto['nombre']),
                'precio' => floatval($producto['precio']),
                'cantidad' => 1
            );
        }
    }
}

header('Location: index.php');
exit();
?>