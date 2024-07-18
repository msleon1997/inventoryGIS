
<?php
require_once '../config.php';
class Existencias extends DBConnection{

	private $base_url;

    public function __construct() {
        parent::__construct();
        $this->base_url = 'http://localhost:5000/api/existencias';
    }

	public function __destruct(){
		parent::__destruct();
	}

  
     // Método para obtener todas las planificaciones
     public function obtenerExistencias()
     {
         $url = $this->base_url;
         $response = file_get_contents($url);
         return json_decode($response, true);
     }
    
     // Método para obtener una planificación por su ID
    public function obtenerExistenciasPorId($id)
    {
        $url = $this->base_url . "/" . $id;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function obtenerExistenciasPorUser($users_id)
    {
    $url = $this->base_url . "/" . $users_id;
    $response = file_get_contents($url);
    return json_decode($response, true);
    }

    
}

?>