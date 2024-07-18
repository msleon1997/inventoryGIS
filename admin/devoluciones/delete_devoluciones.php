<?php

require_once '../config.php';
require_once('../classes/Devoluciones.php');



    // Obtener el id de la URL
    $id = $_GET['id'];


   
    // Crear una instancia del objeto Planificacion con la URL base
    $devoluciones = new Devoluciones();

    // Obtener los detalles del registro de planificación por su id
    $row = $devoluciones->eliminarDevoluciones($id);

   
    exit();


?>