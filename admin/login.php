<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="es" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>

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
    .btn-block {
    display: block;
    width: 100px;
  }
  </style>
  <h1 class="text-center py-5 login-title"><b><?php echo $_settings->info('name') ?></b></h1>
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-warning">
      <div class="card-header text-center">
        <a href="./" class="h1"><b>Acceder</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Ingresa tus credenciales</p>

        <form id="login-frm" action="" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" autofocus name="cedula" placeholder="Numero Cedula">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Contraseña">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            
            <div class="col-4">
              <button type="submit" class="btn btn-warning btn-block">Acceder</button>
            </div>
            <div class="col-4">
                <a href="./register.php" class="btn btn-warning btn-block">Registrar</a>
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