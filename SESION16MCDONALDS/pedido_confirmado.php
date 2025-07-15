<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedido Confirmado - McDonald's</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .confirmation-icon {
            font-size: 4rem;
            color: #198754;
            animation: bounce 1s;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }
        .order-number {
            font-size: 2rem;
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="confirmation-icon mb-4">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h1 class="mb-4">¡Pedido Confirmado!</h1>
                        <p class="lead">Gracias por tu compra en McDonald's.</p>
                        
                        <?php if(isset($_GET['id'])): ?>
                            <p>Tu número de pedido es:</p>
                            <div class="order-number mb-4">#<?php echo htmlspecialchars($_GET['id']); ?></div>
                            <p class="text-muted">Hemos enviado los detalles a nuestro equipo de cocina <i class="bi bi-emoji-smile"></i></p>
                        <?php endif; ?>
                        
                        <a href="index.php" class="btn btn-warning mt-4">
                            <i class="bi bi-arrow-left"></i> Volver al Menú
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>