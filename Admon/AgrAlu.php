<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="windows-1251">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Bienvenido a SisVinTec</title>
  <!-- Bootstrap core CSS -->
  <link href="../Estilos/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="../Estilos/EstilosAgregar.css" rel="stylesheet">

  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>
</head>

<body>
  <?php
  include 'Menu.php';
  ?>
  <section class="jumbotron" style="text-align: center;">
    <div class="container">
      <h1>SisVinTec</h1>
      <p class="lead">Sistema para Vinculacion del TECMM</p><br>
    </div>
    <hr>
    <p class="lead">Solicitud Alumno</p>
    <hr>
  </section>
  <section class="cuerpo">
    <!-- Poner los datos del usuario -->
    <div class="container">
      <form class="form-datos" action="./FncDatabase/AlumnoAgregar.php" method="POST" role="form">
        <span style="font-weight:bold;color:#000080;">Información de registro&nbsp;</span>
        <hr>

        <label for="cuenta-usuario" class="col-lg-3 control-label">Usuario:</label>
        <div class="col-lg-9">
          <input type="text" value="" placeholder="Usuario" name="cuenta-usuario" class="form-control" id="cuenta-usuario"><br>
        </div>

        <label for="cuenta-password" class="col-lg-3 control-label">Contraseña:</label>
        <div class="col-lg-9">
          <input value="" type="password" placeholder="Contraseña" name="cuenta-password" id="cuenta-password" class="form-control" data-toggle="password"><br>
        </div>

        <label for="alumno-nombre" class="col-lg-3 control-label">Nombre De Alumno:</label>
        <div class="col-lg-9">
          <input value="" type="text" class="form-control" id="alumno-nombre" name="alumno-nombre" placeholder="Nombre alumno"><br>
        </div>

        <label for="alumno-numero-control" class="col-lg-3 control-label">Número De Control:</label>
        <div class="col-lg-9">
          <input value="" type="text" class="form-control" id="alumno-numero-control" name="alumno-numero-control" placeholder="Número de control"><br>
        </div>

        <label for="alumno-correo" class="col-lg-3 control-label">Correo Electrónico:</label>
        <div class="col-lg-9">
          <input value="" type="text" class="form-control" id="alumno-correo" name="alumno-correo" placeholder="Correo electronico"><br>
        </div>

        <label for="alumno-direccion" class="col-lg-3 control-label">Dirección:</label>
        <div class="col-lg-9">
          <input value="" type="text" class="form-control" id="alumno-direccion" name="alumno-direccion" placeholder="Dirección"><br>
        </div>

        <label for="alumno-area-id" class="col-lg-3 control-label">Area De Desarrollo:</label>
        <div class="col-lg-9">
          <div class="selector-pai">
            <select name="alumno-area-id" id="alumno-area-id" class="form-control">
              <script type="text/javascript">
                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: "AreasD.php",
                    success: function(response) {
                      $('.selector-pai select').html(response).fadeIn();
                    }
                  });
                });
              </script>
            </select><br>
          </div>
        </div>

        <label for="alumno-carrera-id" class="col-lg-3 control-label">Carrera:</label>
        <div class="col-lg-9">
          <div class="selector-pas">
            <select name="alumno-carrera-id" id="alumno-carrera-id" class="form-control">
              <script type="text/javascript">
                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: "CarreraA.php",
                    success: function(response) {
                      console.log("Valor");
                      $('.selector-pas select').html(response).fadeIn();
                    }
                  });

                });
              </script>
            </select><br>
          </div>
        </div>

        <hr>
        <br><br>
        <input class="btn btn-success" type="submit" name="postCrearAlumno" value="Registrar">
        <a class="btn btn-primary" href="/Admon/ConsultarAlumnos.php" role="button">Consultar / Eliminar</a>
      </form>
    </div>

  </section>
  <footer>
    <div class="contenedor">
      <p>Copyright &copy; BCB</p>
    </div>
  </footer>
  <!-- Bootstrap core JavaScript -->
  <script src="../Estilos/dist/js/jquery.js"></script>
  <script src="../Estilos/dist/js/bootstrap.min.js"></script>
</body>

</html>