<?php
$dev_data = array('id' => '1', 'firstname' => 'Marlon', 'lastname' => 'Leon', 'username' => 'mslb', 'password' => '0192023a7bbd73250516f069df18b500', 'last_login' => '', 'date_updated' => '', 'date_added' => '');
if (!defined('base_url')) define('base_url', 'http://localhost/inventoryGIS/');
if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
if (!defined('dev_data')) define('dev_data', $dev_data);
if (!defined('DB_SERVER')) define('DB_SERVER', "localhost"); // Ajustar la URL a solo el dominio o IP
if (!defined('DB_USERNAME')) define('DB_USERNAME', "root");
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', "");
if (!defined('DB_NAME')) define('DB_NAME', "inventory");


/* try {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME;
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    );
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    echo "Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
} */
?>