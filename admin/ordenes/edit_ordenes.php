<?php
require_once '../config.php';
require_once('../classes/Ordenes.php');

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

// Obtener el id de la URL
$id = $_GET['id'];
$ordenes = new Ordenes();

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

// Función para calcular el total
function calcularTotal($precio, $cantidad, $impuesto, $descuento) {
    $subtotal = $precio * $cantidad;
    $impuestoTotal = $subtotal * ($impuesto / 100);
    $descuentoTotal = $subtotal * ($descuento / 100);
    $total = $subtotal + $impuestoTotal - $descuentoTotal;
    return $total;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST["producto_id"];
    $proveedores_id = $_POST["proveedores_id"];
    $precio = $_POST["precio"];
    $cantidad = $_POST["cantidad"];
    $impuesto = $_POST["impuesto"];
    $descuento = $_POST["descuento"];
    
    $total = calcularTotal($precio, $cantidad, $impuesto, $descuento);

    $datos = array(
        "codigo_compra" => $_POST["codigo_compra"],
        "proveedores_id" => $proveedores_id,
        "producto_id" => $producto_id,
        "cantidad" => $cantidad,
        "precio" => $precio,
        "impuesto" => $impuesto,
        "descuento" => $descuento,
        "total" => $total,
        "observaciones" => $_POST["observaciones"],
        "fecha_creacion" => $_POST["fecha_creacion"],
        "estado" => $_POST["estado"],
        "users_id" => $_POST["users_id"]
    );

    $respuesta = $ordenes->actualizarOrdenes($id, $datos);
}

// Obtener los detalles del registro por su id
$row = $ordenes->obtenerOrdenesPorId($id);
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Editar Nueva Orden de Compra</h3>
        <br>
    </div>
   
    <div class="card-body">
        <div class="container-fluid">
            <form id="ordenes_frm" method="post" action="">
                <input type="hidden" name="id" value="<?php echo $row['id'] ? $id : '' ?>">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="codigo_compra" class="control-label">Código de Compra</label>
                            <input type="text" name="codigo_compra" id="codigo_compra" autofocus class="form-control form-control-sm rounded-0" value="<?php echo $row['codigo_compra'] ?>" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="producto_nombre" class="control-label">Nombre del Proveedor</label>
                            <select name="proveedores_id" id="proveedores_id" class="form-control form-control-sm rounded-0" required>
                            <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['PROV_persona']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
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
                        
                        <div class="form-group col-md-4">
                            <label for="cantidad" class="control-label">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control form-control-sm rounded-0" value="<?php echo $row['cantidad']?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="precio" class="control-label">Precio</label>
                            <input type="text" name="precio" id="precio" class="form-control form-control-sm rounded-0" value="<?php echo $row['precio']?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="descuento" class="control-label">Descuento</label>
                            <input type="number" name="descuento" id="descuento" class="form-control form-control-sm rounded-0" value="<?php echo $row['descuento']?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="impuesto" class="control-label">Impuesto</label>
                            <input type="number" name="impuesto" id="impuesto" value="15" class="form-control form-control-sm rounded-0" value="<?php echo $row['impuesto']?>" readonly>
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
                                        <th>Descuento</th>
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
                            <label for="observaciones" class="control-label">Observaciones:</label>
                            <input type="text" rows="4" name="observaciones" id="observaciones" class="form-control form-control-sm rounded-0" value="<?php echo $row['observaciones']?>" required></input>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_creacion" class="control-label">Fecha Creación</label>
                            <input type="date" name="fecha_creacion" id="fecha_creacion" class="form-control form-control-sm rounded-0" value="<?php 
                                $fecha_creacion = date('Y-m-d', strtotime($row['fecha_creacion']));
                                echo $fecha_creacion?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="estado" class="control-label">Estado</label>
                            <select name="estado" id="estado" class="form-control form-control-sm rounded-0" required>
                                <option value="1">Pendiente</option>
                                <option value="2">Recibimiento Parcial</option>
                                <option value="3">Recibido</option>
                            </select>
                        </div>

                        <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id')?>">

                    </div>
                    <br><br>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <div class="row">
                                <button class="btn btn-sm btn-primary mr-2">Actualziar</button>
                                <a class="btn btn-sm btn-secondary" href="./?page=ordenes">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const agregarProductoBtn = document.getElementById('agregarProducto');
    const productosTable = document.getElementById('productosTable').getElementsByTagName('tbody')[0];
    const productosSelect = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const precioInput = document.getElementById('precio');
    const descuentoInput = document.getElementById('descuento');
    const impuestoInput = document.getElementById('impuesto');

    agregarProductoBtn.addEventListener('click', function() {
        const selectedProductoId = productosSelect.value;
        const selectedProductoNombre = productosSelect.options[productosSelect.selectedIndex].text;
        const cantidad = cantidadInput.value;
        const precio = precioInput.value;
        const descuento = descuentoInput.value;
        const impuesto = impuestoInput.value;
        const total = calcularTotal(precio, cantidad, impuesto, descuento);

        const newRow = productosTable.insertRow();
        newRow.innerHTML = 
           `<td>${selectedProductoNombre}</td>
            <td>${cantidad}</td>
            <td>${precio}</td>
            <td>${descuento}</td>
            <td>${impuesto}</td>
            <td>${total.toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger btn-sm borrar-producto">Borrar</button></td>`;

             // evento para borrar fila
        newRow.querySelector('.borrar-producto').addEventListener('click', function() {
            productosTable.deleteRow(newRow.rowIndex - 1);
        });

    
    });

    function calcularTotal(precio, cantidad, impuesto, descuento) {
        const subtotal = parseFloat(precio) * parseFloat(cantidad);
        const impuestoTotal = subtotal * (parseFloat(impuesto) / 100);
        const descuentoTotal = subtotal * (parseFloat(descuento) / 100);
        const total = subtotal + impuestoTotal - descuentoTotal;
        return total;
    }
});
</script>

<style>
    .tabla-scrollable {
    overflow-x: auto;
    width: 100%;
    }
</style>