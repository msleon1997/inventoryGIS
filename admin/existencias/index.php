<?php
require_once '../config.php';
require_once('../classes/Existencias.php');

$existencias = new Existencias();
$qry = $existencias->obtenerExistencias();
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
        <h3 class="card-title">Existencias</h3>
        <div class="card-tools">
            <button id="downloadExcel" class="btn btn-success">Descargar Excel</button>
        </div>
    </div>
    <div class="card-body">
    <div class="search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar productos...">
            </div>
        <div class="container-fluid">
            <table class="table table-bordered table-stripped" id="existenciastable">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="20%">
                    <col width="10%">
                    <col width="15%">
                    <col width="15%">
                    <col width="3%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Producto</th>
                        <th>Descripción del producto</th>
                        <th>Precio del producto</th>
                        <th>Proveedor</th>
                        <th>Fecha registrada</th>
                        <th>Existencias Disponibles</th>
                        <th>Estado Stock</th>
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
                                <td><?php echo $row['proveedor_nombre'] ?></td>
                                <td><?php echo date("Y-m-d H:i", strtotime($row['PRO_FechaCreacion'])) ?></td>
                                <td><?php echo $row['PRO_Cantidad'] ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($row['PRO_Cantidad'] == 0) {
                                        echo '<button class="btn btn-danger">Sin Stock</button>';
                                    } elseif ($row['PRO_Cantidad'] > 2) {
                                        echo '<button class="btn btn-success">En Stock</button>';
                                    } else {
                                        echo '<button class="btn btn-warning">Stock Bajo</button>';
                                    }
                                    ?>
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
        </div>
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll('#existenciastable tbody tr');

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
        var wb = XLSX.utils.table_to_book(document.getElementById('existenciastable'), {sheet: "Sheet JS"});
        XLSX.writeFile(wb, 'existencias.xlsx');
    });
</script>
<style>
        .search-box {
            margin-bottom: 15px;
        }
</style>
</body>
</html>