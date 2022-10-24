<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['IdUsuario']) || empty($_GET['IdUsuario'])) {
  header("Location: ./ConsultaAlumno.php");
}

//****  Obtener todo los datos del usuario */
// Obtener el IdUsuario
$IdUsuario = $_GET['IdUsuario'];


// Crear consulta
$consultaQ = "SELECT lng.*, alu.*, car.Nombre AS Carrera, are.Nombre AS Area  
    FROM `alumnos` AS alu   
    LEFT JOIN `carrera` AS car ON alu.id_Carrera=car.id_carrera  
    LEFT JOIN `login` AS lng ON lng.id_Login = alu.id_Login    
    LEFT JOIN `area` AS are ON alu.id_Area = are.id_Area   
    WHERE alu.id_Login = $IdUsuario;";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

//****  Verificar si existe registro del usuario */
if ($result->num_rows <= 0) {
  header("Location: ./ConsultaAlumno.php");
}

//print var_dump($result->fetch_assoc());

// Obtener los registros del usuario
$getUsuario = $result->fetch_assoc();

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
      <form class="form-datos" action="<?php echo "./FncDatabase/ActualizarAlumno.php?id=$IdUsuario";?>" method="POST" role="form">
        <span style="font-weight:bold;color:#000080;">Informacion de registro&nbsp;</span>
        <hr>

        <label for="nombre" class="col-lg-3 control-label">Usuario:</label>
        <div class="col-lg-9">
          <input type="text" value=<?php echo $getUsuario['User'];   ?> placeholder="Usuario" name="user" class="form-control" id="nombre"><br>
        </div>

        <label class="col-lg-3 control-label">Contrasena:</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Password'];   ?> type="password" placeholder="Contraseña" name="pass" class="form-control" data-toggle="password"><br>
        </div>
        <label class="col-lg-3 control-label">Nombre Alumno</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Nombre'];   ?> type="text" class="form-control" id="name" name="NombreA"><br>
        </div>

        <label class="col-lg-3 control-label">Numero De Control</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Num_Control'];   ?> type="text" class="form-control" id="name" name="NumeroC"><br>
        </div>

        <label class="col-lg-3 control-label">Correo Electronico</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Correo'];   ?> type="text" class="form-control" id="name" name="Correo"><br>
        </div>

        <label class="col-lg-3 control-label">Direccion</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Direccion'];   ?> type="text" class="form-control" id="name" name="Direccion"><br>
        </div>


        <label class="col-lg-3 control-label">Area De Desarrollo :</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Area'];   ?> type="text" class="form-control" id="name" name="Area"><br>
        </div>
        <label class="col-lg-3 control-label">Carrera :</label>
        <div class="col-lg-9">
          <input value=<?php echo $getUsuario['Carrera'];   ?> type="text" class="form-control" id="name" name="Carrera"><br>
        </div>

        <hr>
        <br><br>
        <input class="btn btn-primary" type="submit" name="postActualizarAlumno" value="Actualizar">
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