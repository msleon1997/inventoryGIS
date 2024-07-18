
<?php
require_once '../config.php';

class Ventas extends DBConnection {

    private $settings;

    public function __destruct() {
        parent::__destruct();
    }

    private $base_url;

    public function __construct() {
        parent::__construct();
        $this->base_url = 'http://localhost:5000/api/ventas';
    }

    // Método para obtener todas las planificaciones
    public function obtenerVentas() {
        $url = $this->base_url;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    // Método para obtener una planificación por su ID
    public function obtenerVentasPorId($id) {
        $url = $this->base_url . "/" . $id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function obtenerVentasPorUser($users_id) {
        $url = $this->base_url . "/" . $users_id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }


      // Método para obtener la cantidad de un producto en el stock
      private function obtenerCantidadProducto($producto_id) {
        $stmt = $this->conn->prepare("SELECT PRO_Cantidad FROM tproducto WHERE id = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        return $producto['PRO_Cantidad'];
    }

    public function crearVentas($datos) {
       
            $cantidad_en_stock = $this->obtenerCantidadProducto($datos['producto_id']);
            if ($datos['VENT_cantidad'] > $cantidad_en_stock) {
                echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'No hay stock suficiente del producto seleccionado.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
                return false;
            }
    

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
                    text: 'Nueva venta registrada satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=ventas';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al registrar al Venta.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    public function actualizarVentas($id, $datos) {
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
                    text: 'Venta  actualizada satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=ventas';
                    }
                });
            </script>";
        } else {
            $error = error_get_last();
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar la Venta: " . $error['message'] . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    public function eliminarVentas($id) {
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
                    text: 'Venta eliminada satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=ventas';
                    }
                });
            </script>";
        } else {
            $error = error_get_last();
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar la Venta: " . $error['message'] . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }
}
?>



