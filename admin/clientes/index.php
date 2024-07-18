<?php
require_once '../config.php';
require_once('../classes/Clientes.php');

$clientes = new Clientes();
$qry = $clientes->obtenerClientes();
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
        <h3 class="card-title">Lista de Clientes</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
            <a href="<?php echo base_url ?>admin/?page=clientes/manage_clientes" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Crear Nuevo Cliente</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar cliente...">
            </div>
            <div class="table-responsive">
                <?php if ($qry && is_array($qry) && count($qry) > 0) : ?>
                    <table class="table table-bordered table-stripped" id="clientesTable">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="20%">
                            <col width="20%">
                            <col width="15%">
                            <col width="15%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Numero de Identificacion</th>
                                <th>Nombre Cliente</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Correo</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = 1;
                            if (isset($qry['clientes']) && is_array($qry['clientes'])) {
                                foreach ($qry['clientes'] as $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo $row['CLI_Identificacion'] ?></td>
                                <td><?php echo $row['CLI_NombresCompletos'] ?></td>
                                <td><?php echo $row['CLI_Direccion'] ?></td>
                                <td><?php echo $row['CLI_Telefono'] ?></td>
                                <td><?php echo $row['CLI_Correo'] ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Acción
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=clientes/view_clientes&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=clientes/edit_clientes&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="<?php echo base_url . 'admin?page=clientes/delete_clientes&id=' . $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
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
                    <div class="alert alert-info">No se encontraron clientes.</div>
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
    var rows = document.querySelectorAll('#clientesTable tbody tr');

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
    var wb = XLSX.utils.table_to_book(document.getElementById('clientesTable'), {sheet: "Sheet JS"});
    XLSX.writeFile(wb, 'clientes.xlsx');
});
</script>

</body>
</html>
