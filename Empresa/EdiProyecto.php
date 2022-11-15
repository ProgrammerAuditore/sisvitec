<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include '../conexion.php';
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
a.id_Area AS ProyectoAreaId,
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
    overflow: hidden;
    height: auto;
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
      <form action=<?php echo "/Empresa/FncDatabase/ProyectoActualizar.php?id=$IdProyecto"; ?> class="form-datos" method="POST" role="form">

        <!-- Información del proyecto -->
        <span style="font-weight:bold;color:#000080;">Información del proyecto&nbsp;</span>
        <hr>

        <label class="col-lg-3 control-label">Nombre Del Proyecto: </label>
        <div class="col-lg-9">
          <input value="<?php echo $getProyecto['ProyectoNombre']; ?>" type="text" class="form-control" id="name" name="proyecto-nombre" required><br>
        </div>

        <label class="col-lg-3 control-label">Tipo de Proyecto: </label>
        <div class="col-lg-9">
          <input value="<?php echo $getProyecto['ProyectoTipo']; ?>" type="text" class="form-control" id="TP" name="proyecto-tipo" required><br>
        </div>

        <label class="col-lg-3 control-label">Area De Desarrollo: </label>
        <div class="col-lg-9">
          <div id="seleccionar-proyecto-area">
            <select name="proyecto-area" class="form-control" required>
              <script type="text/javascript">
                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: "AreasD.php?idArea=<?php echo $getProyecto['ProyectoAreaId']; ?>",
                    success: function(response) {
                      $('#seleccionar-proyecto-area select').html(response).fadeIn();
                    }
                  });

                });
              </script>
            </select><br>
          </div>
        </div>

        <label for="turno" class="col-lg-3 control-label">Duración en Semanas: </label>
        <div class="col-lg-9">
          <select name="proyecto-duracion" class="form-control" required>
            <!-- Crear 25 opciones -->
            <Option select value="">Seleccione</Option>
            <?php
            for ($i = 1; $i <= 25; $i++) {
              if ($getProyecto['ProyectoDuracion'] == $i) {
                echo "<Option selected value='$i'>$i</Option>";
              } else {
            ?>
                <Option value="<?php echo $i; ?>"><?php echo $i; ?></Option>
            <?php }
            } ?>
          </select><br>
        </div>

        <label class="col-lg-3 control-label">Descripción Del Proyecto: </label>
        <div class="col-lg-9">
          <textarea class="form-control" id="proyecto-descripcion" name="proyecto-descripcion" required><?php echo $getProyecto['ProyectoDescripcion']; ?></textarea>
        </div>

        <!-- Objetivos -->
        <span style="font-weight:bold;color:#000080;">Objetivos&nbsp;</span>
        <hr>
        <label class="col-lg-3 control-label">Objetivo General Del Proyecto: </label>
        <div class="col-lg-9">
          <textarea class="form-control" id="proyecto-obj-general" name="proyecto-obj-general" required><?php echo $getProyecto['ProyectoObjGeneral']; ?></textarea>
        </div>

        <label class="col-lg-3 control-label">Objetivos Especificos Del Proyecto: </label>
        <div class="col-lg-9">
          <textarea class="form-control" id="proyecto-obj-especifico" name="proyecto-obj-especifico" required><?php echo $getProyecto['ProyectoObjEspecifico']; ?></textarea>
        </div>

        <hr>
        <br><br>
        <a class="btn btn-primary" href="/Empresa/ConsultarProyecto.php" role="button">Regresar</a>
        <input class="btn btn-warning" type="submit" name="postActualizarProyecto" value="Actualizar">
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
  <script>
    $(document).ready(function() {
      
      $('#proyecto-descripcion, #proyecto-obj-general ,#proyecto-obj-especifico').on('input', function() {
        this.style.height = 'auto';

        this.style.height =
          (this.scrollHeight) + 'px';
      });

    });
  </script>
</body>

</html>