<?php
require_once '../config.php';

class Register extends DBConnection {
    private $settings;
    
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;

        parent::__construct();
        ini_set('display_errors', 1);
    }
    
    public function __destruct(){
        parent::__destruct();
    }
    
    public function index(){
        echo "<h1>Acceso Denegado</h1> <a href='".base_url."'>Volver Atrás.</a>";
    }
    
    public function checkUserExists($cedula) {
        // Preparar la consulta SQL para verificar si el usuario ya está registrado
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE cedula = ?");
        $stmt->bind_param('s', $cedula);
        $stmt->execute();
        $result = $stmt->get_result();

        // Si el usuario ya existe, retornar verdadero, de lo contrario, retornar falso
        return $result->num_rows > 0;
    }
    
    public function registerUser($firstname, $lastname, $cedula, $password, $type, $status) {
        // Verificar que todos los campos necesarios estén presentes y no estén vacíos
        if (empty($cedula) || empty($firstname) || empty($lastname) || empty($password) || empty($type) || empty($status)) {
            // Si algún campo está vacío, retornar un mensaje de error y mostrar una alerta
            echo "<script>alert('Por favor, complete todos los campos.')</script>";
            return;
        }

        // Verificar si la cédula tiene exactamente 10 caracteres y contiene solo números
        if (strlen($cedula) !== 10 || !ctype_digit($cedula)) {
            // Si la cédula no cumple con los requisitos, mostrar una alerta y retornar un mensaje de error
            echo "<script>alert('La cédula debe tener exactamente 10 caracteres y contener solo números.')</script>";
            return;
        }

        // Verificar si la cédula contiene solo números
        if (!preg_match('/^[0-9]+$/', $cedula)) {
            // Si la cédula contiene caracteres que no son números, mostrar una alerta y retornar un mensaje de error
            echo "<script>alert('La cédula debe contener solo números.')</script>";
            return;
        }

        // Hashear la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Preparar la consulta SQL con los nombres de columna apropiados
        $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, cedula, password, type, status) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error);
        }

        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param('ssssii', $firstname, $lastname, $cedula, $hashed_password, $type, $status);

        if ($stmt->execute() === false) {
            die('Execute failed: ' . $stmt->error);
        }

        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            // Si el registro fue exitoso, mostrar una alerta
            echo "<script>alert('Usuario registrado satisfactoriamente.'); window.location.href = 'login.php';</script>";
        } else {
            // Si hubo un error en el registro, mostrar una alerta
            echo "<script>alert('Error al registrar usuario.')</script>";
        }

        $stmt->close();
    }
}
?>
