<?php
require_once 'config.php';

$_SESSION['carrito'] = array();
	
header('Location: carrito.php');
exit();
?>