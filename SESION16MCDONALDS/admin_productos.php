<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Productos - McDonald's</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .img-thumbnail {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-danger text-white p-3 rounded">
            <h1 class="mb-0">Administración de Productos</h1>
            <div>
                <a href="index.php" class="btn btn-light me-2">
                    <i class="bi bi-arrow-left"></i> Volver al Menú
                </a>
                <a href="#modalAgregar" class="btn btn-success" data-bs-toggle="modal">
                    <i class="bi bi-plus-circle"></i> Agregar Producto
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['tipo_mensaje'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensaje'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Lista de Productos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Categoría</th>
                                <th>Imagen</th>
                                <th class="action-buttons">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $conn->query("SELECT * FROM productos ORDER BY id_producto DESC");
                                while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <tr>
                                <td><?= $producto['id_producto'] ?></td>
                                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                <td><?= htmlspecialchars(substr($producto['descripcion'], 0, 50)) ?>...</td>
                                <td>$<?= number_format($producto['precio'], 2) ?></td>
                                <td><?= htmlspecialchars($producto['categoria']) ?></td>
                                <td>
                                    <?php if (!empty($producto['imagen'])): ?>
                                        <img src="imagenes/<?= htmlspecialchars($producto['imagen']) ?>" class="img-thumbnail" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="#modalEditar<?= $producto['id_producto'] ?>" class="btn btn-sm btn-primary" data-bs-toggle="modal">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="#modalEliminar<?= $producto['id_producto'] ?>" class="btn btn-sm btn-danger" data-bs-toggle="modal">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="modalEditar<?= $producto['id_producto'] ?>" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="procesar_producto.php" method="post" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditarLabel">Editar Producto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="accion" value="editar">
                                                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label for="nombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="descripcion" class="form-label">Descripción</label>
                                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="precio" class="form-label">Precio</label>
                                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" value="<?= $producto['precio'] ?>" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="categoria" class="form-label">Categoría</label>
                                                    <select class="form-select" id="categoria" name="categoria" required>
                                                        <option value="hamburguesas" <?= $producto['categoria'] == 'hamburguesas' ? 'selected' : '' ?>>Hamburguesas</option>
                                                        <option value="pollo" <?= $producto['categoria'] == 'pollo' ? 'selected' : '' ?>>Pollo</option>
                                                        <option value="acompañamientos" <?= $producto['categoria'] == 'acompañamientos' ? 'selected' : '' ?>>Acompañamientos</option>
                                                        <option value="bebidas" <?= $producto['categoria'] == 'bebidas' ? 'selected' : '' ?>>Bebidas</option>
                                                        <option value="postres" <?= $producto['categoria'] == 'postres' ? 'selected' : '' ?>>Postres</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="imagen" class="form-label">Imagen</label>
                                                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                                    <small class="text-muted">Dejar en blanco para mantener la imagen actual</small>
                                                    <?php if (!empty($producto['imagen'])): ?>
                                                        <div class="mt-2">
                                                            <img src="imagenes/<?= htmlspecialchars($producto['imagen']) ?>" class="img-thumbnail" width="100">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="modalEliminar<?= $producto['id_producto'] ?>" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="eliminar_producto.php" method="post">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Producto</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                                                <p>¿Estás seguro de que deseas eliminar el producto <strong><?= htmlspecialchars($producto['nombre']) ?></strong>?</p>
                                                <p class="text-danger">Esta acción no se puede deshacer.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-danger" >Eliminar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } catch(PDOException $e) {
                                echo "<div class='alert alert-danger'>Error al cargar los productos: " . $e->getMessage() . "</div>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="procesar_producto.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarLabel">Agregar Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="agregar">
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="hamburguesas">Hamburguesas</option>
                                <option value="pollo">Pollo</option>
                                <option value="acompañamientos">Acompañamientos</option>
                                <option value="bebidas">Bebidas</option>
                                <option value="postres">Postres</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                            <small class="text-muted">Formatos aceptados: JPG, PNG, GIF</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Agregar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>