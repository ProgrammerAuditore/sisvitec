<?php
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$IDE = $_SESSION['idE'];
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
              <th>Editar Proyecto</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <?php
          $mysql = new conexion();
          $con = $mysql->_ObtenerConexion();
          if (!$con) {
            die('error de conexion de servidor:' . mysql_error());
          }
          $consulta = "SELECT p.Nombre as NombreP,a.Nombre,p.Descripcion,p.Duracion FROM proyecto as p Left JOIN area as a ON p.id_Area=a.id_Area where p.id_Empresa=" . $IDE . ";";
          $resultado = mysqli_query($con, $consulta);
          $contador = 0;
          while ($misdatos = mysqli_fetch_assoc($resultado)) {
            $contador++; ?>
            <tr>
              <td><?php echo $contador ?></td>
              <td><?php echo $misdatos["NombreP"]; ?></td>
              <td><?php echo $misdatos["Nombre"]; ?></td>
              <td><?php echo $misdatos["Descripcion"]; ?></td>
              <td><?php echo $misdatos["Duracion"]; ?></td>
              <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=C.php?id=" . $misdatos["id_empresa"] . "&IdUsuario=" . $misdatos["id_Login"] . "><font color='#ffffff'>Consultar</font></a>" ?></td>

              <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=C.php?id=" . $misdatos["id_empresa"] . "&IdUsuario=" . $misdatos["id_Login"] . "><font color='#ffffff'>Editar</font></a>" ?></td>
              <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=C.php?id=" . $misdatos["id_empresa"] . " data-confirm='¿Está seguro de que desea eliminar el alumno seleccionado?'><font color='#ffffff'>Eliminar</font></a>" ?></td>

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
            url: "./FncDatabase/AlumnoEliminar.php?id=<?php echo $_GET['IdUsuario']; ?>"
          });
          Swal.fire(
            'Eliminado!',
            'Alumno fue eliminado.',
            'success'
          ).then((r) => {
            window.location.reload();
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