<?php
require_once '../config.php';
require_once('../classes/Proveedores.php');

$provincias_y_ciudades = [
    "Azuay" => ["Cuenca", "Girón", "Gualaceo", "Nabón", "Paute", "Pucará", "San Fernando", "Santa Isabel", "Sigsig"],
    "Bolívar" => ["Guaranda", "Chimbo", "Echeandía"],
    "Cañar" => ["Azogues", "Cañar", "La Troncal"],
    "Carchi" => ["Tulcán", "San Gabriel", "Mira"],
    "Chimborazo" => ["Riobamba", "Guano", "Penipe"],
    "Cotopaxi" => ["Latacunga", "La Mana", "Saquisilí"],
    "El Oro" => ["Machala", "Arenillas", "Santa Rosa"],
    "Esmeraldas" => ["Esmeraldas", "Atacames", "Muisne"],
    "Galápagos" => ["Puerto Baquerizo Moreno", "Puerto Ayora", "Santa Cruz"],
    "Guayas" => ["Guayaquil", "Durán", "Samborondón"],
    "Imbabura" => ["Ibarra", "Otavalo", "Cotacachi"],
    "Loja" => ["Loja", "Catamayo", "Macará"],
    "Los Ríos" => ["Babahoyo", "Quevedo", "Baba"],
    "Manabí" => ["Portoviejo", "Manta", "Chone"],
    "Morona Santiago" => ["Macas", "Gualaquiza", "Sucúa"],
    "Napo" => ["Tena", "Archidona", "El Chaco"],
    "Orellana" => ["Francisco de Orellana", "La Joya de los Sachas", "Aguarico"],
    "Pastaza" => ["Puyo", "Santa Clara", "Mera"],
    "Pichincha" => ["Quito", "Cayambe", "Sangolquí"],
    "Santa Elena" => ["Santa Elena", "Salinas", "La Libertad"],
    "Santo Domingo de los Tsáchilas" => ["Santo Domingo", "La Concordia", "El Carmen"],
    "Sucumbíos" => ["Nueva Loja", "Shushufindi", "Gonzalo Pizarro"],
    "Tungurahua" => ["Ambato", "Banos", "Pelileo"],
    "Zamora Chinchipe" => ["Zamora", "Yantzaza", "Zumbi"],
];

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `users` where id = '{$_GET['id']}'");
}

$id = $_GET['id'];
$proveedor = new Proveedores();

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificacion = $_POST["PROV_Identificacion"];
    $nombre_empresa = $_POST["PROV_nombre_empresa"];
    $direccion = $_POST["PROV_direccion"];
    $proveedor_nombre = $_POST["PROV_persona"];
    $telefono = $_POST["PROV_telefono"];
    $correo = $_POST["PROV_email"];
    $pagina = $_POST["PROV_pagina_web"];
    $notas = $_POST["notas"];
    $estado_proveedor = $_POST["estado_proveedor"];
    $ciudad = $_POST["PM_Ciudad"];
    $provincia = $_POST["PM_Provincia"];
    $longitud = $_POST["PM_Longitud"];
    $latitud = $_POST["PM_Latitud"];
    $users_id = $_POST["users_id"];

    $datos = array(
        "PROV_Identificacion" => $identificacion,
        "PROV_nombre_empresa" => $nombre_empresa,
        "PROV_direccion" => $direccion,
        "PROV_persona" => $proveedor_nombre,
        "PROV_telefono" => $telefono,
        "PROV_email" => $correo,
        "PROV_pagina_web" => $pagina,
        "notas" => $notas,
        "estado_proveedor" => $estado_proveedor,
        "PM_Ciudad" => $ciudad,
        "PM_Provincia" => $provincia,
        "PM_Longitud" => $longitud,
        "PM_Latitud" => $latitud,
        "users_id" => $users_id
    );

    $respuesta = $proveedor->actualizarProveedor($id, $datos);
}

$row = $proveedor->obtenerProveedorPorId($id);
?>

