<?php
require_once '../config.php';

class Proveedores extends DBConnection {

    private $base_url;

    public function __construct() {
        parent::__construct();
        $this->base_url = 'http://localhost:5000/api/proveedores';
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function obtenerProveedores() {
        $url = $this->base_url;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }    
    
    public function obtenerProveedorPorId($id) {
        $url = $this->base_url . "/" . $id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function obtenerProveedoresPorUser($users_id) {
        $url = $this->base_url . "/" . $users_id; 
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function crearProveedor($datos) {
        // Validación de datos
        $tipoIdentificacion = $this->validarIdentificacion($datos['PROV_Identificacion']);
        if (!$tipoIdentificacion || !$this->validarCorreo($datos['PROV_email'])) {
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

        // Verificar si ya existe un Proveedor con la misma cédula
        if ($this->existeProveedorConCedula($datos['PROV_Identificacion'])) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Ya existe un Proveedor con esa identificación.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
            return null;
        }
    
        // Si no existe, procede a registrar el Proveedor
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
                    text: 'Proveedor registrado satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=Proveedores';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al registrar el Proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    
        return json_decode($response, true);
    }
    
    private function existeProveedorConCedula($cedula, $id = null) {
        // Consultar si existe un Proveedor con la misma cédula en la base de datos, excluyendo el proveedor actual si se está editando
        $query = "SELECT COUNT(*) AS count FROM `tproveedores` WHERE `PROV_Identificacion` = '$cedula'";
        if ($id) {
            $query .= " AND `id` != '$id'";
        }
        $result = $this->conn->query($query);
        $count = $result->fetch_assoc()['count'];
    
        return $count > 0;
    }

    public function actualizarProveedor($id, $datos) {
        $tipoIdentificacion = $this->validarIdentificacion($datos['PROV_Identificacion']);
        if (!$tipoIdentificacion || !$this->validarCorreo($datos['PROV_email'])) {
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

        // Verificar si ya existe un Proveedor con la misma cédula, excluyendo el proveedor actual
        if ($this->existeProveedorConCedula($datos['PROV_Identificacion'], $id)) {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Ya existe un Proveedor con esa identificación.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
            return null;
        }
    
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
                    text: 'Proveedor actualizado satisfactoriamente.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = './?page=Proveedores';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al actualizar el Proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }

        return json_decode($response, true);
    }

    public function eliminarProveedor($id) {
        $url = $this->base_url . "/" . $id;
        $opciones = array(
            'http' => array(
                'method'  => 'DELETE',
                'header'  => 'Content-type: application/json'
            )
        );
        $context  = stream_context_create($opciones);
        $response = @file_get_contents($url, false, $context);
    
        // Verificar si se pudo eliminar el proveedor correctamente
        if ($response) {
            $responseData = json_decode($response, true);
    
            // Verificar si hubo un error específico de restricción de clave externa
            if (isset($responseData['exito']) && $responseData['exito'] === false && strpos($responseData['mensaje'], "Cannot delete or update a parent row") !== false) {
                echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'No se puede eliminar el proveedor porque está siendo utilizado.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = './?page=Proveedores';
                        }
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Proveedor eliminado satisfactoriamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = './?page=Proveedores';
                        }
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Error al eliminar el Proveedor.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
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
