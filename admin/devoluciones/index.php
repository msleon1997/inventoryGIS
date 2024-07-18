<?php
require_once '../config.php';
require_once('../classes/Devoluciones.php');

$devoluciones = new Devoluciones();
$qry = $devoluciones->obtenerDevoluciones();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Devoluciones de Productos</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
            <a href="<?php echo base_url ?>admin/?page=devoluciones/manage_devoluciones" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Crear Nueva Devolución</a>
        </div>
    </div>
    <div class="card-body">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar devolución...">
            </div>
        <div class="container-fluid">
            <div class="container-fluid">
                <?php if ($qry && is_array($qry) && count($qry) > 0) : ?>
                    <table class="table table-bordered table-stripped" id="devolucionestable">
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
                                <th>Fecha de Devolución</th>
                                <th>Código</th>
                                <th>Proveedor</th>
                                <th>Producto</th>
                                <th>Razon de Devolución</th>
                                <th>Costo Producto</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = 1;
                            if (isset($qry['devoluciones']) && is_array($qry['devoluciones'])) {
                                foreach ($qry['devoluciones'] as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($row['DEV_fecha_devolucion'])) ?></td>
                                <td><?php echo $row['DEV_codigo_devolucion'] ?></td>
                                <td><?php echo $row['proveedor_nombre'] ?></td>
                                <td><?php echo $row['producto_nombre'] ?></td>
                                <td><?php echo $row['DEV_razon_devolucion'] ?></td>
                                <td class="text-right"><?php echo number_format($row['DEV_producto_costo'], 2) ?></td>
                                <td class="text-center">
                                    <?php
                                    $estado = "N/A";
                                    switch ($row['DEV_estado_devolucion']) {
                                        case 1:
                                            $estado = "Pendiente";
                                            break;
                                        case 2:
                                            $estado = "Procesado";
                                            break;
                                        case 3:
                                            $estado = "Completado";
                                            break;
                                        case 4:
                                            $estado = "Cancelado";
                                            break;
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $row['DEV_estado_devolucion'] == 1 ? 'primary' : ($row['DEV_estado_devolucion'] == 2 ? 'warning' : ($row['DEV_estado_devolucion'] == 3 ? 'success' : 'danger')) ?> rounded-pill"><?php echo $estado ?></span>
                                </td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Acción
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=devoluciones/view_devoluciones&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=devoluciones/edit_devoluciones&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="<?php echo base_url . 'admin?page=devoluciones/delete_devoluciones&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='9'>No se encontraron devoluciones de productos.</td></tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-info">No se encontraron devoluciones de productos.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('#devolucionestable tbody tr');

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
        var wb = XLSX.utils.table_to_book(document.getElementById('devolucionestable'), {sheet: "Sheet JS"});
        XLSX.writeFile(wb, 'devoluciones.xlsx');
    });
</script>
<style>
        .search-box {
            margin-bottom: 15px;
        }
</style>
</body>
</html>

