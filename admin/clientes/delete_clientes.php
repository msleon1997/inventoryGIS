<?php

require_once '../config.php';
require_once('../classes/Clientes.php');

    // Obtener el id de la URL
    $id = $_GET['id'];

    // Crear una instancia del objeto Planificacion con la URL base
    $clientes = new Clientes();

    // Obtener los detalles del registro de planificación por su id
    $row = $clientes->eliminarCliente($id);

    exit();

?>