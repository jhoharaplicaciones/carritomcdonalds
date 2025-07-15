<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'carrito_mcdonalds';
$username = 'root';
$password = '';

try {
    // Conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Iniciar sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Inicializar carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
    
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>