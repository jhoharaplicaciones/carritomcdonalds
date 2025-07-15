<?php include("config.php") ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McDonald's - Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .carrito-link {
            transition: all 0.3s ease;
        }
        .carrito-link:hover {
            transform: translateY(-2px);
        }
        .quantity-input {
            width: 60px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-danger text-white p-3 rounded">
            <h1 class="mb-0">Carrito de Compras</h1>
            <a href="index.php" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Volver al Menú
            </a>
        </div>

        <?php if (empty($_SESSION['carrito'])): ?>
            <div class="alert alert-info">
                <i class="bi bi-cart-x"></i> Tu carrito está vacío.
            </div>
        <?php else: ?>
            <form id="cart-form" method="post" action="actualizar_carrito.php">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Unitario</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    foreach ($_SESSION['carrito'] as $indice => $producto):
                                        $subtotal = $producto['precio'] * $producto['cantidad'];
                                        $total += $subtotal;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($producto['nombre']); ?></td>
                                        <td><?= number_format($producto['precio'], 2); ?> €</td>
                                        <td>
                                            <input type="number" 
                                                   class="form-control quantity-input" 
                                                   name="cantidad[<?= $indice ?>]" 
                                                   value="<?= $producto['cantidad']; ?>" 
                                                   min="1"
                                                   onchange="this.form.submit()">
                                        </td>
                                        <td><?= number_format($subtotal, 2); ?> €</td>
                                        <td>
                                            <a href="eliminar_carrito.php?indice=<?= $indice; ?>" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td colspan="2" class="fw-bold"><?= number_format($total, 2); ?> €</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="vaciar_carrito.php" class="btn btn-danger">
                        <i class="bi bi-cart-x"></i> Vaciar Carrito
                    </a>
                    <a href="confirmar_pedido.php" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirmar Pedido
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para enviar el formulario automáticamente al cambiar la cantidad
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Agregar una pequeña animación de feedback
                    this.classList.add('bg-warning', 'bg-opacity-25');
                    setTimeout(() => {
                        this.classList.remove('bg-warning', 'bg-opacity-25');
                    }, 300);
                    
                    // Enviar el formulario
                    document.getElementById('cart-form').submit();
                });
            });
        });
    </script>
</body>
</html>