<?php
require_once '../config.php';
require_once('../classes/Devoluciones.php');

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `users` where id = '{$_GET['id']}'");

    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}
$id = $_GET['id'];
$devoluciones = new Devoluciones();

// Obtener lista de productos con precio
$productos = [];
$qry_productos = $conn->query("SELECT * FROM tproducto");
if ($qry_productos->num_rows > 0) {
    while ($row = $qry_productos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Obtener lista de proveedores
$proveedores = [];
$qry_proveedores = $conn->query("SELECT * FROM tproveedores");
if ($qry_proveedores->num_rows > 0) {
    while ($row = $qry_proveedores->fetch_assoc()) {
        $proveedores[] = $row;
    }
}

function calcularTotalDevolucion($cantidad, $costo) {
    $total = $cantidad * $costo;
    return $total;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST["producto_id"];
    $proveedor_id = $_POST["proveedor_id"];
    $unidad = $_POST["DEV_unidad"];
    $cantidad_devuelta = $_POST["DEV_cantidad_devuelta"];
    
    // Obtener costo del producto seleccionado
    $qry_precio = $conn->query("SELECT PRO_Precio FROM tproducto WHERE id = '{$producto_id}'");
    if ($qry_precio->num_rows > 0) {
        $producto = $qry_precio->fetch_assoc();
        $producto_costo = $producto['PRO_Precio'];
    } else {
        $producto_costo = 0; // Manejar caso donde no se encuentra el precio
    }

    $total_devolucion = calcularTotalDevolucion($cantidad_devuelta, $producto_costo);

    $datos = array(
        "DEV_codigo_devolucion" => $_POST["DEV_codigo_devolucion"],
        "DEV_unidad" => $unidad,
        "DEV_cantidad_devuelta" => $cantidad_devuelta,
        "DEV_producto_costo" => $producto_costo,
        "DEV_total_devolucion" => $total_devolucion,
        "DEV_razon_devolucion" => $_POST["DEV_razon_devolucion"],
        "DEV_fecha_devolucion" => $_POST["DEV_fecha_devolucion"],
        "DEV_estado_devolucion" => $_POST["DEV_estado_devolucion"],
        "DEV_notas" => $_POST["DEV_notas"],
        "proveedor_id" => $proveedor_id,
        "producto_id" => $producto_id,
        "users_id" => $_POST["users_id"],
    );

  
    
    $respuesta = $devoluciones->actualizarDevoluciones($id, $datos);
}

    // Obtener los detalles del registro  por su id
    $row = $devoluciones->obtenerDevolucionPorId($id);
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Actualizar Devolución</h3>
        <br>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="edit_devoluciones_frm" method="post" action="">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="DEV_codigo_devolucion" class="control-label">Código de devolución</label>
                            <input type="text" name="DEV_codigo_devolucion" id="DEV_codigo_devolucion" autofocus class="form-control form-control-sm rounded-0" value="<?php echo $row['DEV_codigo_devolucion']?>" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="producto_id" class="control-label">Nombre del Producto</label>
                            <select name="producto_id" id="producto_id" class="form-control form-control-sm rounded-0" required>
                                <?php foreach ($productos as $producto): ?>
                                    <?php
                                    $selected = ($producto['id'] == $row['producto_id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $producto['id']; ?>" <?php echo $selected; ?>><?php echo $producto['PRO_Nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>



                        <div class="form-group col-md-5">
                            <label for="producto_nombre" class="control-label">Nombre del Proveedor</label>
                            <select name="proveedor_id" id="proveedor_id" class="form-control form-control-sm rounded-0" required>
                            <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['PROV_persona']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="DEV_producto_costo" class="control-label">Precio producto</label>
                            <input type="number" name="DEV_producto_costo" id="DEV_producto_costo" class="form-control form-control-sm rounded-0" value="<?php echo $row['DEV_producto_costo']?>" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="DEV_unidad" class="control-label">Unidad de medida</label>
                            <input type="text" name="DEV_unidad" id="DEV_unidad" class="form-control form-control-sm rounded-0" value="<?php echo $row['DEV_unidad']?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="DEV_cantidad_devuelta" class="control-label">Cantidad devuelta</label>
                            <input type="number" step="1" name="DEV_cantidad_devuelta" id="DEV_cantidad_devuelta" class="form-control form-control-sm rounded-0" value="<?php echo $row['DEV_cantidad_devuelta']?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="DEV_total_devolucion" class="control-label">Total devolución</label>
                            <input type="text" name="DEV_total_devolucion" id="DEV_total_devolucion" class="form-control form-control-sm rounded-0" value="<?php echo $row['DEV_total_devolucion']?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="DEV_razon_devolucion" class="control-label">Razón de devolución</label>
                            <textarea name="DEV_razon_devolucion" id="DEV_razon_devolucion" class="form-control form-control-sm rounded-0"><?php echo $row['DEV_razon_devolucion']?></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="DEV_notas" class="control-label">Notas adicionales</label>
                            <textarea name="DEV_notas" id="DEV_notas" class="form-control form-control-sm rounded-0"><?php echo $row['DEV_notas']?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="DEV_fecha_devolucion" class="control-label">Fecha de Devolución</label>
                            <input type="date" name="DEV_fecha_devolucion" id="DEV_fecha_devolucion" class="form-control form-control-sm rounded-0" value="<?php echo date('Y-m-d', strtotime($row['DEV_fecha_devolucion'])); ?>" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="DEV_estado_devolucion" class="control-label">Estado de Devolución</label>
                            <select name="DEV_estado_devolucion" id="DEV_estado_devolucion" class="form-control form-control-sm rounded-0" required>
                                <?php
                                // Array asociativo para mapear los valores numéricos a estados legibles
                                $estados = [
                                    1 => 'Pendiente',
                                    2 => 'Procesado',
                                    3 => 'Completado',
                                    4 => 'Rechazado'
                                ];

                                // Iterar sobre cada estado para crear las opciones del select
                                foreach ($estados as $value => $label) {
                                    $selected = ($value == $row['DEV_estado_devolucion']) ? 'selected' : '';
                                    echo "<option value=\"$value\" $selected>$label</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                    <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">
                </fieldset>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-sm rounded-0" type="submit">Actualizar</button>
                        <a href="./?page=devoluciones" class="btn btn-secondary btn-sm rounded-0">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('producto_nombre').addEventListener('change', function() {
        var precio = this.options[this.selectedIndex].getAttribute('data-precio');
        document.getElementById('DEV_producto_costo').value = precio;
        actualizarTotalDevolucion();
    });

    document.getElementById('DEV_cantidad_devuelta').addEventListener('input', function() {
        actualizarTotalDevolucion();
    });

    function actualizarTotalDevolucion() {
        var precio = parseFloat(document.getElementById('DEV_producto_costo').value);
        var cantidadDevuelta = parseFloat(document.getElementById('DEV_cantidad_devuelta').value);
        if (!isNaN(precio) && !isNaN(cantidadDevuelta)) {
            var totalDevolucion = precio * cantidadDevuelta;
            document.getElementById('DEV_total_devolucion').value = totalDevolucion.toFixed(2);
        }
    }
</script>
