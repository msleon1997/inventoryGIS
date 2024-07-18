<?php
require_once '../config.php';
require_once('../classes/Ordenes.php');

// URL base de la API 
//cambiar a una variale que llame al link en la clase de cada modulo
$ordenes = new Ordenes();

// Obtener las órdenes desde la API
$qry = $ordenes->obtenerOrdenes();
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
        <h3 class="card-title">Órdenes de Compra</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
            <a href="<?php echo base_url ?>admin/?page=ordenes/manage_ordenes" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Crear Nueva Orden</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
        <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar cliente...">
            </div>
            <div class="container-fluid">
                <?php if ($qry && is_array($qry) && count($qry) > 0) : ?>
                    <table class="table table-bordered table-stripped"  id="ordenesTable">
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
                                <th>Fecha de Creación</th>
                                <th>Código</th>
                                <th>Proveedor</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
    $i = 1;
    if (isset($qry['ordenes']) && is_array($qry['ordenes'])) {
        foreach ($qry['ordenes'] as $row) {
    ?>
        <tr>
            <td class="text-center"><?php echo $i++; ?></td>
            <td><?php echo date("Y-m-d H:i", strtotime($row['fecha_creacion'])) ?></td>
            <td><?php echo $row['codigo_compra'] ?></td>
            <td><?php echo $row['proveedor'] ?></td>
            <td><?php echo $row['producto'] ?></td>
            <td class="text-right"><?php echo number_format($row['precio'], 2) ?></td>
            <td><?php echo $row['cantidad'] ?></td>
            <td class="text-center">
                <?php
                $estado = "N/A";
                switch ($row['estado']) {
                    case 1:
                        $estado = "Pendiente";
                        break;
                    case 2:
                        $estado = "Recibimiento Parcial";
                        break;
                    case 3:
                        $estado = "Recibido";
                        break;
                }
                ?>
                <span class="badge badge-<?php echo $row['estado'] == 1 ? 'primary' : ($row['estado'] == 2 ? 'warning' : ($row['estado'] == 3 ? 'success' : 'danger')) ?> rounded-pill"><?php echo $estado ?></span>
            </td>
            <td align="center">
                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    Acción
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=ordenes/view_ordenes&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=ordenes/edit_ordenes&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item delete_data" href="<?php echo base_url . 'admin?page=ordenes/delete_ordenes&id=' . $row['id'] ?>"data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
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

<style>
        .search-box {
            margin-bottom: 15px;
        }
    </style>
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#ordenesTable tbody tr');

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
    var wb = XLSX.utils.table_to_book(document.getElementById('ordenesTable'), {sheet: "Sheet JS"});
    XLSX.writeFile(wb, 'ordenes.xlsx');
});
</script>

</body>
</html>
