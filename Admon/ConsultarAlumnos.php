<?php
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
error_reporting(0);
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
          <?php
          $mysql = new conexion();
          $con = $mysql->_ObtenerConexion();
          if (!$con) {
            die('error de conexion de servidor:' . mysql_error());
          }
          $consulta = "SELECT alu.id_Login,alu.id_Alumnos,alu.Nombre,alu.Num_Control,alu.Correo,car.Nombre as Carrera, are.Nombre as Area From alumnos as alu LEFT JOIN carrera as car On alu.id_Carrera=car.id_carrera
LEFT JOIN area as are On alu.id_Area = are.id_Area;";
          $resultado = mysqli_query($con, $consulta);
          $contador = 0;
          while ($misdatos = mysqli_fetch_assoc($resultado)) {
            $contador++; ?>
            <tr>
              <td><?php echo $contador ?></td>
              <td><?php echo $misdatos["Nombre"]; ?></td>
              <td><?php echo $misdatos["Num_Control"]; ?></td>
              <td><?php echo $misdatos["Correo"]; ?></td>
              <td><?php echo $misdatos["Area"]; ?></td>
              <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=ConAlumno.php?IdUsuario={$misdatos["id_Login"]}><font color='#ffffff'>Consultar</font></a>" ?></td>

              <td><?php echo "<a style='margin:3px' class='btn btn-warning' href=EdiAlumno.php?IdUsuario={$misdatos["id_Login"]} ><font color='#ffffff'>Editar</font></a>" ?></td>
              <td><?php echo "<a style='margin:3px' class='btn btn-danger' href=?IdUsuario={$misdatos["id_Login"]}&action=delete ><font color='#ffffff'>Eliminar</font></a>" ?></td>

            </tr>

          <?php } ?>

          </tbody>
        </table>
        <!-- Fin Contenido -->
      </div>
    </div>
  </section>

  <?php if (isset($_GET['action']) && $_GET['action'] == 'updated_success') { ?>
    <script>
      Swal.fire({
        icon: "success",
        text: "Datos actualizados"
      }).then((resultado) => {
        var url = document.location.href;
        window.history.pushState({}, "", url.split("?")[0]);
      });
    </script>
  <?php } ?>

  <?php if (isset($_GET['action']) && $_GET['action'] == 'created_success') { ?>
    <script>
      Swal.fire({
        icon: "success",
        text: "Alumno creado"
      }).then((resultado) => {
        var url = document.location.href;
        window.history.pushState({}, "", url.split("?")[0]);
      });
    </script>
  <?php } ?>

  <?php if (isset($_GET['action']) && $_GET['action'] == 'updated_error') { ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Datos no actualizado.',
        text: 'Verifique que los campos sean correctos y no vacíos.\n' +
          'Vuelve a intentarlo.'
      }).then((resultado) => {
        var url = document.location.href;
        window.history.pushState({}, "", url.split("?")[0]);
      });
    </script>
  <?php } ?>

  <?php if (isset($_GET['action']) && $_GET['action'] == 'created_error') { ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Alumno no fue creado',
        text: 'Verifique que los campos sean correctos y no vacíos.\n' +
          'Vuelve a intentarlo.'
      }).then((resultado) => {
        var url = document.location.href;
        window.history.pushState({}, "", url.split("?")[0]);
      });
    </script>
  <?php } ?>

  <?php if (isset($_GET['action']) && $_GET['action'] == 'created_exist') { ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Alumno no fue creado',
        text: 'El nombre de usuario ya está registrado. \n' +
          'Vuelve a intentarlo.'
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