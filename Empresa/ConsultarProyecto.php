<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$IDE = $_SESSION['idE'];
//error_reporting(0);

// Crear consulta
$consultaQ = "SELECT 
p.id_Proyecto AS ProyectoId,
p.Nombre aS ProyectoNombre,
a.Nombre AS ProyectoArea,
p.Descripcion AS ProyectoDescripcion,
p.Duracion AS ProyectoDuracion 
FROM `proyecto` AS p 
LEFT JOIN `area` AS a ON p.id_Area=a.id_Area 
WHERE p.id_Empresa=$IDE";

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

  <!-- sweetalert2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
              <th>Acciones</th>
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
                <td><?php echo $getProyectos['ProyectoArea'] ?></td>
                <td><?php echo $getProyectos['ProyectoDescripcion'] ?></td>
                <td><?php echo $getProyectos['ProyectoDuracion'] ?></td>
                <td>
                  <a class="btn btn-primary" href="<?php echo "/Empresa/ConProyecto.php?IdProyecto=" . $getProyectos['ProyectoId']; ?>">Consultar</a>
                  <a class="btn btn-warning" href="/Empresa/EdiProyecto.php?IdProyecto=<?php echo $getProyectos['ProyectoId']; ?>" role="button">Editar</a>
                  <a class="btn btn-danger" href="<?php echo "?IdProyecto=" . $getProyectos['ProyectoId'] . "&action=delete"; ?>">Eliminar</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <!-- Fin Contenido -->
      </div>
    </div>
  </section>

  <?php if (isset($_GET['action'])) { ?>
    <script>
      Swal.fire({
        icon: '<?php echo (isset($_GET['action'])) ? $_GET['action'] : ''; ?>',
        title: '<?php echo (isset($_GET['title'])) ? $_GET['title'] : ''; ?>',
        html: '<?php echo (isset($_GET['msg'])) ? $_GET['msg'] : ''; ?>'
      }).then((resultado) => {
        var url = document.location.href;
        window.history.pushState({}, "", url.split("?")[0]);
      });
    </script>
  <?php } ?>

  <?php if (isset($_GET['action']) && $_GET['action'] == 'delete') { ?>
    <script>
      Swal.fire({
        title: 'Confirmar',
        text: "¿Seguro que desear eliminar este alumno?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
      }).then((result) => {

        var url = document.location.href;
        window.history.pushState({}, "", url.split("?")[0]);

        if (result.isConfirmed) {
          $.ajax({
            url: "./FncDatabase/ProyectoEliminar.php?id=<?php echo $_GET['IdProyecto']; ?>"
          }).then((a) => {
            Swal.fire({
              icon: 'success',
              title: a
            }).then((r) => {
              window.location.reload();
            });
          });
        }

      })
    </script>
  <?php } ?>

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