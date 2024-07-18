<?php
require_once '../config.php';
require_once('../classes/Clientes.php');




if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `users` where id = '{$_GET['id']}'");
    $id = $_GET['id'];
    $cliente = $clientes->obtenerClientePorId($id);

    if (!$cliente || !isset($cliente['cliente'])) {
        echo "<script>alert('Cliente no encontrado.')</script>";
        echo "<script>window.location.href = './index.php';</script>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificacion = $_POST["CLI_Identificacion"];
    $nombre_completo = $_POST["CLI_NombresCompletos"];
    $direccion = $_POST["CLI_Direccion"];
    $telefono = $_POST["CLI_Telefono"];
    $correo = $_POST["CLI_Correo"];
    $fecha_nacimiento = $_POST["CLI_FechaNacimiento"];
    $users_id = $_POST["users_id"];

    $datos = array(
        "CLI_Identificacion" => $identificacion,
        "CLI_NombresCompletos" => $nombre_completo,
        "CLI_Direccion" => $direccion,
        "CLI_Telefono" => $telefono,
        "CLI_Correo" => $correo,
        "CLI_FechaNacimiento" => $fecha_nacimiento,
        "users_id" => $users_id
    );
    $clientes = new Clientes();
    $respuesta = $clientes->crearCliente($datos);
}
?>

<div class="card card-outline card-primary">
    
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($_GET['id']) ? 'Editar Cliente' : 'Crear Nuevo Cliente'; ?></h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="clientes_frm" method="post" action="">
                <div class="form-group">
                    <label for="CLI_Identificacion" class="control-label">Identificación</label>
                    <input type="text" name="CLI_Identificacion" id="CLI_Identificacion" class="form-control" value="<?php echo isset($cliente['CLI_Identificacion']) ? $cliente['CLI_Identificacion'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="CLI_NombresCompletos" class="control-label">Nombre Completo</label>
                    <input type="text" name="CLI_NombresCompletos" id="CLI_NombresCompletos" class="form-control" value="<?php echo isset($cliente['CLI_NombrePrincipal']) ? $cliente['CLI_NombrePrincipal'] . ' ' . $cliente['CLI_NombreSecundario'] . ' ' . $cliente['CLI_ApellidoPaterno'] . ' ' . $cliente['CLI_ApellidoMaterno'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="CLI_Direccion" class="control-label">Dirección</label>
                    <input type="text" name="CLI_Direccion" id="CLI_Direccion" class="form-control" value="<?php echo isset($cliente['CLI_Direccion']) ? $cliente['CLI_Direccion'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="CLI_Telefono" class="control-label">Teléfono</label>
                    <input type="tel" name="CLI_Telefono" id="CLI_Telefono" class="form-control" value="<?php echo isset($cliente['CLI_Telefono']) ? $cliente['CLI_Telefono'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="CLI_Correo" class="control-label">Correo</label>
                    <input type="email" name="CLI_Correo" id="CLI_Correo" class="form-control" value="<?php echo isset($cliente['CLI_Correo']) ? $cliente['CLI_Correo'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="CLI_FechaNacimiento" class="control-label">Fecha de Nacimiento</label>
                    <input type="date" name="CLI_FechaNacimiento" id="CLI_FechaNacimiento" class="form-control" value="<?php echo isset($cliente['CLI_FechaNacimiento']) ? $cliente['CLI_FechaNacimiento'] : ''; ?>" required>
                </div>
                <br>
                <div class="card-footer text-right">
                    <button class="btn btn-flat btn-primary btn-sm" type="submit">Guardar Cliente</button>
                    <a href="./?page=clientes" class="btn btn-flat btn-default border btn-sm">Cancelar</a>
                </div>
                <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">

            </form>
        </div>
    </div>
</div>

   
