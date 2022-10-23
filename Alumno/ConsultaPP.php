<?php
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$mysql = new conexion();
$con = $mysql->_ObtenerConexion();
$ID = $_SESSION['id'];
$sql = "SELECT id_Area FROM `alumnos` where id_Alumnos=" . $ID;
$idusu = mysqli_query($con, $sql);
$row = mysqli_fetch_row($idusu);
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SISVINTEC - Proyectos</title>
  <!-- Bootstrap core CSS -->
  <link href="../Estilos/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="../Estilos/EstilosAgregar.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
  <?php
  include 'Menu.php';
  ?>
  <section class="jumbotron">
    <div class="container">
      <h1>Listado De Proyectos</h1>
      <p class="lead">SISVINTEC</p><br>
      <p class="lead">Usuario Alumno</p>
    </div>
    <div class="col-12 col-md-12">

    </div>
    <div class="row">
      <div class="col-12 col-md-12">
        <hr>
        <hr>
        <table class="table table-hover">
          <thead>
            <tr align='center' class='table table-hover'>
              <th>#</th>
              <th>Nombre Proyecto</th>
              <th>Area</th>
              <th>Descripcion</th>
              <th>Empresa Encargada</th>
            </tr>
          </thead>
          <?php

          if (!$con) {
            die('error de conexion de servidor:' . mysql_error());
          }
          $consulta = "SELECT p.Nombre,a.Nombre as area,p.Descripcion,e.Nombre as empresa From alu_proyect as ap 
LEFT JOIN proyecto as p on ap.id_Proyecto=p.id_Proyecto 
LEFT JOIN empresa as e ON p.id_Empresa = e.id_empresa
LEFT JOIN area as a ON p.id_Area=a.id_Area
WHERE ap.id_Alumno=" . $ID . " ;";
          $resultado = mysqli_query($con, $consulta);
          $contador = 0;
          while ($misdatos = mysqli_fetch_assoc($resultado)) {
            $contador++;
          ?>
            <tr>
              <td><?php echo $contador ?></td>
              <td><?php echo $misdatos["Nombre"]; ?></td>
              <td><?php echo $misdatos["area"]; ?></td>
              <td><?php echo $misdatos["Descripcion"]; ?></td>
              <td><?php echo $misdatos["empresa"]; ?></td>

            </tr>

          <?php } ?>

          </tbody>
        </table>
        <!-- Fin Contenido -->
      </div>
    </div>
  </section>
  <section class="cuerpo">
    <div class="container">
      <center>
      </center>
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