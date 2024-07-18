<?php

require_once '../config.php';
require_once('../classes/Productos.php');

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

// Obtener lista de proveedores
$proveedores = [];
$qry_proveedores = $conn->query("SELECT * FROM tproveedores");
if ($qry_proveedores->num_rows > 0) {
    while ($row = $qry_proveedores->fetch_assoc()) {
        $proveedores[] = $row;
    }
}

function calcularTotal($cantidad, $costo, $impuesto) {
    
    $total = $cantidad * $costo * (1 + $impuesto / 100);
    return $total;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proveedor_id = $_POST["proveedor_id"];
    $cantidad_total = $_POST["PRO_Cantidad"];
    $precio_total = $_POST["PRO_Precio"];
    $impuesto = $_POST["PRO_ExcentoIva"];
    $total_producto = calcularTotal($cantidad_total, $precio_total, $impuesto);

    $datos = array(
        "PRO_Nombre" => $_POST["PRO_Nombre"],
        "PRO_Descripcion" => $_POST["PRO_Descripcion"],
        "PRO_Precio" => $precio_total,
        "PRO_ExcentoIva" => $impuesto,
        "PRO_FechaCreacion" => $_POST["PRO_FechaCreacion"],
        "PRO_Cantidad" => $cantidad_total,
        "PRO_Total" => $total_producto,
        "PRO_Estado" => $_POST["PRO_Estado"],
        "users_id" => $_POST["users_id"],
        "proveedor_id" => $proveedor_id,
    );

    $productos = new Productos();
    $respuesta = $productos->crearProductos($datos);
}

?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Registrar Productos</h3>
        <br>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="productos_frm" method="post" action="">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="PRO_Nombre" class="control-label">Nombre del producto</label>
                            <input type="text" name="PRO_Nombre" id="PRO_Nombre" autofocus class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="PRO_Descripcion" class="control-label">Descripción del producto</label>
                            <input type="text" name="PRO_Descripcion" id="PRO_Descripcion" autofocus class="form-control form-control-sm rounded-0" required>
                        </div>
                       
                        <div class="form-group col-md-4">
                            <label for="proveedor_nombre" class="control-label">Proveedor</label>
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
                            <label for="PRO_Precio" class="control-label">Precio producto</label>
                            <input type="number" step="0.1" name="PRO_Precio" id="PRO_Precio" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="PRO_ExcentoIva" class="control-label">Impuesto IVA</label>
                            <input type="number" name="PRO_ExcentoIva" id="PRO_ExcentoIva" class="form-control form-control-sm rounded-0" value="15" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="PRO_Cantidad" class="control-label">Cantidad</label>
                            <input type="number" step="1" name="PRO_Cantidad" id="PRO_Cantidad" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="PRO_Total" class="control-label">Total</label>
                            <input type="text" name="PRO_Total" id="PRO_Total" class="form-control form-control-sm rounded-0" readonly>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="PRO_FechaCreacion" class="control-label">Fecha de registro</label>
                            <input type="date" name="PRO_FechaCreacion" id="PRO_FechaCreacion" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PRO_Estado" class="control-label">Estado</label>
                            <select name="PRO_Estado" id="PRO_Estado" class="form-control form-control-sm rounded-0" required>
                                <option value="1">Pendiente</option>
                                <option value="2">Recibido</option>
                                <option value="3">Cancelado</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">
                </fieldset>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-sm rounded-0" type="submit">Guardar</button>
                        <a href="./?page=productos" class="btn btn-secondary btn-sm rounded-0">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const precioInput = document.getElementById('PRO_Precio');
    const impuestoInput = document.getElementById('PRO_ExcentoIva');
    const cantidadInput = document.getElementById('PRO_Cantidad');
    const totalInput = document.getElementById('PRO_Total');

    function calcularTotal() {
        const precio = parseFloat(precioInput.value) || 0;
        const impuesto = parseFloat(impuestoInput.value) || 0;
        const cantidad = parseFloat(cantidadInput.value) || 0;
        const total = precio * (1 + impuesto / 100) * cantidad;
        totalInput.value = total.toFixed(2);
    }

    precioInput.addEventListener('input', calcularTotal);
    impuestoInput.addEventListener('input', calcularTotal);
    cantidadInput.addEventListener('input', calcularTotal);
});
</script>
