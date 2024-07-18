<?php
require_once '../config.php';
require_once('../classes/Productos.php');

// Verificar si se proporcionó un id válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obtener el id de la URL
    $id = $_GET['id'];

    // URL base de la API

    $Productos = new Productos();


    $row = $Productos->obtenerProductoPorId($id);
    
    
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Actualizar Productos</h3>
        <br>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="edit_productos_frm" method="post" action="">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="PRO_Nombre" class="control-label">Nombre del producto</label>
                            <input type="text" name="PRO_Nombre" id="PRO_Nombre" autofocus class="form-control form-control-sm rounded-0" value="<?php echo $row['PRO_Nombre']?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PRO_Descripcion" class="control-label">Descripción del producto</label>
                            <textarea type="text" name="PRO_Descripcion" id="PRO_Descripcion" autofocus class="form-control form-control-sm rounded-0"><?php echo $row['PRO_Descripcion']?></textarea>
                        </div>
                       
                        <div class="form-group col-md-6">
                            <label for="producto_nombre" class="control-label">Proveedor</label>
                            <select name="proveedor_id" id="proveedor_id" class="form-control form-control-sm rounded-0" required>
                                <option value="<?php echo $row['id']; ?>" selected><?php echo $row['PROV_persona']; ?></option>
                            </select>
                        </div>


                    </div>
                    

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="PRO_FechaCreacion" class="control-label">Fecha de registro</label>
                            <input type="date" name="PRO_FechaCreacion" id="PRO_FechaCreacion" class="form-control form-control-sm rounded-0" value="<?php echo date('Y-m-d', strtotime($row['PRO_FechaCreacion'])); ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                                <label for="PRO_Estado" class="control-label">Estado</label>
                                <select name="PRO_Estado" id="PRO_Estado" class="form-control form-control-sm rounded-0" required>
                                    <?php
                                    // Array asociativo para mapear los valores numéricos a estados legibles
                                    $estados = [
                                        1 => 'Pendiente',
                                        2 => 'Recibido',
                                        3 => 'Rechazado'
                                    ];

                                    // Iterar sobre cada estado para crear las opciones del select
                                    foreach ($estados as $value => $label) {
                                        $selected = ($value == $row['PRO_Estado']) ? 'selected' : '';
                                        echo "<option value=\"$value\" $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                        </div>
                    </div>
                   
                    <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">
                </fieldset>
                <br>
                <!-- Tabla con los campos -->
                <table class="table table-striped table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Precio producto</th>
                            <th>Impuesto IVA</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $row['PRO_Precio']; ?></td>
                            <td><?php echo $row['PRO_ExcentoIva']; ?></td>
                            <td><?php echo $row['PRO_Cantidad']; ?></td>
                            <td><?php echo $row['PRO_Total']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            
        </div>
    </div>
</div>

<?php 
 
}
?>

<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }

    hr.custom-divider {
        height: 1px;
        width: 100%;
        background-color: black;
        margin: auto;
    }

    .tabla-scrollable {
        width: 100%;
        height: 300px;
        overflow: auto;
    }

    .tabla-scrollable table {
        width: 100%;
    }

    h3.card-title {
        text-align: center;
        float: center;
    }

    p.notificacion {
        font-size: 10px !important;
    }

  /*   .card-primary.card-outline {
        border-top: 3px solid #ff0000;
    } */
</style>


