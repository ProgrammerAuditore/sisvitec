<?php
session_start();
include '../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$IDE = $_SESSION['idE'];
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
      <form class="form-datos" action="<?php echo "/Empresa/FncDatabase/ProyectoAgregar.php?id=$IDE" ?>" method="POST" role="form">

        <!-- Información del proyecto -->
        <span style="font-weight:bold;color:#000080;">Información del proyecto&nbsp;</span>
        <hr>

        <label for="proyecto-nombre" class="col-lg-3 control-label">Nombre Del Proyecto: </label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="proyecto-nombre" name="proyecto-nombre"><br>
        </div>

        <label for="proyecto-tipo" class="col-lg-3 control-label">Tipo de Proyecto: </label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="proyecto-tipo" name="proyecto-tipo"><br>
        </div>

        <label for="proyecto-area" class="col-lg-3 control-label">Area De Desarrollo: </label>
        <div class="col-lg-9">
          <div id="seleccionar-proyecto-area">
            <select name="proyecto-area" class="form-control">
              <script type="text/javascript">
                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: "AreasD.php",
                    success: function(response) {
                      $('#seleccionar-proyecto-area select').html(response).fadeIn();
                    }
                  });

                });
              </script>
            </select><br>
          </div>
        </div>

        <label for="proyecto-duracion" class="col-lg-3 control-label">Duración en Semanas: </label>
        <div class="col-lg-9">
          <select name="proyecto-duracion" class="form-control">
            <!-- Crear 25 opciones -->
            <Option select value="">Seleccionar...</Option>
            <?php
            for ($i = 1; $i <= 25; $i++) {
            ?>
              <Option value="<?php echo $i; ?>"><?php echo $i; ?></Option>
            <?php } ?>
          </select><br>
        </div>

        <label for="proyecto-descripcion" class="col-lg-3 control-label">Descripción Del Proyecto: </label>
        <div class="col-lg-9">
          <textarea class="form-control" id="proyecto-descripcion" name="proyecto-descripcion"></textarea>
        </div>

        <!-- Objetivos -->
        <span style="font-weight:bold;color:#000080;">Objetivos&nbsp;</span>
        <hr>

        <label for="proyecto-obj-general" class="col-lg-3 control-label">Objetivo General Del Proyecto: </label>
        <div class="col-lg-9">
          <textarea class="form-control" id="proyecto-obj-general" name="proyecto-obj-general"></textarea>
        </div>

        <label for="proyecto-obj-especifico" class="col-lg-3 control-label">Objetivos Especificos Del Proyecto: </label>
        <div class="col-lg-9">
          <textarea class="form-control" id="proyecto-obj-especifico" name="proyecto-obj-especifico"></textarea>
        </div>

        <hr>
        <br><br>
        <input class="btn btn-success" type="submit" name="postAgregarProyecto" value="Registrar">
        <a class="btn btn-primary" href="/Empresa/ConsultarProyecto.php" role="button">Consultar / Eliminar</a>
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