<div class="container mt-4">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Actualizar Cliente</h3>
            <br>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form id="proveedors_frm" method="post" action="">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="PROV_Identificacion" class="control-label">Identificación</label>
                            <input type="text" name="PROV_Identificacion" id="PROV_Identificacion" class="form-control" value="<?php echo $row['PROV_Identificacion'] ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_persona" class="control-label">Nombre Completo</label>
                            <input type="text" name="PROV_persona" id="PROV_persona" class="form-control" value="<?php echo $row['PROV_persona'] ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_direccion" class="control-label">Dirección</label>
                            <input type="text" name="PROV_direccion" id="PROV_direccion" class="form-control" value="<?php echo $row['PROV_direccion'] ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_nombre_empresa" class="control-label">Empresa</label>
                            <input type="text" name="PROV_nombre_empresa" id="PROV_nombre_empresa" class="form-control" value="<?php echo $row['PROV_nombre_empresa']?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_telefono" class="control-label">Teléfono</label>
                            <input type="tel" name="PROV_telefono" id="PROV_telefono" class="form-control" value="<?php echo $row['PROV_telefono']?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_email" class="control-label">Correo</label>
                            <input type="email" name="PROV_email" id="PROV_email" class="form-control" value="<?php echo $row['PROV_email'] ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_pagina_web" class="control-label">Página Web</label>
                            <input type="text" name="PROV_pagina_web" id="PROV_pagina_web" class="form-control" value="<?php echo $row['PROV_pagina_web'] ?>" readonly>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="estado_proveedor" class="control-label">Estado</label>
                            <select name="estado_proveedor" id="estado_proveedor" class="form-control form-control-sm rounded-0" readonly>
                                <?php
                                $estados = [
                                    1 => 'Inactivo',
                                    2 => 'Activo',
                                ];
                                foreach ($estados as $value => $label) {
                                    $selected = ($value == $row['estado_proveedor']) ? 'selected' : '';
                                    echo "<option value=\"$value\" $selected>$label</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PM_Provincia" class="control-label">Provincia</label>
                            <select name="PM_Provincia" id="PM_Provincia" class="form-control form-control-sm rounded-0" readonly>
                                <?php foreach ($provincias_y_ciudades as $provincia => $ciudades): ?>
                                    <?php
                                    $selected = ($provincia == $row['PM_Provincia']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $provincia ?>" <?php echo $selected ?>><?php echo $provincia ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="PM_Ciudad" class="control-label">Ciudad</label>
                            <select name="PM_Ciudad" id="PM_Ciudad" class="form-control form-control-sm rounded-0" readonly>
                                <?php foreach ($provincias_y_ciudades as $provincia => $ciudades): ?>
                                    <?php
                                    $selected_provincia = ($provincia == $row['PM_Provincia']) ? 'selected' : '';
                                    ?>
                                    <optgroup label="<?php echo $provincia ?>" <?php echo $selected_provincia ?>>
                                        <?php foreach ($ciudades as $ciudad): ?>
                                            <?php
                                            $selected_ciudad = ($ciudad == $row['PM_Ciudad']) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $ciudad ?>" <?php echo $selected_ciudad ?>><?php echo $ciudad ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="notas" class="control-label">Notas</label>
                            <textarea name="notas" id="notas" cols="30" rows="2" class="form-control" readonly><?php echo $row['notas'] ?></textarea>
                        </div>
                        <input type="hidden" name="PM_Latitud" id="PM_Latitud" value="<?php echo isset($row['PM_Latitud']) ? $row['PM_Latitud'] : ''; ?>">
                        <input type="hidden" name="PM_Longitud" id="PM_Longitud" value="<?php echo isset($row['PM_Longitud']) ? $row['PM_Longitud'] : ''; ?>">
                    </div>
                    <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id'); ?>">
                    <div id="mapa"></div>
                </form>
                <div class="card-footer text-right">
                        
                    <a class="btn btn-flat btn-primary btn-sm" href="./?page=proveedores">Atrás</a>
                      
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ajustar la altura del contenedor del mapa */
    #mapa {
        height: 400px;
        width: 100%;
        margin-bottom: 20px;
    }
</style>

<script>
    // Función para inicializar el mapa
    function initMap() {
        // Obtener las coordenadas de latitud y longitud
        var latitud = <?php echo json_encode($row['PM_Latitud']); ?>;
        var longitud = <?php echo json_encode($row['PM_Longitud']); ?>;

        // Crear el mapa
        var map = L.map('mapa').setView([latitud, longitud], 13);

        // Agregar la capa de mapas de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Crear un marcador en la ubicación del proveedor
        var marker = L.marker([latitud, longitud]).addTo(map);
        
        // Contenido HTML personalizado para el popup
        var popupContent = `
            <div style="max-width: 250px;">
                <h5 style="margin-bottom: 5px;"><?php echo $row['PROV_nombre_empresa']; ?></h5>
                <p style="margin-bottom: 5px;"><strong>Dirección:</strong> <?php echo $row['PROV_direccion']; ?></p>
                <p style="margin-bottom: 5px;"><strong>Teléfono:</strong> <?php echo $row['PROV_telefono']; ?></p>
                <p style="margin-bottom: 5px;"><strong>Correo:</strong> <?php echo $row['PROV_email']; ?></p>
                <p style="margin-bottom: 5px;"><strong>Página Web:</strong> <?php echo $row['PROV_pagina_web']; ?></p>
                <p style="margin-bottom: 5px;"><strong>Notas:</strong> <?php echo $row['notas']; ?></p>
                <p style="margin-bottom: 5px;"><strong>Estado:</strong> <?php echo $row['estado_proveedor'] == 1 ? 'Inactivo' : 'Activo'; ?></p>
            </div>
        `;

        // Agregar el contenido del popup al marcador
        marker.bindPopup(popupContent).openPopup();
    }

    // Ejecutar la función para inicializar el mapa cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', initMap);
</script>

