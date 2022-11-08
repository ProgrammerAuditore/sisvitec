<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include '../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$goTo = "Location:/Admon/ConsultarAlumnos.php";
$againTo = "<br/><hr><a href=/Admon/EdiAlumno.php?IdUsuario={$_GET['IdUsuario']}>Volver a intentarlo.</a>";


//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['IdUsuario']) || empty($_GET['IdUsuario'])) {
  // En caso de recibir campos incorrectos
  $goTo .= "?action=error";
  $goTo .= "&title=Alumno no actualizado.";
  $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
  $goTo .= $againTo;
  $mysqli->close();
  header($goTo);
  exit();
}

//****  Obtener todo los datos del usuario */
// Obtener el IdUsuario
$IdUsuario = $_GET['IdUsuario'];

// Crear consulta
$consultaQ = "SELECT 
lng.User AS CuentaUser,
lng.Password AS CuentaPassword, 
alu.id_Login AS AlumnoLoginId,
alu.Nombre AS AlumnoNombre,
alu.Num_Control AS AlumnoNumeroControl,
alu.Correo AS AlumnoCorreo,
alu.Direccion AS AlumnoDireccion,
car.id_Carrera AS AlumnoCarreraId, 
are.id_Area AS AlumnoAreaId
FROM `alumnos` AS alu   
LEFT JOIN `carrera` AS car ON alu.id_Carrera=car.id_carrera  
LEFT JOIN `login` AS lng ON lng.id_Login = alu.id_Login    
LEFT JOIN `area` AS are ON alu.id_Area = are.id_Area   
WHERE alu.id_Login = $IdUsuario ; ";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

//****  Verificar si existe registro del usuario */
if ($result->num_rows <= 0) {
  // En caso de recibir campos incorrectos
  $goTo .= "?action=error";
  $goTo .= "&title=Alumno no actualizado.";
  $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
  $goTo .= $againTo;
  $mysqli->close();
  header($goTo);
  exit();
}

//print var_dump($result->fetch_assoc());

// Obtener los registros del usuario
$getUsuario = $result->fetch_assoc();

$mysqli->close();

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

      <br>
      <span style="font-weight:bold;color:#000080;">Información de registro&nbsp;</span>
      <hr>

      <label for="nombre" class="col-lg-3 control-label">Usuario:</label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getUsuario['CuentaUser']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Contraseña:</label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getUsuario['CuentaPassword']; ?></p>
      </div>

      <span style="font-weight:bold;color:#000080;">Información de alumno&nbsp;</span>
      <hr>

      <label class="col-lg-3 control-label">Nombre Alumno</label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getUsuario['AlumnoNombre']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Numero De Control</label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getUsuario['AlumnoNumeroControl']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Correo Electrónico </label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getUsuario['AlumnoCorreo']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Direccion</label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getUsuario['AlumnoDireccion']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Area De Desarrollo :</label>
      <div class="col-lg-9">
        <div class="selector-pai">
          <select name="Area" class="form-control" disabled>
            <script type="text/javascript">
              $(document).ready(function() {
                $.ajax({
                  type: "POST",
                  url: "AreasD.php?idArea=<?php echo $getUsuario['AlumnoAreaId']; ?>",
                  success: function(response) {
                    $('.selector-pai select').html(response).fadeIn();
                  }
                });
              });
            </script>
          </select><br>
        </div>
      </div>
      <label class="col-lg-3 control-label">Carrera :</label>
      <div class="col-lg-9">
        <div class="selector-pas">
          <select name="Carrera" class="form-control" disabled>
            <script type="text/javascript">
              $(document).ready(function() {
                $.ajax({
                  type: "POST",
                  url: "CarreraA.php?idCarrera=<?php echo $getUsuario['AlumnoCarreraId']; ?>",
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
      <a class="btn btn-primary" href="/Admon/ConsultarAlumnos.php" role="button">Regresar</a>
      <a class="btn btn-warning" href="/Admon/EdiAlumno.php?IdUsuario=<?php echo $getUsuario['AlumnoLoginId']; ?>" role="button">Editar</a>
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