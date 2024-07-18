<?php
require_once '../config.php';
require_once('../classes/Ventas.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

$ventas = new Ventas();
$row = $ventas->obtenerVentasPorId($id);

 // Obtener lista de productos
 $productos = [];
 $qry_productos = $conn->query("SELECT * FROM tproducto");
 if ($qry_productos->num_rows > 0) {
     while ($producto = $qry_productos->fetch_assoc()) {
         $productos[$producto['id']] = $producto['PRO_Nombre'];
     }
 }

// Obtener lista de clientes
$clientes = [];
$qry_clientes = $conn->query("SELECT * FROM tcliente");
if ($qry_clientes->num_rows > 0) {
    while ($cliente = $qry_clientes->fetch_assoc()) {
        $clientes[$cliente['id']] = $cliente['CLI_NombresCompletos'] ;
    }
}
}
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Veista Venta Registrada</h3>
        <br>
    </div>
   
    <div class="card-body">
        <div class="container-fluid">
            <form id="ordenes_frm" method="post" action="">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="VENT_codigo_venta" class="control-label">Código Venta</label>
                            <input type="text" name="VENT_codigo_venta" id="VENT_codigo_venta" autofocus class="form-control form-control-sm rounded-0" value="<?php echo($row['VENT_codigo_venta'])?>">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="cliente_id" class="control-label">Nombre del Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-control form-control-sm rounded-0" readonly>
                                <?php foreach ($clientes as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" readonly <?php echo ($key == $row['cliente_id']) ? 'selected' : ''; ?>><?php echo $value; ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="producto_id" class="control-label">Nombre del Producto</label>
                            <select name="producto_id" id="producto_id" class="form-control form-control-sm rounded-0" readonly>
                                <?php foreach ($productos as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" readonly <?php echo ($key == $row['producto_id']) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                
                        <div class="form-group col-md-4">
                            <label for="VENT_cantidad" class="control-label">Cantidad</label>
                            <input type="number" name="VENT_cantidad" id="VENT_cantidad" class="form-control form-control-sm rounded-0" value="<?php echo($row['VENT_cantidad'])?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="VENT_producto_costo" class="control-label">Precio</label>
                            <input type="text" name="VENT_producto_costo" id="VENT_producto_costo" class="form-control form-control-sm rounded-0" value="<?php echo($row['VENT_producto_costo'])?>">
                        </div>
                       
                        <div class="form-group col-md-4">
                            <label for="impuesto" class="control-label">Impuesto</label>
                            <input type="number" name="impuesto" id="impuesto" value="15" class="form-control form-control-sm rounded-0">
                        </div>
                    </div>
                   
                    <div class="row">
                        <table class="table table-bordered" id="productosTable">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Impuesto</th>
                                    <th>Total</th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                        <tr>
                                            <td><?php echo $productos[$row['producto_id']]; ?></td>
                                            <td><?php echo $row['VENT_cantidad']; ?></td>
                                            <td><?php echo $row['VENT_producto_costo']; ?></td>
                                            <td>15%</td>
                                            <td><?php echo $row['VENT_total']; ?></td>
                                        </tr>
                                        
                            </tbody>
                        </table>

                       
                    </div>
                  
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="VENT_notas" class="control-label">Notas:</label>
                            <input type="text" rows="4" name="VENT_notas" id="VENT_notas" class="form-control form-control-sm rounded-0" value="<?php echo($row['VENT_notas'])?>"></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="VENT_fecha_venta" class="control-label">Fecha Creación</label>
                            <input type="date" name="VENT_fecha_venta" id="VENT_fecha_venta" class="form-control form-control-sm rounded-0" value="<?php 
                                $fecha_creacion = date('Y-m-d', strtotime($row['VENT_fecha_venta']));
                                echo $fecha_creacion;?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="VENT_estado_venta" class="control-label">Estado Venta</label>
                            <input type="text" name="VENT_estado_venta" id="VENT_estado_venta" class="form-control form-control-sm rounded-0" value="<?php
                                $estado = "N/A";
                                switch ($row['VENT_estado_venta']) {
                                    case 1:
                                        $estado = "Pendiente";
                                        break;
                                    case 2:
                                        $estado = "Cancelado";
                                        break;
                                    case 3:
                                        $estado = "Completado";
                                        break;
                                }
                                echo $estado;
                            ?>" readonly>
                        </div>

                        <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">

                    </div>
                    <br><br>
                  
                </fieldset>
            </form>
            <br><br>
        </div>
    </div>
</div>



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


</style>

