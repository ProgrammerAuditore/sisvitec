<?php
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$IDE = $_SESSION['idE'];
print_r("Este es la variable: $IDE :" . $IDE)


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
    <p class="lead">Solicitud Proyecto</p>
    <hr>
  </section>
  <section class="cuerpo">
    <div class="container">
      <form class="form-datos" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" role="form">
        <span style="font-weight:bold;color:#000080;">Informacion de Registro De Proyecto&nbsp;</span>
        <hr>

        <label class="col-lg-3 control-label">Nombre Del Proyecto</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="name" name="NombreP"><br>
        </div>
        <label class="col-lg-3 control-label">Area De Desarrollo :</label>
        <div class="col-lg-9">
          <div class="selector-pais">
            <select name="Area" class="form-control">
              <script type="text/javascript">
                $(document).ready(function() {
                  $.ajax({
                    type: "POST",
                    url: "AreasD.php",
                    success: function(response) {
                      $('.selector-pais select').html(response).fadeIn();
                    }
                  });

                });
              </script>
            </select><br>
          </div>
        </div>
        <label class="col-lg-3 control-label">Descripcion Del Proyecto</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="Descr" name="Descrip"><br>
        </div>

        <label class="col-lg-3 control-label">Objetivo General Del Proyecto</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="OGDP" name="OGDP"><br>
        </div>
        <label class="col-lg-3 control-label">Objetivos Especificos Del Proyecto :</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="OGEP" name="OEDP"><br>
        </div>
        <label for="turno" class="col-lg-3 control-label">Duracion en Semanas:</label>
        <div class="col-lg-9">
          <select name="Duracion" class="form-control">
            <Option select value="">Seleccione</Option>
            <Option value="1">1</Option>
            <Option value="2">2</Option>
            <Option value="3">3</Option>
            <Option value="4">4</Option>
            <Option value="5">5</Option>
            <Option value="6">6</Option>
            <Option value="7">7</Option>
            <Option value="8">8</Option>
            <Option value="9">9</Option>
            <Option value="10">10</Option>
            <Option value="11">11</Option>
            <Option value="12">12</Option>
            <Option value="13">13</Option>
            <Option value="14">14</Option>
            <Option value="15">15</Option>
            <Option value="16">16</Option>
            <Option value="17">17</Option>
            <Option value="18">18</Option>
            <Option value="19">19</Option>
            <Option value="20">20</Option>
            <Option value="21">21</Option>
            <Option value="22">22</Option>
            <Option value="23">23</Option>
            <Option value="24">24</Option>
            <Option value="25">25</Option>
          </select><br>
        </div>
        <label class="col-lg-3 control-label">Tipo de Proyecto</label>
        <div class="col-lg-9">
          <input type="text" class="form-control" id="TP" name="TP"><br>
        </div>
        <hr>
        <br><br>
        <input class="btn btn-primary" type="submit" name="enviar" value="Guardar">
      </form>
    </div>
  </section>
  <?php

  if (isset($_POST["enviar"])) {
    $mysql = new conexion();
    $con = $mysql->_ObtenerConexion();
    if (!$con) {
      die("Connection failed: " . $con->connect_error);
    }
    $NombreP   = $_POST['NombreP'];
    $Area   = $_POST['Area'];
    $Descripcion = $_POST['Descrip'];
    $OGDP = $_POST['OGDP'];
    $OEDP = $_POST['OEDP'];
    $Duracion = $_POST['Duracion'];
    $TP = $_POST['TP'];

    mysqli_query($con, "START TRANSACTION;");

    $result = mysqli_query($con, "select * from proyecto where Nombre = '" . $NombreP . "' and id_Area = " . $Area . " and Descripcion='" . $Descripcion . "' and id_Empresa='" . $IDE . "'");

    if (mysqli_num_rows($result) > 0) {
      echo 'Proyecto YA Existente';
      mysqli_query($con, "RollBack;");
    } else {
      $sql = "INSERT INTO `proyecto`(`Nombre`, `id_Area`, `Descripcion`, `id_Empresa`, `Objetivo_General`, `Objetivo_Espesifico`, `Duracion`, `Tipo_Proyect`) VALUES ('" . $NombreP . "'," . $Area . ",'" . $Descripcion . "'," . $IDE . ",'" . $OGDP . "','" . $OEDP . "'," . $Duracion . ",'" . $TP . "')";

      if (mysqli_query($con, $sql)) {
        mysqli_query($con, "COMMIT;");
  ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>
        <script>
          $.jGrowl("EL REGISTRO SE GUARDO CON EXITO!", {
            life: 3000,
            position: 'bottom-right',
            theme: 'test'
          });
        </script>
        <style>
          .test {
            background-color: #31B404;
            width: 300px;
            height: 80px;
            text-align: center;
          }
        </style>
  <?php


      } else {
        mysqli_query($con, "RollBack;");
        echo 'error al agregar El Proyecto';
      }
    }
    $con->close();
  }

  ?>
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