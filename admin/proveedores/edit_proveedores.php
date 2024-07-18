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

    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
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
                <input type="hidden" name="id" value="<?php echo $row['id'] ? $id : '' ?>">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="PROV_Identificacion" class="control-label">Identificación</label>
                            <input type="text" name="PROV_Identificacion" id="PROV_Identificacion" class="form-control" value="<?php echo $row['PROV_Identificacion'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_persona" class="control-label">Nombre Completo</label>
                            <input type="text" name="PROV_persona" id="PROV_persona" class="form-control" value="<?php echo $row['PROV_persona'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_direccion" class="control-label">Dirección</label>
                            <input type="text" name="PROV_direccion" id="PROV_direccion" class="form-control" value="<?php echo $row['PROV_direccion'] ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_nombre_empresa" class="control-label">Empresa</label>
                            <input type="text" name="PROV_nombre_empresa" id="PROV_nombre_empresa" class="form-control" value="<?php echo $row['PROV_nombre_empresa']?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_telefono" class="control-label">Teléfono</label>
                            <input type="tel" name="PROV_telefono" id="PROV_telefono" class="form-control" value="<?php echo $row['PROV_telefono']?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_email" class="control-label">Correo</label>
                            <input type="email" name="PROV_email" id="PROV_email" class="form-control" value="<?php echo $row['PROV_email'] ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="PROV_pagina_web" class="control-label">Página Web</label>
                            <input type="text" name="PROV_pagina_web" id="PROV_pagina_web" class="form-control" value="<?php echo $row['PROV_pagina_web'] ?>" >
                        </div>
                        <div class="form-group col-md-5">
                            <label for="estado_proveedor" class="control-label">Estado</label>
                            <select name="estado_proveedor" id="estado_proveedor" class="form-control form-control-sm rounded-0" required>
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
                            <select name="PM_Provincia" id="PM_Provincia" class="form-control form-control-sm rounded-0" required>
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
                            <select name="PM_Ciudad" id="PM_Ciudad" class="form-control form-control-sm rounded-0" required>
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
                            <textarea name="notas" id="notas" cols="30" rows="2" class="form-control"><?php echo $row['notas'] ?></textarea>
                        </div>
                        <input type="hidden" name="PM_Latitud" id="PM_Latitud" value="<?php echo isset($row['PM_Latitud']) ? $row['PM_Latitud'] : ''; ?>">
                        <input type="hidden" name="PM_Longitud" id="PM_Longitud" value="<?php echo isset($row['PM_Longitud']) ? $row['PM_Longitud'] : ''; ?>">
                    </div>
                    <input type="hidden" name="users_id" value="<?php echo $_settings->userdata('id'); ?>">
                    <!-- cargar mapa -->
                    <div id="mapa"></div>
                    <div class="card-footer text-right">
                        <button class="btn btn-flat btn-primary btn-sm" id="geocodeButton" type="submit">Actualizar Proveedor</button>
                        <a href="./?page=proveedores" class="btn btn-flat btn-default border btn-sm">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    
        document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('mapa').setView([-1.831239, -78.183406], 6.5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;


           // Función para centrar el mapa y colocar un marcador en la ubicación seleccionada
    function centrarMapaEnCoordenadas(lat, lng) {
        var zoomLevel = map.getZoom();
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function() {
                var latlng = marker.getLatLng();
                document.getElementById('PM_Latitud').value = latlng.lat;
                document.getElementById('PM_Longitud').value = latlng.lng;
            });
        }
        map.setView([lat, lng], zoomLevel);
        document.getElementById('PM_Latitud').value = lat;
        document.getElementById('PM_Longitud').value = lng;
    }

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        centrarMapaEnCoordenadas(lat, lng);
    });

    
    document.getElementById('geocodeButton').addEventListener('click', function() {
        var direccion = document.getElementById('PM_Direccion').value;
        var provincia = document.getElementById('PM_Provincia').value;
        var ciudad = document.getElementById('PM_Ciudad').value;
        var query = `${direccion}, ${ciudad}, ${provincia}, Ecuador`;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var lat = data[0].lat;
                    var lng = data[0].lon;
                    centrarMapaEnCoordenadas(lat, lng);
                } else {
                    alert('Dirección no encontrada');
                }
            })
            .catch(error => {
                console.error('Error en la geocodificación:', error);
                alert('Ocurrió un error en la geocodificación');
            });
    });

        // Función para actualizar el contenido del popup del marcador
        function actualizarPopupProveedor(row) {
            var contenidoPopup = `
                <div style="max-width: 250px;">
                    <h5 style="margin-bottom: 5px;"><strong>Proveedor: ${row['PROV_nombre_empresa']}</strong></h5>
                    <p style="margin-bottom: 5px;"><strong>Dirección:</strong> ${row['PROV_direccion']}</p>
                    <p style="margin-bottom: 5px;"><strong>Teléfono:</strong> ${row['PROV_telefono']}</p>
                    <p style="margin-bottom: 5px;"><strong>Correo:</strong> ${row['PROV_email']}</p>
                    <p style="margin-bottom: 5px;"><strong>Página Web:</strong> ${row['PROV_pagina_web']}</p>
                    <p style="margin-bottom: 5px;"><strong>Notas:</strong> ${row['notas']}</p>
                    <p style="margin-bottom: 5px;"><strong>Estado:</strong> ${row['estado_proveedor'] == 1 ? 'Inactivo' : 'Activo'}</p>
                </div>
            `;
            return contenidoPopup;
        }

            // Manejar cambio de provincia y ciudad (similar a tu código actual)
    document.getElementById('PM_Provincia').addEventListener('change', function() {
        var provincia = this.value;
        var ciudades = <?php echo json_encode($provincias_y_ciudades); ?>;
        var ciudadSelect = document.getElementById('PM_Ciudad');

        // Limpiar ciudades
        ciudadSelect.innerHTML = '<option value="">Seleccione una ciudad</option>';

        // Añadir ciudades de la provincia seleccionada
        if (ciudades[provincia]) {
            ciudades[provincia].forEach(function(ciudad) {
                var option = document.createElement('option');
                option.value = ciudad;
                option.textContent = ciudad;
                ciudadSelect.appendChild(option);
            });
        }
    });

        // Manejar cambio de ciudad
        document.getElementById('PM_Ciudad').addEventListener('change', function() {
            var ciudad = this.value;
            var ciudades = <?php echo json_encode($provincias_y_ciudades); ?>;
            var provincia = document.getElementById('PM_Provincia').value;

            if (ciudades[provincia] && ciudades[provincia].includes(ciudad)) {
                // Aquí puedes añadir la lógica para centrar el mapa en la ciudad seleccionada si tienes las coordenadas
                // Por ejemplo:
                var coordenadas = {
                        "Cuenca": [-2.90055, -79.00453],
                        "Girón": [-3.1575423518901276, -79.14512173841143],
                        "Gualaceo": [-2.8975, -78.7764],
                        "Nabón": [-3.975, -79.137],
                        "Paute": [-2.7861, -78.6331],
                        "Pucará": [-2.5343, -78.9331],
                        "San Fernando": [-3.1463860241495754, -79.24897080912056],
                        "Santa Isabel": [-3.276405478364109, -79.31249033123113],
                        "Sigsig": [-3.0506, -78.7728],
                        "Guaranda": [-1.59443, -79.00274],
                        "Chimbo": [-1.6764, -79.365],
                        "Echeandía": [-1.0565, -78.6168],
                        "Azogues": [-2.7416, -78.8442],
                        "Cañar": [-2.5544, -78.9422],
                        "La Troncal": [-2.4011, -79.2333],
                        "Tulcán": [0.8212, -77.732],
                        "San Gabriel": [0.6092, -77.8378],
                        "Mira": [0.3858, -78.1248],
                        "Riobamba": [-1.6636, -78.6546],
                        "Guano": [-1.6033, -78.6398],
                        "Penipe": [-1.4157, -78.7766],
                        "Latacunga": [-0.9308, -78.6157],
                        "La Mana": [-0.9389, -79.2161],
                        "Saquisilí": [-0.6825, -78.7691],
                        "Machala": [-3.2586, -79.9606],
                        "Arenillas": [-3.5613, -80.0714],
                        "Santa Rosa": [-3.4533, -79.9578],
                        "Esmeraldas": [0.9617, -79.6536],
                        "Atacames": [0.8683, -79.8454],
                        "Muisne": [0.6075, -80.0214],
                        "Puerto Baquerizo Moreno": [-0.9014, -89.6036],
                        "Puerto Ayora": [-0.7437, -90.3158],
                        "Santa Cruz": [-0.7396, -90.3155],
                        "Guayaquil": [-2.1709, -79.9224],
                        "Durán": [-2.1796, -79.8557],
                        "Samborondón": [-2.1566, -79.8007],
                        "Ibarra": [0.3503, -78.1221],
                        "Otavalo": [0.2273, -78.2607],
                        "Cotacachi": [0.3023, -78.2669],
                        "Loja": [-3.9931, -79.2042],
                        "Catamayo": [-4.0083, -79.2075],
                        "Macará": [-4.3783, -79.9461],
                        "Babahoyo": [-1.8021, -79.5341],
                        "Quevedo": [-1.0222, -79.4608],
                        "Baba": [-1.6833, -79.5167],
                        "Portoviejo": [-1.0546, -80.4547],
                        "Manta": [-0.9677, -80.7082],
                        "Chone": [-0.6886, -80.0969],
                        "Macas": [-2.3086, -78.1097],
                        "Gualaquiza": [-3.3944, -78.5486],
                        "Sucúa": [-2.4667, -78.1667],
                        "Tena": [-0.9933, -77.8167],
                        "Archidona": [-1.067, -77.605],
                        "El Chaco": [-0.4358, -77.6671],
                        "Francisco de Orellana": [-0.4646, -76.9957],
                        "La Joya de los Sachas": [-0.1161, -76.8989],
                        "Aguarico": [0.9001, -76.8762],
                        "Puyo": [-1.4925, -78.0021],
                        "Santa Clara": [-1.02, -77.8207],
                        "Mera": [-1.4974, -78.1176],
                        "Quito": [-0.1807, -78.4678],
                        "Cayambe": [0.025, -78.1417],
                        "Sangolquí": [-0.3122, -78.4467],
                        "Santa Elena": [-2.2267, -80.8583],
                        "Salinas": [-2.2167, -80.9667],
                        "La Libertad": [-2.2333, -80.9],
                        "Santo Domingo": [-0.2389, -79.1775],
                        "La Concordia": [0.7886, -79.3144],
                        "El Carmen": [-0.2647, -79.4697],
                        "Nueva Loja": [0.0847, -76.8828],
                        "Shushufindi": [0.4667, -76.9667],
                        "Gonzalo Pizarro": [0.9792, -77.8125],
                        "Ambato": [-1.2486, -78.6164],
                        "Banos": [-1.3989, -78.4214],
                        "Pelileo": [-1.3275, -78.5322],
                        "Zamora": [-4.0691, -78.9566],
                        "Yantzaza": [-3.8278, -78.7594],
                        "Zumbi": [-4.2994, -78.9739],
                };

                if (coordenadas[ciudad]) {
                    var latlng = coordenadas[ciudad];
                    centrarMapaEnCoordenadas(latlng[0], latlng[1], actualizarPopupProveedor(<?php echo json_encode($row); ?>));

                    // Actualizar los campos ocultos de latitud y longitud en el formulario
                    document.getElementById('PM_Latitud').value = latlng[0];
                    document.getElementById('PM_Longitud').value = latlng[1];
                }
            }
        });

        // Si se está editando un proveedor y ya hay coordenadas guardadas, mostrar el marcador con el contenido del popup
        var latitud = document.getElementById('PM_Latitud').value;
        var longitud = document.getElementById('PM_Longitud').value;

        if (latitud && longitud) {
            centrarMapaEnCoordenadas(latitud, longitud, actualizarPopupProveedor(<?php echo json_encode($row); ?>));
        }
    });
</script>





<style>
    /* Ajustar la altura del contenedor del mapa */
    #mapa {
        height: 400px;
        width: 100%;
        margin-bottom: 20px;
    }
</style>
