<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cantidad'])) {
    foreach ($_POST['cantidad'] as $indice => $cantidad) {
        if (isset($_SESSION['carrito'][$indice])) {
            $_SESSION['carrito'][$indice]['cantidad'] = max(1, (int)$cantidad);
        }
    }
}

// Redirigir de vuelta al carrito
header('Location: carrito.php');
exit();
?>