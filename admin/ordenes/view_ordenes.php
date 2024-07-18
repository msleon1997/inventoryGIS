<?php
require_once '../config.php';
require_once('../classes/Ordenes.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la orden de compra por su ID
    $ordenes = new Ordenes();
    $row = $ordenes->obtenerOrdenesPorId($id);

    // Obtener lista de productos
    $productos = [];
    $qry_productos = $conn->query("SELECT * FROM tproducto");
    if ($qry_productos->num_rows > 0) {
        while ($producto = $qry_productos->fetch_assoc()) {
            $productos[$producto['id']] = $producto['PRO_Nombre'];
        }
    }

    // Obtener lista de proveedores
    $proveedores = [];
    $qry_proveedores = $conn->query("SELECT * FROM tproveedores");
    if ($qry_proveedores->num_rows > 0) {
        while ($proveedor = $qry_proveedores->fetch_assoc()) {
            $proveedores[$proveedor['id']] = $proveedor['PROV_persona'];
        }
    }
}
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Vista Orden de Compra</h3>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form id="ordenes_frm" method="post" action="">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <fieldset class="border-bottom">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="codigo_compra" class="control-label">Código de Compra</label>
                            <input type="text" name="codigo_compra" id="codigo_compra" autofocus class="form-control form-control-sm rounded-0" value="<?php echo $row['codigo_compra']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="proveedores_id" class="control-label">Nombre del Proveedor</label>
                            <select name="proveedores_id" id="proveedores_id" class="form-control form-control-sm rounded-0" readonly>
                                <?php foreach ($proveedores as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" readonly <?php echo ($key == $row['proveedores_id']) ? 'selected' : ''; ?>><?php echo $value; ?> </option>
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
                            <label for="cantidad" class="control-label">Cantidad</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control form-control-sm rounded-0" value="<?php echo $row['cantidad']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="precio" class="control-label">Precio</label>
                            <input type="text" name="precio" id="precio" class="form-control form-control-sm rounded-0" value="<?php echo $row['precio']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="descuento" class="control-label">Descuento</label>
                            <input type="number" name="descuento" id="descuento" class="form-control form-control-sm rounded-0" value="<?php echo $row['descuento']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="impuesto" class="control-label">Impuesto</label>
                            <input type="number" name="impuesto" id="impuesto" class="form-control form-control-sm rounded-0" value="<?php echo $row['impuesto']; ?>" readonly>
                        </div>
                    </div>
                   
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Detalles de la Orden de Compra</h3>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>Impuesto</th>
                                            <th>Total</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $productos[$row['producto_id']]; ?></td>
                                            <td><?php echo $row['cantidad']; ?></td>
                                            <td><?php echo $row['precio']; ?></td>
                                            <td><?php echo $row['descuento']; ?>%</td>
                                            <td><?php echo $row['impuesto']; ?>%</td>
                                            <td><?php echo $row['total']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                 </div>
                 <div class="row">
                        <div class="form-group col-md-12">
                            <label for="observaciones" class="control-label">Observaciones:</label>
                            <input type="text" rows="4" name="observaciones" id="observaciones" class="form-control form-control-sm rounded-0" value="<?php echo $row['observaciones']; ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_creacion" class="control-label">Fecha Creación</label>
                            <input type="date" name="fecha_creacion" id="fecha_creacion" class="form-control form-control-sm rounded-0" value="<?php echo date('Y-m-d', strtotime($row['fecha_creacion'])); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="estado" class="control-label">Estado</label>
                            <select name="estado" id="estado" class="form-control form-control-sm rounded-0" required>
                                <option value="1" <?php echo ($row['estado'] == 1) ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="2" <?php echo ($row['estado'] == 2) ? 'selected' : ''; ?>>Recibimiento Parcial</option>
                                <option value="3" <?php echo ($row['estado'] == 3) ? 'selected' : ''; ?>>Recibido</option>
                            </select>
                        </div>
                        <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id'); ?>">
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <div class="row">
                                
                                <a href="./?page=ordenes" class="btn btn-sm btn-secondary">Volver Atrás</a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>


