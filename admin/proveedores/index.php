<?php
require_once '../config.php';
require_once('../classes/Proveedores.php');


$proveedores = new Proveedores();
$qry = $proveedores->obtenerProveedores();
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
        <h3 class="card-title">Listado de Proveedores</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
            <a href="<?php echo base_url ?>admin/?page=proveedores/manage_proveedores" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Crear Nuevo Proveedor</a>
        </div>
    </div>
    <div class="card-body">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar proveedor...">
            </div>
        <div class="container-fluid">
            <div class="container-fluid">
                <?php if ($qry && is_array($qry) && count($qry) > 0) : ?>
                    <table class="table table-bordered table-stripped" id="proveedorestable">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="20%">
                            <col width="20%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de Registro</th>
                                <th>Numero de Identificacion</th>
                                <th>Nombre Proveedor</th>
                                <th>Empresa</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Estado Proveedor</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
    $i = 1;
    if (isset($qry['proveedores']) && is_array($qry['proveedores'])) {
        foreach ($qry['proveedores'] as $row) {
    ?>
        <tr>
            <td class="text-center"><?php echo $i++; ?></td>
            <td><?php echo date("Y-m-d H:i", strtotime($row['PROV_fecha_registro'])) ?></td>
            <td><?php echo $row['PROV_Identificacion'] ?></td>
            <td><?php echo $row['PROV_persona'] ?></td>
            <td><?php echo $row['PROV_nombre_empresa'] ?></td>
            <td><?php echo $row['PROV_email'] ?></td>
            <td><?php echo $row['PROV_telefono'] ?></td>
            <td class="text-center">
                                <?php
                                    $estado = "N/A";
                                    switch ($row['estado_proveedor']) {
                                        case 1:
                                            $estado = "Inactivo";
                                            break;
                                        case 2:
                                            $estado = "Activo";
                                            break;

                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $row['estado_proveedor'] == 1 ? 'danger' : ($row['estado_proveedor'] == 2 ? 'success':'') ?> rounded-pill"><?php echo $estado ?></span>
                                </td>
            <td align="center">
                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    Acción
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=proveedores/view_proveedores&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url . 'admin?page=proveedores/edit_proveedores&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item delete_data" href="<?php echo base_url . 'admin?page=proveedores/delete_proveedores&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
                </div>
            </td>
        </tr>
        <?php
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron clientes.</td></tr>";
            }
        ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <div class="alert alert-info">No se encontraron proveedores.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('#proveedorestable tbody tr');

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
        var wb = XLSX.utils.table_to_book(document.getElementById('proveedorestable'), {sheet: "Sheet JS"});
        XLSX.writeFile(wb, 'proveedores.xlsx');
    });
</script>
<style>
        .search-box {
            margin-bottom: 15px;
        }
</style>
</body>
</html>