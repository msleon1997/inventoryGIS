<?php


require_once '../config.php';
require_once('../classes/Productos.php');

// URL base de la API

$productos = new Productos();
$qry = $productos->obtenerProductos();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Productos</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
            <a href="<?php echo base_url ?>admin/?page=productos/manage_productos" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Agregar producto</a>
        </div>
    </div>
    <div class="card-body">
    <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
            </div>
        <div class="container-fluid">
            <div class="container-fluid">
                <?php if ($qry && is_array($qry) && count($qry) > 0) : ?>
                    <table class="table table-bordered table-stripped" id="productostable">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="20%">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre Producto</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Fecha</th>
                                <th>Cantidad</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        if (isset($qry['productos']) && is_array($qry['productos'])) {
                            foreach ($qry['productos'] as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo $row['PRO_Nombre'] ?></td>
                                <td><?php echo $row['PRO_Descripcion'] ?></td>
                                <td class="text-right"><?php echo number_format($row['PRO_Precio'], 2) ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($row['PRO_FechaCreacion'])) ?></td>
                                <td><?php echo $row['PRO_Cantidad'] ?></td>
                                <td><?php echo $row['proveedor_nombre'] ?></td>
                                <td class="text-center">
                                <?php
                                    $estado = "N/A";
                                    switch ($row['PRO_Estado']) {
                                        case 1:
                                            $estado = "Pendiente";
                                            break;
                                        case 2:
                                            $estado = "Recibido";
                                            break;
                                        case 3:
                                            $estado = "Cancelado";
                                            break;
                                        
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $row['PRO_Estado'] == 1 ? 'primary' : ($row['PRO_Estado'] == 2 ? 'warning' : ($row['PRO_Estado'] == 3 ? 'danger':'')) ?> rounded-pill"><?php echo $estado ?></span>
                                </td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Acción
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <?php if ($row['PRO_Estado'] == 1) : ?>
                                            <a class="dropdown-item" href="<?php echo base_url . 'admin?page=productos/productos&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-boxes text-dark"></span> Recibir</a>
                                            <div class="dropdown-divider"></div>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=productos/view_productos&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=productos/edit_productos&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="<?php echo base_url . 'admin?page=productos/delete_productos&id=' . $row['id'] ?>"data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    // Manejar caso donde no hay productos
                                    echo "<tr><td colspan='9'>No se encontraron productos registrados.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-info">No se encontraron productos registrados.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#productostable tbody tr');

    rows.forEach(function(row) {
        var cells = row.querySelectorAll('td');
        var match = false;
        cells.forEach(function(cell) {
            if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
            }
        });
        if (match) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('downloadExcel').addEventListener('click', function() {
    var wb = XLSX.utils.table_to_book(document.getElementById('productostable'), {sheet: "Sheet JS"});
    XLSX.writeFile(wb, 'productos.xlsx');
});
</script>
<style>
        .search-box {
            margin-bottom: 15px;
        }
    </style>
</body>
</html>