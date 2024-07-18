
<?php
require_once '../config.php';

class Clientes extends DBConnection {

    private $base_url;

    public function __construct() {
        parent::__construct();
        $this->base_url = 'http://localhost:5000/api/clientes';
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function obtenerClientes() {
        $url = $this->base_url;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
    
    public function obtenerClientePorId($id) {
        $url = $this->base_url . "/" . $id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function obtenerClientesPorUser($users_id) {
        $url = $this->base_url . "/" . $users_id; 
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function crearCliente($datos) {
        // Validación de datos
        $tipoIdentificacion = $this->validarIdentificacion($datos['CLI_Identificacion']);
        if (!$tipoIdentificacion || !$this->validarCorreo($datos['CLI_Correo'])) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Identificación o correo inválido.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
            return null;
        }

        // Asignar el tipo de identificación
        $datos['CLI_TipoIdentificacion'] = $tipoIdentificacion;

        // Verificar si ya existe un cliente con la misma cédula
        if ($this->existeClienteConCedula($datos['CLI_Identificacion'])) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Ya existe un cliente con esa identificación.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
            return null;
        }
    
        // Si no existe, procede a registrar el cliente
        $url = $this->base_url;
        $opciones = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => json_encode($datos)
            )
        );
        $context  = stream_context_create($opciones);
        $response = file_get_contents($url, false, $context);
    
        if ($response) {
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Cliente registrado satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=clientes';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al registrar el cliente.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    
        return json_decode($response, true);
    }
    
    private function existeClienteConCedula($cedula) {
        // Consultar si existe un cliente con la misma cédula en la base de datos
        $query = "SELECT COUNT(*) AS count FROM `tcliente` WHERE `CLI_Identificacion` = '$cedula'";
        $result = $this->conn->query($query);
        $count = $result->fetch_assoc()['count'];
    
        return $count > 0;
    }

    private function determinarTipoIdentificacion($identificacion) {
        // Implementación simple para determinar el tipo de identificación
        if (preg_match('/^\d{10}$/', $identificacion)) {
            return 'C'; // Cédula
        } elseif (preg_match('/^[A-Z0-9]{9}$/', $identificacion)) {
            return 'P'; // Pasaporte
        } elseif (preg_match('/^\d{13}$/', $identificacion)) {
            return 'R'; // RUC
        } else {
            return ''; // Tipo de identificación desconocido
        }
    }

    public function actualizarCliente($id, $datos) {

    
        $url = $this->base_url . "/" . $id;
        $opciones = array(
            'http' => array(
                'method'  => 'PUT',
                'header'  => 'Content-type: application/json',
                'content' => json_encode($datos)
            )
        );
        $context  = stream_context_create($opciones);
        $response = file_get_contents($url, false, $context);

        if ($response) {
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Cliente actualizado satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=clientes';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar el cliente.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    public function eliminarCliente($id) {
        $url = $this->base_url . "/" . $id;
        $opciones = array(
            'http' => array(
                'method'  => 'DELETE',
                'header'  => 'Content-type: application/json'
            )
        );
        $context  = stream_context_create($opciones);
        $response = file_get_contents($url, false, $context);

        if ($response) {
            echo "<script>
                Swal.fire({
                    title: 'Éxito',
                    text: 'Cliente eliminado satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=clientes';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar el cliente.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    private function validarIdentificacion($identificacion) {
        // Cédula ecuatoriana: 10 dígitos numéricos
        if (preg_match('/^\d{10}$/', $identificacion)) {
            return 'C';
        }

        // Pasaporte: 9 caracteres alfanuméricos
        if (preg_match('/^[A-Z0-9]{9}$/', $identificacion)) {
            return 'P';
        }

        // RUC: 13 dígitos numéricos
        if (preg_match('/^\d{13}$/', $identificacion)) {
            return 'R';
        }

        // Si no coincide con ninguno, retorna null
        return null;
    }

    private function validarCorreo($correo) {
        return filter_var($correo, FILTER_VALIDATE_EMAIL);
    }
}
?>
