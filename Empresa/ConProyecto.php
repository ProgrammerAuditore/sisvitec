<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Proyecto";
$goTo = "Location:/Empresa/ConsultarProyecto.php";
$againTo = "<br/><hr><a href=/Empresa/AgrProyecto.php>Volver a intentarlo.</a>";

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['IdProyecto']) || empty($_GET['IdProyecto'])) {
  // En caso de recibir campos incorrectos
  $goTo .= "?action=error&msg=Usuario no valido&msg=Este usuario no existe.";
  $mysqli->close();
  header($goTo);
  exit();
}

// Obtener el IdProyecto
$IdProyecto = $_GET['IdProyecto'];

// Crear consulta
$consultaQ = "SELECT 
p.id_Proyecto AS ProyectoId,
p.Nombre AS ProyectoNombre,
p.Descripcion AS ProyectoDescripcion,
p.Objetivo_General AS ProyectoObjGeneral,
p.Objetivo_Espesifico AS ProyectoObjEspecifico,
p.Duracion AS ProyectoDuracion,
p.Tipo_Proyect AS ProyectoTipo,
a.Nombre AS ProyectoArea,
e.Nombre AS ProyectoEmpresa
FROM `proyecto` AS p  
LEFT JOIN `empresa` AS e ON p.id_Empresa = e.id_empresa
LEFT JOIN `area` AS a ON p.id_Area = a.id_Area   
WHERE p.id_Proyecto = $IdProyecto;";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);
//print var_dump($result->fetch_assoc());

// Obtener los registros del usuario
$getProyecto = $result->fetch_assoc();

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

<style>
    textarea {
        resize: none;
        margin: 0.5em 0px;
    }

    section.cuerpo {
        margin: 0px 0px 2em 0px;
    }
</style>

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
    <p class="lead">Solicitud Proyecto</p>
    <hr>
  </section>
  <section class="cuerpo">
    <div class="container">

      <!-- Información del proyecto -->
      <span style="font-weight:bold;color:#000080;">Información del proyecto&nbsp;</span>
      <hr>

      <label class="col-lg-3 control-label">Nombre Del Proyecto: </label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getProyecto['ProyectoNombre']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Tipo de Proyecto: </label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getProyecto['ProyectoTipo']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Area De Desarrollo: </label>
      <div class="col-lg-9">
        <div class="selector-pais">
          <p disabled class="form-control"><?php echo $getProyecto['ProyectoArea']; ?></p>
        </div>
      </div>

      <label for="turno" class="col-lg-3 control-label">Duracion en Semanas: </label>
      <div class="col-lg-9">
        <p disabled class="form-control"><?php echo $getProyecto['ProyectoDuracion']; ?></p>
      </div>

      <label class="col-lg-3 control-label">Descripcion Del Proyecto: </label>
      <div class="col-lg-9">
        <textarea disabled class="form-control" id="proyecto-descripcion" name="proyecto-descripcion"><?php echo $getProyecto['ProyectoDescripcion']; ?></textarea>
      </div>

      <!-- Objetivos -->
      <span style="font-weight:bold;color:#000080;">Objetivos&nbsp;</span>
      <hr>
      <label class="col-lg-3 control-label">Objetivo General Del Proyecto: </label>
      <div class="col-lg-9">
        <textarea disabled class="form-control" id="proyecto-obj-general" name="proyecto-obj-general"><?php echo $getProyecto['ProyectoObjGeneral']; ?></textarea>
      </div>

      <label class="col-lg-3 control-label">Objetivos Especificos Del Proyecto: </label>
      <div class="col-lg-9">
        <textarea disabled class="form-control" id="proyecto-obj-general" name="proyecto-obj-general"><?php echo $getProyecto['ProyectoObjEspecifico']; ?></textarea>
      </div>


      <hr>
      <br><br>
      <a class="btn btn-primary" href="/Empresa/ConsultarProyecto.php" role="button">Regresar</a>
      <a class="btn btn-warning" href="/Empresa/EdiProyecto.php?IdProyecto=<?php echo $getProyecto['ProyectoId']; ?>" role="button">Editar</a>

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