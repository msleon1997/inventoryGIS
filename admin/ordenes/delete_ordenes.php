<?php

require_once '../config.php';
require_once('../classes/Ordenes.php');



    // Obtener el id de la URL
    $id = $_GET['id'];


    
    // Crear una instancia del objeto Planificacion con la URL base
    $ordenes = new Ordenes();

    // Obtener los detalles del registro de planificación por su id
    $row = $ordenes->eliminarOrdenes($id);

   
    exit();


?>