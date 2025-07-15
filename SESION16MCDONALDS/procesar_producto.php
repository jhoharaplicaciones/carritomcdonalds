<?php 
session_start();
include 'config.php';

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['form_errors'] = ["Método no permitido"];
    header('Location: admin_productos.php');
    exit();
}

// Obtener datos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
$categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
$imagen_actual = isset($_POST['imagen_actual']) ? $_POST['imagen_actual'] : '';
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;

// Validación básica
$errors = [];
if (empty($nombre)) $errors[] = "El nombre del producto es requerido";
if (empty($descripcion)) $errors[] = "La descripción es requerida";
if ($precio <= 0) $errors[] = "El precio debe ser mayor que cero";
if (empty($categoria)) $errors[] = "La categoría es requerida";

// Procesar imagen si se subió
$imagen = $imagen_actual; // Mantener la imagen actual por defecto

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
    // Configuración de tamaño máximo (5MB en bytes)
    $maxFileSize = 5 * 1024 * 1024;
    
    // Verificar tamaño del archivo
    if ($_FILES["imagen"]["size"] > $maxFileSize) {
        $errors[] = "La imagen excede el tamaño máximo permitido de 5MB";
    }
    
    // Verificar tipo de archivo
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $nombreArchivo = $_FILES["imagen"]["name"];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    
    if (!in_array($extension, $allowedExtensions)) {
        $errors[] = "Solo se permiten archivos JPG, JPEG, PNG o GIF para la imagen";
    }
    
    // Si no hay errores, mover el archivo
    if (empty($errors)) {
        // Eliminar imagen anterior si existe (solo en edición)
        if (!empty($imagen_actual) && file_exists("imagenes/$imagen_actual") && $accion === 'editar') {
            unlink("imagenes/$imagen_actual");
        }
        
        $imagen = "producto_".date("Y_m_d_His").".$extension";
        $temporal = $_FILES["imagen"]["tmp_name"];
        $destination = "imagenes/$imagen";
        
        if (!move_uploaded_file($temporal, $destination)) {
            $errors[] = "Error al mover el archivo subido";
        }
    }
}

// Validación especial para agregar producto
if ($accion === 'agregar' && (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK)) {
    $errors[] = "La imagen es requerida para agregar un nuevo producto";
}

// Si hay errores, mostrarlos
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: admin_productos.php');
    exit();
}

try {
    // Determinar si es una edición o inserción
    if ($accion === 'editar' && $id_producto > 0) {
        // Preparar la consulta SQL de actualización
        $sql = "UPDATE productos SET 
                nombre = :nombre, 
                descripcion = :descripcion, 
                precio = :precio, 
                categoria = :categoria, 
                imagen = :imagen 
                WHERE id_producto = :id";
        
        $stmt = $conn->prepare($sql);
        $params = [
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':categoria' => $categoria,
            ':imagen' => $imagen,
            ':id' => $id_producto
        ];
        $success_message = "Producto actualizado correctamente";
    } elseif ($accion === 'agregar') {
        // Preparar la consulta SQL de inserción
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria, imagen) 
                VALUES (:nombre, :descripcion, :precio, :categoria, :imagen)";
        
        $stmt = $conn->prepare($sql);
        $params = [
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':categoria' => $categoria,
            ':imagen' => $imagen
        ];
        $success_message = "Producto agregado correctamente";
    } else {
        $_SESSION['form_errors'] = ["Acción no válida"];
        header('Location: admin_productos.php');
        exit();
    }

    if ($stmt->execute($params)) {
        $_SESSION['success_message'] = $success_message;
    } else {
        $_SESSION['form_errors'] = ["Error al guardar los datos"];
    }
} catch(PDOException $e) {
    $_SESSION['form_errors'] = ["Error en la base de datos: " . $e->getMessage()];
    $_SESSION['form_data'] = $_POST;
}

header('Location: admin_productos.php');
exit();
?>