
<?php
require_once '../config.php';

class Ordenes extends DBConnection {

    private $settings;

    public function __destruct() {
        parent::__destruct();
    }

    private $base_url;

    public function __construct() {
        parent::__construct();
        $this->base_url = 'http://localhost:5000/api/ordenes';
    }

    // Método para obtener todas las planificaciones
    public function obtenerOrdenes() {
        $url = $this->base_url;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    // Método para obtener una planificación por su ID
    public function obtenerOrdenesPorId($id) {
        $url = $this->base_url . "/" . $id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function obtenerOrdenesPorUser($users_id) {
        $url = $this->base_url . "/" . $users_id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function crearOrdenes($datos) {
        $url = $this->base_url;
        $opciones = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($datos)
            )
        );
        $context = stream_context_create($opciones);
        $response = file_get_contents($url, false, $context);

        if ($response) {
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Ordenes de producto registradas satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=ordenes';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al registrar las ordenes de productos.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    public function actualizarOrdenes($id, $datos) {
        $url = $this->base_url . "/" . $id;
        $opciones = array(
            'http' => array(
                'method' => 'PUT',
                'header' => 'Content-type: application/json',
                'content' => json_encode($datos)
            )
        );
        $context = stream_context_create($opciones);
        $response = file_get_contents($url, false, $context);

        if ($response !== false) {
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Ordenes de compra actualizadas satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=ordenes';
                    }
                });
            </script>";
        } else {
            $error = error_get_last();
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar las ordenes de compra: " . $error['message'] . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    public function eliminarOrdenes($id) {
        $url = $this->base_url . "/" . $id;
        $opciones = array(
            'http' => array(
                'method' => 'DELETE',
                'header' => 'Content-type: application/json'
            )
        );
        $context = stream_context_create($opciones);
        $response = file_get_contents($url, false, $context);

        if ($response !== false) {
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Orden de compra eliminada satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=ordenes';
                    }
                });
            </script>";
        } else {
            $error = error_get_last();
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar el registro de orden de compra: " . $error['message'] . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }
}
?>



