<?php

require_once '../config.php';
require_once('../classes/Ventas.php');



    // Obtener el id de la URL
    $id = $_GET['id'];


    
    // Crear una instancia del objeto Planificacion con la URL base
    $ventas = new Ventas();

    // Obtener los detalles del registro de planificación por su id
    $row = $ventas->eliminarVentas($id);

   
    exit();


?>