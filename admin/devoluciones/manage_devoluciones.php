<?php
require_once '../config.php';
require_once('../classes/Devoluciones.php');

// Función para generar el código de devolución en el formato dev001, dev002, etc.
function generarCodigoDevolucion($conn) {
    $qry = $conn->query("SELECT DEV_codigo_devolucion FROM tdevoluciones ORDER BY DEV_codigo_devolucion DESC LIMIT 1");
    if ($qry->num_rows > 0) {
        $row = $qry->fetch_assoc();
        $ultimoCodigo = $row['DEV_codigo_devolucion'];
        $numero = (int)substr($ultimoCodigo, 3) + 1;
        $nuevoCodigo = 'dev' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    } else {
        $nuevoCodigo = 'dev001';
    }
    return $nuevoCodigo;
}

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

    $devoluciones = new Devoluciones();
    $respuesta = $devoluciones->crearDevoluciones($datos);
}

$codigoDevolucion = generarCodigoDevolucion($conn);
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Crear Nueva Devolución</h3>
        <br>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="devoluciones_frm" method="post" action="">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="DEV_codigo_devolucion" class="control-label">Código de devolución</label>
                            <input type="text" name="DEV_codigo_devolucion" id="DEV_codigo_devolucion" value="<?php echo $codigoDevolucion; ?>" autofocus class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="producto_nombre" class="control-label">Nombre del Producto</label>
                            <select name="producto_id" id="producto_nombre" class="form-control form-control-sm rounded-0" required>
                                <option value="">Seleccionar Producto</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['id']; ?>" data-precio="<?php echo $producto['PRO_Precio']; ?>"><?php echo $producto['PRO_Nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="proveedor_nombre" class="control-label">Nombre del Proveedor</label>
                            <select name="proveedor_id" id="proveedor_nombre" class="form-control form-control-sm rounded-0" required>
                                <option value="">Seleccionar Proveedor</option>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['PROV_persona']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="DEV_producto_costo" class="control-label">Precio producto</label>
                            <input type="number" name="DEV_producto_costo" id="DEV_producto_costo" class="form-control form-control-sm rounded-0" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="DEV_unidad" class="control-label">Unidad de medida</label>
                            <input type="text" name="DEV_unidad" id="DEV_unidad" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="DEV_cantidad_devuelta" class="control-label">Cantidad devuelta</label>
                            <input type="number" step="1" name="DEV_cantidad_devuelta" id="DEV_cantidad_devuelta" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="DEV_total_devolucion" class="control-label">Total devolución</label>
                            <input type="text" name="DEV_total_devolucion" id="DEV_total_devolucion" class="form-control form-control-sm rounded-0" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="DEV_razon_devolucion" class="control-label">Razón de devolución</label>
                            <textarea name="DEV_razon_devolucion" id="DEV_razon_devolucion" class="form-control form-control-sm rounded-0" required></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="DEV_notas" class="control-label">Notas adicionales</label>
                            <textarea name="DEV_notas" id="DEV_notas" class="form-control form-control-sm rounded-0"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="DEV_fecha_devolucion" class="control-label">Fecha de Devolución</label>
                            <input type="date" name="DEV_fecha_devolucion" id="DEV_fecha_devolucion" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="DEV_estado_devolucion" class="control-label">Estado de Devolución</label>
                            <select name="DEV_estado_devolucion" id="DEV_estado_devolucion" class="form-control form-control-sm rounded-0" required>
                                <option value="1">Pendiente</option>
                                <option value="2">Aprobado</option>
                                <option value="3">Rechazado</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">
                </fieldset>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-sm rounded-0" type="submit">Guardar</button>
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
