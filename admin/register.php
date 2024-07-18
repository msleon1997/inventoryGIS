<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="es" class="" style="height: auto;">
<?php require_once('inc/header.php');
      require_once('../classes/Register.php');

      // Crear una instancia de la clase Register
$register = new Register();

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $cedula = $_POST['cedula'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $status = $_POST['status'];

    if ($register->checkUserExists($cedula)) {
      echo "<script>alert('El usuario ya está registrado.')</script>";

    }else {
      // Registrar al usuario
      $result = $register->registerUser($firstname, $lastname, $cedula, $password, $type, $status);

      // Manejar el resultado
      echo $result;
    }

}
?>

<body class="hold-transition login-page  dark-mode">
  <script>
    start_loader()
  </script>
  <style>
    body {
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size: cover;
      background-repeat: no-repeat;
    }

    .login-title {
      text-shadow: 2px 2px black;
      font-size: xxx-large;
    }

    .row {
    display: -ms-flexbox;
    display: flex;
    justify-content: center;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
   
    }
  </style>
  <h1 class="text-center py-5 login-title"><b><?php echo $_settings->info('name') ?></b></h1>
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-warning">
      <div class="card-header text-center">
        <a href="./" class="h1"><b>Registrarse</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Ingresa tus credenciales</p>

        <form id="register-frm" action="" method="post">
              <div class="input-group mb-3">
                <input type="text" class="form-control" autofocus name="firstname" placeholder="Ingrese su nombre" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input type="text" class="form-control" autofocus name="lastname" placeholder="Ingrese su apellido" required>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                  <input type="text" class="form-control" autofocus name="cedula" placeholder="Ingrese su Cédula" maxlength="10" required>
                  <div class="input-group-append">
                      <div class="input-group-text">
                          <span class="fas fa-address-card"></span>
                      </div>
                  </div>
              </div>
              <div class="input-group mb-3">
                <input type="password" class="form-control" name="password" placeholder="Contraseña" >
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>

              <div class="input-group mb-3">
                <input type="hidden" class="form-control" name="status" value="1">
                <input type="hidden" class="form-control" name="type" value="1">
                
              </div>


              <div class="row">
                <!-- /.col -->
                <div class="col-5">
                  <button type="submit" class="btn btn-warning btn-block ">Registrarse</button>
                </div>
                <div class="col-5">
                    <a href="./login.php" class="btn btn-warning btn-block ">Atrás</a>
                </div>
              </div>
            </form>
      </div>
    </div>
  </div>
  

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>

  <script>
    $(document).ready(function() {
      end_loader();
    })
  </script>
</body>

</html>