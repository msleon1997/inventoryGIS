<?php
require_once '../config.php';
require_once('../classes/Ventas.php');

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM users WHERE id = '{$_GET['id']}'");

    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}

// Obtener lista de productos 
$productos = [];
$qry_productos = $conn->query("SELECT * FROM tproducto WHERE PRO_Estado = 2");
if ($qry_productos->num_rows > 0) {
    while ($row = $qry_productos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Obtener lista de clientes
$clientes = [];
$qry_clientes = $conn->query("SELECT * FROM tcliente");
if ($qry_clientes->num_rows > 0) {
    while ($row = $qry_clientes->fetch_assoc()) {
        $clientes[] = $row;
    }
}

// Generar el código de venta
$qry_codigo = $conn->query("SELECT VENT_codigo_venta FROM tventas ORDER BY VENT_codigo_venta DESC LIMIT 1");
if ($qry_codigo->num_rows > 0) {
    $row = $qry_codigo->fetch_assoc();
    $ultimo_codigo = intval(substr($row['VENT_codigo_venta'], 1)) + 1;
    $codigo_venta = 'v' . str_pad($ultimo_codigo, 3, '0', STR_PAD_LEFT);
} else {
    $codigo_venta = 'v001';
}

// Función para calcular el total
function calcularTotal($precio, $cantidad, $impuesto) {
    $subtotal = $precio * $cantidad;
    $impuestoTotal = $subtotal * ($impuesto / 100);
    $total = $subtotal + $impuestoTotal;
    return $total;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST["producto_id"];
    $cliente_id = $_POST["cliente_id"];
    $cantidad = $_POST["VENT_cantidad"];
    $impuesto = 15;

    // Obtener costo del producto seleccionado
    $qry_precio = $conn->query("SELECT PRO_Precio FROM tproducto WHERE id = '{$producto_id}'");
    if ($qry_precio->num_rows > 0) {
        $producto = $qry_precio->fetch_assoc();
        $precio = $producto['PRO_Precio'];
    } else {
        $precio = 0; // Manejar caso donde no se encuentra el precio
    }

    $total = calcularTotal($precio, $cantidad, $impuesto);

    $datos = array(
        "VENT_codigo_venta" => $_POST["VENT_codigo_venta"],
        "cliente_id" => $cliente_id,
        "producto_id" => $producto_id,
        "VENT_cantidad" => $cantidad,
        "VENT_producto_costo" => $precio,
        "VENT_total" => $total,
        "VENT_fecha_venta" => $_POST["VENT_fecha_venta"],
        "VENT_estado_venta" => $_POST["VENT_estado_venta"],
        "VENT_notas" => $_POST["VENT_notas"],
        "users_id" => $_POST["users_id"]
    );

    $ventas = new Ventas();

    $respuesta = $ventas->crearVentas($datos);
}
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Crear Nueva Orden de Venta</h3>
        <br>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="ordenes_frm" method="post" action="">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="VENT_codigo_venta" class="control-label">Código Venta</label>
                            <input type="text" name="VENT_codigo_venta" id="VENT_codigo_venta" value="<?php echo $codigo_venta; ?>" autofocus class="form-control form-control-sm rounded-0" readonly>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="cliente_id" class="control-label">Nombre del Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-control form-control-sm rounded-0" required>
                                <option value="" default>Seleccione al cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['CLI_NombresCompletos']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="producto_id" class="control-label">Producto</label>
                            <select name="producto_id" id="producto_id" class="form-control form-control-sm rounded-0" required>
                                <option value="" default>Seleccione el producto</option>
                                <?php foreach ($productos as $producto): ?>
                                    <option value="<?php echo $producto['id']; ?>" data-precio="<?php echo $producto['PRO_Precio']; ?>"><?php echo $producto['PRO_Nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="VENT_cantidad" class="control-label">Cantidad</label>
                            <input type="number" name="VENT_cantidad" id="VENT_cantidad" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="VENT_producto_costo" class="control-label">Precio</label>
                            <input type="number" name="VENT_producto_costo" id="VENT_producto_costo" class="form-control form-control-sm rounded-0" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="impuesto" class="control-label">Impuesto</label>
                            <input type="number" name="impuesto" id="impuesto" value="15" class="form-control form-control-sm rounded-0" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <button type="button" class="btn btn-primary" id="agregarProducto">Agregar Producto</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="tabla-scrollable">
                            <table class="table table-bordered" id="productosTable">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Impuesto</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se agregarán los productos dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="VENT_notas" class="control-label">Notas:</label>
                            <input type="text" rows="4" name="VENT_notas" id="VENT_notas" class="form-control form-control-sm rounded-0"></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="VENT_estado_venta" class="control-label">Estado de la Venta</label>
                            <select name="VENT_estado_venta" id="VENT_estado_venta" class="form-control form-control-sm rounded-0" required>
                                <option value="1">Pendiente</option>
                                <option value="2">Cancelado</option>
                                <option value="3">Completado</option>
                            </select>
                        </div>
                        <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">
                    </div>
                    <br><br>
                    <div class="card-footer text-right">
                        <button class="btn btn-flat btn-primary btn-sm" type="submit">Guardar Venta</button>
                        <a href="./?page=ventas" class="btn btn-flat btn-default border btn-sm">Cancelar</a>
                    </div>
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

    .tabla-scrollable {
        overflow-x: auto;
        width: 100%;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productosSelect = document.getElementById('producto_id');
    const precioInput = document.getElementById('VENT_producto_costo');
    const cantidadInput = document.getElementById('VENT_cantidad');

    // Evento para actualizar el precio cuando se selecciona un producto
    productosSelect.addEventListener('change', function() {
        const selectedOption = productosSelect.options[productosSelect.selectedIndex];
        const precio = selectedOption.getAttribute('data-precio');
        precioInput.value = precio ? parseFloat(precio).toFixed(2) : 0;
    });

    // Función para calcular el total
    function calcularTotal(precio, cantidad, impuesto) {
        const subtotal = parseFloat(precio) * parseFloat(cantidad);
        const impuestoTotal = subtotal * (parseFloat(impuesto) / 100);
        const total = subtotal + impuestoTotal;
        return total;
    }

    const agregarProductoBtn = document.getElementById('agregarProducto');
    const productosTable = document.getElementById('productosTable').getElementsByTagName('tbody')[0];

    agregarProductoBtn.addEventListener('click', function() {
        const selectedProductoId = productosSelect.value;
        const selectedProductoNombre = productosSelect.options[productosSelect.selectedIndex].text;
        const cantidad = cantidadInput.value;
        const precio = precioInput.value;
        const impuesto = document.getElementById('impuesto').value;
        const total = calcularTotal(precio, cantidad, impuesto);

        const newRow = productosTable.insertRow();
        newRow.innerHTML = 
           `<td>${selectedProductoNombre}</td>
            <td>${cantidad}</td>
            <td>${parseFloat(precio).toFixed(2)}</td>
            <td>${impuesto}</td>
            <td>${total.toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger btn-sm borrar-producto">Borrar</button></td>`;

        // Evento para borrar fila
        newRow.querySelector('.borrar-producto').addEventListener('click', function() {
            productosTable.deleteRow(newRow.rowIndex - 1);
        });
    });
});

$(document).ready(function() {
    $('#cliente_id').select2({
        placeholder: "Seleccione al cliente",
        allowClear: true
    });

    $('#producto_id').select2({
        placeholder: "Seleccione el producto",
        allowClear: true
    }).on('change', function() {
        var selectedOption = $(this).find(':selected');
        var precio = selectedOption.data('precio');
        $('#VENT_producto_costo').val(precio ? parseFloat(precio).toFixed(2) : 0);
    });
});
</script>
