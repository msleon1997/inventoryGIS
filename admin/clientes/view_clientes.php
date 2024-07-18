<?php
require_once '../config.php';
require_once('../classes/Clientes.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obtener el id de la URL
    $id = $_GET['id'];
$clientes = new Clientes();

$row = $clientes->obtenerClientePorId($id);

?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Detalles del Cliente</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="form-group">
                <label for="CLI_Identificacion" class="control-label">Identificación</label>
                <input type="text" class="form-control" value="<?php echo $row['CLI_Identificacion'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="CLI_NombresCompletos" class="control-label">Nombres Completos</label>
                <input type="text" class="form-control" value="<?php echo $row['CLI_NombresCompletos'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="CLI_Direccion" class="control-label">Dirección</label>
                <input type="text" class="form-control" value="<?php echo $row['CLI_Direccion'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="CLI_Correo" class="control-label">Email</label>
                <input type="email" class="form-control" value="<?php echo $row['CLI_Correo'] ?>" readonly>
            </div>
            <div class="form-group">
                <label for="CLI_Telefono" class="control-label">Teléfono</label>
                <input type="tel" class="form-control" value="<?php echo $row['CLI_Telefono']?>" readonly>
            </div>
            <div class="form-group">
                <label for="fecha_nace" class="control-label">Fecha Nacimiento</label>
                <input type="date" class="form-control" value="<?php 
                 $fecha_nace = date('Y-m-d', strtotime($row['CLI_FechaNacimiento']));
                 echo $fecha_nace;?>" readonly>
                
               
            </div>
            <br>
            <div class="card-footer text-right">
                <a href="./?page=clientes" class="btn btn-flat btn-default border btn-sm">Volver</a>
            </div>
        </div>
    </div>
</div>
<?php 
 
}
?>