<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Crear consulta
$consultaQ = "SELECT
alu.id_Login AS AlumnoLoginId,
alu.id_Alumnos AS AlumnoId,
alu.Nombre AS AlumnoNombre,
alu.Num_Control AS AlumnoNumControl,
alu.Correo AS AlumnoCorreo,
car.Nombre AS AlumnoCarrera, 
are.Nombre AS AlumnoArea 
FROM alumnos AS alu 
LEFT JOIN carrera AS car ON alu.id_Carrera=car.id_carrera
LEFT JOIN area AS are ON alu.id_Area = are.id_Area; ";

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
  <title>SISVINTEC - EMPRESAS</title>
  <!-- Bootstrap core CSS -->
  <link href="../Estilos/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="../Estilos/EstilosAgregar.css" rel="stylesheet">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <!-- sweetalert2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <?php
  include 'Menu.php';
  ?>
  <section class="jumbotron">
    <div class="container">
      <h1>Listado De Alumnos
      </h1>
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
              <th>Nombre</th>
              <th>Numero De Control</th>
              <th>Correo Electronico</th>
              <th>Area</th>
              <th>Consultar </th>
              <th>Editar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $filas = 0;
            while ($getAlumnos = $result->fetch_assoc()) {
              $filas++;
            ?>
              <tr>
                <td><?php echo $filas ?></td>
                <td><?php echo $getAlumnos['AlumnoNombre'] ?></td>
                <td><?php echo $getAlumnos['AlumnoNumControl'] ?></td>
                <td><?php echo $getAlumnos['AlumnoCorreo'] ?></td>
                <td><?php echo $getAlumnos['AlumnoArea'] ?></td>
                <td>
                  <a class="btn btn-primary" 
                  href="./ConAlumno.php?IdUsuario=<?php echo $getAlumnos['AlumnoLoginId']; ?>" 
                  role="button">Consultar</a>
                </td>
                <td>
                <a class="btn btn-warning" 
                  href="./EdiAlumno.php?IdUsuario=<?php echo $getAlumnos['AlumnoLoginId']; ?>" 
                  role="button">Editar</a>
                </td>
                <td>
                <a class="btn btn-danger" 
                  href="?IdUsuario=<?php echo $getAlumnos['AlumnoLoginId']; ?>&action=delete" 
                  role="button">Editar</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <!-- Fin Contenido -->
      </div>
    </div>
  </section>

  <?php require './notificacionesAlumno.php' ?>

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