<?php
session_start();
include 'config.php';

// Verificar que se haya proporcionado un ID
if (!isset($_POST['id_producto']) || !is_numeric($_POST['id_producto'])) {
    $_SESSION['form_errors'] = ["ID de producto no válido"];
    header('Location: admin_productos.php');
    exit();
}

$id = (int)$_POST['id_producto'];

try {
    // Obtener información del producto para eliminar su imagen
    $stmt = $conn->prepare("SELECT imagen FROM productos WHERE id_producto = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eliminar la imagen si existe
    if (!empty($producto['imagen']) && file_exists("imagenes/".$producto['imagen'])) {
        unlink("imagenes/".$producto['imagen']);
    }

    // Eliminar el producto de la base de datos
    $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
    
    if ($stmt->execute([$id])) {
        $_SESSION['success_message'] = "Producto eliminado correctamente";
    } else {
        $_SESSION['form_errors'] = ["Error al eliminar el producto"];
    }
} catch(PDOException $e) {
    $_SESSION['form_errors'] = ["Error al eliminar el producto: " . $e->getMessage()];
}

header('Location: admin_productos.php');
exit();
?>