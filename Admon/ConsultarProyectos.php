<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Crear consulta
$consultaQ = "SELECT p.id_Proyecto, 
p.Nombre AS ProyectoNombre, 
p.Descripcion AS ProyectoDescripcion, 
p.Duracion AS ProyectoDuracion, 
a.Nombre AS AreaNombre,
a.Descripcion AS AreaDescripcion
FROM proyecto AS p Left JOIN area AS a ON p.id_Area=a.id_Area ;";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

$mysqli->close();
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
      <p class="lead">Usuario Administrador</p>
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
              <th>Duracion / Semanas</th>
              <th>Consultar Proyecto</th>
              <th>Asignar Alumnos</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $filas = 0;
            while ($getProyectos = $result->fetch_assoc()) {
              $filas++;
            ?>
              <tr>
                <td><?php echo $filas ?></td>
                <td><?php echo $getProyectos['ProyectoNombre'] ?></td>
                <td><?php echo $getProyectos['AreaNombre'] ?></td>
                <td><?php echo $getProyectos['ProyectoDescripcion'] ?></td>
                <td><?php echo $getProyectos['ProyectoDuracion'] ?></td>
                <td>
                  <a 
                  class="btn btn-primary" 
                  href="./ConProyecto.php?IdProyecto=<?php echo $getProyectos['id_Proyecto']; ?>" 
                  role="button">Consultar</a>
                </td>
                <td>
                  <a 
                  class="btn btn-success" 
                  href="./AsignarAP.php?IdProyecto=<?php echo $getProyectos['id_Proyecto']; ?>" 
                  role="button">Asignar</a>
                  
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <!-- Fin Contenido -->
      </div>
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