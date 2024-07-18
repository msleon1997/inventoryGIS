<?php
require_once '../config.php';
require_once('../classes/Devoluciones.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obtener el id de la URL
    $id = $_GET['id'];
$devoluciones = new Devoluciones();

$row = $devoluciones->obtenerDevolucionPorId($id);

?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Lista de Devoluciones</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="devolucionesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha de Devolución</th>
                        <th>Código de Producto</th>
                        <th>Unidad</th>
                        <th>Cantidad Devuelta</th>
                        <th>Costo Producto</th>
                        <th>Total Devolución</th>
                        <th>Observaciones</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['DEV_fecha_devolucion']; ?></td>
                            <td><?php echo $row['DEV_codigo_devolucion']; ?></td>
                            <td><?php echo $row['DEV_unidad']; ?></td>
                            <td><?php echo $row['DEV_cantidad_devuelta']; ?></td>
                            <td><?php echo $row['DEV_producto_costo']; ?></td>
                            <td><?php echo $row['DEV_total_devolucion']; ?></td>
                            <td><?php echo $row['DEV_notas']; ?></td>
                            <td><?php 
                                $estado = "N/A";
                                switch ($row['DEV_estado_devolucion']) {
                                    case 1:
                                        $estado = "Pendiente";
                                        break;
                                    case 2:
                                        $estado = "Procesado";
                                        break;
                                    case 3:
                                        $estado = "Recibido";
                                        break;
                                }
                                echo $estado;
                            ?></td>
                        </tr>
                   
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php 
 
}
?>
<style>
    .card-primary.card-outline {
        border-top: 3px solid #007bff;
    }
</style>
