<?php

require_once '../config.php';
require_once('../classes/Proveedores.php');

    // Obtener el id de la URL
    $id = $_GET['id'];
    
    // Crear una instancia del objeto Planificacion con la URL base
    $proveedores = new Proveedores();

    // Obtener los detalles del registro de planificación por su id
    $row = $proveedores->eliminarProveedor($id);
   
    exit();