<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Crear consulta
$consultaQ = "SELECT
lng.User AS AlumnoUsuario,
alu.id_Login AS AlumnoLoginId,
alu.id_Alumnos AS AlumnoId,
alu.Nombre AS AlumnoNombre,
alu.Num_Control AS AlumnoNumeroControl,
alu.Correo AS AlumnoCorreo,
car.Nombre AS AlumnoCarrera, 
are.Nombre AS AlumnoArea,
IF(ISNULL(pro.ProNombre)=1, 'Sin Asignar', pro.ProNombre) AS AlumnoProyecto
FROM `alumnos` AS alu 
LEFT JOIN `carrera` AS car ON alu.id_Carrera=car.id_carrera
LEFT JOIN `login` AS lng ON lng.id_Login=alu.id_Login
LEFT JOIN `area` AS are ON alu.id_Area = are.id_Area
LEFT JOIN (
	SELECT p.Nombre AS ProNombre, a.id_Alumnos AS ProAlumno
	FROM alu_proyect AS x
	LEFT JOIN proyecto AS p ON p.id_Proyecto=x.id_Proyecto
	LEFT JOIN alumnos AS a ON a.id_Alumnos=x.id_Alumno
) AS pro ON pro.ProAlumno = alu.id_Alumnos; ";

// Obtener resultado de la consulta
$resultado = $mysqli->query($consultaQ);
$mysqli->close();

$skillData = array();
$fila = 0;
while ($row = $resultado->fetch_assoc()) {
  $fila++;
  $data['fila'] = $fila;
  $data['nombre'] = $row['AlumnoNombre'];
  $data['numero_control'] = $row['AlumnoNumeroControl'];
  $data['correo'] = $row['AlumnoCorreo'];
  $data['carrera'] = $row['AlumnoCarrera'];
  $data['usuario'] = $row['AlumnoUsuario'];
  $data['proyecto'] = $row['AlumnoProyecto'];
  $data['login_id'] = $row['AlumnoLoginId'];
  array_push($skillData, $data);
}

$getAlumnosJson = json_encode($skillData);

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

  <!-- JQuery -->
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

  <!-- Ajax -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>

  <!-- Bootstrap Icons v5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <!-- sweetalert2 -->
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- https://datatables.net/ -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

</head>

<style>
  .btn-acciones {
    display: flex;
    justify-content: space-evenly;
    align-items: center;
  }
</style>

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
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-hover table-responsive table-bordered" id="tbl-alumnos">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Usuario</th>
                  <th>Nombre</th>
                  <th>Numero De Control</th>
                  <th>Carrera</th>
                  <th>Correo Electronico</th>
                  <th>Proyecto Asignado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
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

  <script>
    $(document).ready(function() {
      var tabla = $('#tbl-alumnos').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
        },
        data: <?php echo $getAlumnosJson; ?>,
        columns: [{
            data: 'fila'
          },
          {
            data: 'usuario'
          },
          {
            data: 'nombre'
          },
          {
            data: 'numero_control'
          },
          {
            data: 'carrera'
          },
          {
            data: 'correo'
          },
          {
            data: 'proyecto'
          },
          {
            targets: -1,
            data: null,
            defaultContent: "<div class='btn-acciones'>" +
              "<a role=button id='btn-consultar-alumno' class='btn btn-primary'><i class='bi bi-eye-fill'></i></a>" +
              "<a role=button id='btn-editar-alumno' class='btn btn-warning'><i class='bi bi-pencil-fill'></i></a>" +
              "<a role=button id='btn-eliminar-alumno' class='btn btn-danger'><i class='bi bi-trash-fill'></i></a>" +
              "</div>",
          },
        ]
      });

      $('#tbl-alumnos tbody').on('click', '#btn-consultar-alumno', function(e) {
        fncConsultarAlumno(tabla.row($(this).parents('tr')).data().login_id);
      });

      $('#tbl-alumnos tbody').on('click', '#btn-editar-alumno', function(e) {
        fncEditarAlumno(tabla.row($(this).parents('tr')).data().login_id);
      });

      $('#tbl-alumnos tbody').on('click', '#btn-eliminar-alumno', function(e) {
        fncEliminarAlumno(tabla.row($(this).parents('tr')).data().login_id);
      });

    });

    function fncConsultarAlumno(login_id_alumno) {
      window.location.href = "/Admon/ConAlumno.php?IdUsuario=" + login_id_alumno;
    }

    function fncEditarAlumno(login_id_alumno) {
      window.location.href = "/Admon/EdiAlumno.php?IdUsuario=" + login_id_alumno;
    }

    function fncEliminarAlumno(login_id_alumno) {
      Swal.fire({
        title: 'Estás seguro de eliminarlo definitivamente?',
        html: "Se borrará de todos los proyectos realizados y asignados.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminalo!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "GET",
            url: "/Admon/FncDatabase/AlumnoEliminar.php?id=" + login_id_alumno,
            success: function(resp) {
              Swal.fire(
                'Eliminado!',
                'El alumno se elimino exitosamente.',
                'success'
              ).then((result) => {
                window.location.reload();
              });
            }
          });
        }
      });
    }
  </script>

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