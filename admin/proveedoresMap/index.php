<?php
require_once '../config.php';
require_once '../classes/Proveedores.php';



// Instanciar la clase Proveedores
$proveedores = new Proveedores();

// Obtener todos los proveedores
$todosLosProveedores = $proveedores->obtenerProveedores();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Proveedores</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 600px; float: left; width: 80%; }
        .proveedores-lista { float: left; width: 20%; padding: 10px; overflow-y: auto; height: 600px; }
        .proveedor-item { margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .proveedor-nombre { font-weight: bold; }
        .whatsapp-icon { margin-left: 5px; }
        .estado-activo { background-color: green; color: white; }
        .estado-inactivo { background-color: red; color: white; }
        .geolocate-btn { margin: 10px; padding: 10px; background-color: #007bff; color: white; cursor: pointer; }
    </style>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Listado de Proveedores</h3>
        <div class="card-tools">
            <a href="<?php echo base_url ?>admin/?page=proveedores/manage_proveedores" class="btn btn-flat btn-warning"><span class="fas fa-plus"></span> Crear Nuevo Proveedor</a>
            <button class="btn btn-flat btn-success" onclick="geolocate()">Mostrar mi ubicación</button>
            <button class="btn btn-flat btn-primary" id="exportButton">Descargar Mapa</button>
<!--             <button class="btn btn-flat btn-success" onclick="window.location.href='proveedoresMap/generate_shapefile.php'">Descargar Mapa shape</button>
 -->

        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="proveedores-lista">
                    <h3>Proveedores</h3>
                    <div id="lista-proveedores"></div>
                </div>
                <div id="map"></div>
                <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                <script>
                    var map = L.map('map').setView([-1.831239, -78.183403], 7); // Coordenadas de Ecuador

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 18,
                    }).addTo(map);

                    // Datos de los proveedores obtenidos desde PHP
                    var proveedores = <?php echo json_encode($todosLosProveedores['proveedores']); ?>;

                    // Iterar sobre los proveedores y agregar marcadores
                    proveedores.forEach(function(proveedor) {
                        var latitud = proveedor['PM_Latitud'];
                        var longitud = proveedor['PM_Longitud'];
                        var nombreEmpresa = proveedor['PROV_nombre_empresa'];
                        var direccion = proveedor['PROV_direccion'];
                        var personaContacto = proveedor['PROV_persona'];
                        var email = proveedor['PROV_email'];
                        var telefono = proveedor['PROV_telefono'];
                        var estado = proveedor['estado_proveedor'];

                        // Determinar el estilo y texto del estado
                        var estadoTexto = estado === 2 ? 'Activo' : 'Inactivo';
                        var estadoClase = estado === 2 ? 'estado-activo' : 'estado-inactivo';

                        // Crear el contenido del popup con el estado como un botón
                        var popupContent = `<b>${nombreEmpresa}</b><br>${direccion}<br>${personaContacto}<br>${email}<br>${telefono}<br><br><button class="${estadoClase}">${estadoTexto}</button>`;

                        // Agregar marcador al mapa con el popup personalizado
                        L.marker([latitud, longitud]).addTo(map)
                            .bindPopup(popupContent)
                            .openPopup();

                        // Agregar el proveedor a la lista en la columna izquierda
                        var whatsappLink = `https://wa.me/${telefono.replace(/\D/g, '')}`; 

                        var proveedorItem = `
                            <div class="proveedor-item">
                                <div class="proveedor-nombre">${nombreEmpresa}</div>
                                <div>${direccion}</div>
                                <div>${personaContacto}</div>
                                <div><a href="mailto:${email}">${email}</a></div>
                                <div>
                                    <a href="${whatsappLink}" target="_blank">
                                        <i class="fab fa-whatsapp whatsapp-icon"></i>
                                    </a>
                                    <span>${telefono}</span>
                                </div>
                                <div class="${estadoClase}">Estado: ${estadoTexto}</div>
                            </div>
                        `;
                        
                        document.getElementById('lista-proveedores').innerHTML += proveedorItem;
                    });


                    // Función para geolocalizar al usuario
                    function geolocate() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var lat = position.coords.latitude;
                                var lon = position.coords.longitude;

                                // Agregar marcador para la ubicación actual
                                var userMarker = L.marker([lat, lon]).addTo(map)
                                    .bindPopup("Mi ubicación actual")
                                    .openPopup();
                                    

                                map.setView([lat, lon], 14);
                            }, function(error) {
                                console.log("Error en la geolocalización: ", error);
                            });
                        } else {
                            alert("La geolocalización no es soportada por este navegador.");
                        }
                    }


                   
             document.getElementById("exportButton").onclick = function() {
            // Crear objeto GeoJSON con datos de proveedores
            var geojsonData = {
                "type": "FeatureCollection",
                "features": []
            };

            // Iterar sobre los proveedores y agregar como características GeoJSON
            proveedores.forEach(function(proveedor) {
                var feature = {
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": [proveedor['PM_Longitud'], proveedor['PM_Latitud']]
                    },
                    "properties": {
                        "nombre_empresa": proveedor['PROV_nombre_empresa'],
                        "direccion": proveedor['PROV_direccion'],
                        "persona_contacto": proveedor['PROV_persona'],
                        "email": proveedor['PROV_email'],
                        "telefono": proveedor['PROV_telefono'],
                        "estado": proveedor['estado_proveedor'] === 2 ? 'Activo' : 'Inactivo',
                        "ciudad": proveedor['PM_Ciudad'],
                        "provincia": proveedor['PM_Provincia'],
                        "latitud": proveedor['PM_Latitud'],
                        "longitud": proveedor['PM_Longitud'],
                    }
                };
                geojsonData.features.push(feature);
            });

            // Convertir a JSON y descargar como archivo
            var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(geojsonData));
            var downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href", dataStr);
            downloadAnchorNode.setAttribute("download", "proveedores_mapa.geojson");
            document.body.appendChild(downloadAnchorNode); 
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
        };
</script>


                </script>
            </div>
        </div>
    </div>
</div>
</body>
</html>
