<?php

require_once '../config.php';
require_once('../classes/Productos.php');



    // Obtener el id de la URL
    $id = $_GET['id'];


    
    // Crear una instancia del objeto Planificacion con la URL base
    $productos = new Productos();

    // Obtener los detalles del registro de planificación por su id
    $row = $productos->eliminarProductos($id);

   
    exit();
