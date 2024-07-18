<?php
require_once '../config.php';
require_once('../classes/Ventas.php');

// URL base de la API 
//cambiar a una variale que llame al link en la clase de cada modulo
$ventas = new Ventas();

// Obtener las órdenes desde la API
$qry = $ventas->obtenerVentas();
//var_dump($qry);

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
        <h3 class="card-title">Ventas Realizadas</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
            <a href="<?php echo base_url ?>admin/?page=ventas/manage_ventas" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Generar nueva Venta</a>
        </div>
    </div>
    <div class="card-body">
         <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar venta...">
            </div>
        <div class="container-fluid">
            <div class="container-fluid">
                <?php if ($qry && is_array($qry) && count($qry) > 0) : ?>
                    <table class="table table-bordered table-stripped" id="ventastable">
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
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Codigo Venta</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Fecha Venta</th>
                                <th>Estado Venta</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
    $i = 1;
    if (isset($qry['ventas']) && is_array($qry['ventas'])) {
        foreach ($qry['ventas'] as $row) {
    ?>
        <tr>
            <td class="text-center"><?php echo $i++; ?></td>
            <td><?php echo $row['VENT_codigo_venta'] ?></td>
            <td><?php echo $row['cliente'] ?></td>
            <td><?php echo $row['producto'] ?></td>
            <td><?php echo $row['VENT_cantidad'] ?></td>
            <td class="text-right"><?php echo number_format($row['VENT_producto_costo'], 2) ?></td>
            <td class="text-right"><?php echo number_format($row['VENT_total'], 2) ?></td>
            <td><?php echo $row['VENT_fecha_venta'] ?></td>
            <td class="text-center">
                <?php
                $estado = "N/A";
                switch ($row['VENT_estado_venta']) {
                    case 1:
                        $estado = "Pendiente";
                        break;
                    case 2:
                        $estado = "Cancelada";
                        break;
                    case 3:
                        $estado = "Completada";
                        break;
                }
                ?>
                <span class="badge badge-<?php echo $row['VENT_estado_venta'] == 1 ? 'warning' : ($row['VENT_estado_venta'] == 2 ? 'danger' : ($row['VENT_estado_venta'] == 3 ? 'success' : 'danger')) ?> rounded-pill"><?php echo $estado ?></span>




            </td>
            <td align="center">
                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    Acción
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=ventas/view_ventas&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=ventas/edit_ventas&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item delete_data" href="<?php echo base_url . 'admin?page=ventas/delete_ventas&id=' . $row['id'] ?>"data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
                </div>
            </td>
        </tr>
        <?php
                }
            } else {
                // Manejar caso donde no hay órdenes
                echo "<tr><td colspan='9'>No se encontraron órdenes de compra.</td></tr>";
            }
        ?>

                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-info">No se encontraron órdenes de compra.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>




<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('#ventastable tbody tr');

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
        var wb = XLSX.utils.table_to_book(document.getElementById('ventastable'), {sheet: "Sheet JS"});
        XLSX.writeFile(wb, 'ventas.xlsx');
    });
</script>
<style>
        .search-box {
            margin-bottom: 15px;
        }
</style>
</body>
</html>