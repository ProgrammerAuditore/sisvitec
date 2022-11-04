<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Crear consulta
$consultaQ = "SELECT  
p.id_Proyecto AS ProyectoId, 
p.Nombre AS ProyectoNombre,  
p.Duracion AS ProyectoDuracion,  
p.Tipo_Proyect AS ProyectoTipo,  
a.Nombre AS ProyectoArea, 
emp.Nombre AS ProyectoEmpresa 
FROM proyecto AS p  
LEFT JOIN `empresa` AS emp ON emp.id_empresa = p.id_Empresa 
LEFT JOIN area AS a ON p.id_Area = a.id_Area ; ";

// Obtener resultado de la consulta
$resultado = $mysqli->query($consultaQ);
$mysqli->close();

$skillData = array();
$fila = 0;
while ($row = $resultado->fetch_assoc()) {
  $fila++;
  $data['fila'] = $fila;
  $data['nombre'] = $row['ProyectoNombre'];
  $data['tipo'] = $row['ProyectoTipo'];
  $data['duracion'] = $row['ProyectoDuracion'];
  $data['area'] = $row['ProyectoArea'];
  $data['empresa'] = $row['ProyectoEmpresa'];
  $data['id'] = $row['ProyectoId'];
  array_push($skillData, $data);
}

$getProyectosJson = json_encode($skillData);

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
      <h1>Listado De Proyectos</h1>
      <p class="lead">SISVINTEC</p><br>
      <p class="lead">Usuario Administrador</p>
    </div>

    <div class="col-12 col-md-12">
    </div>

    <div class="row">
      <div class="col-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-hover table-responsive table-bordered" id="tbl-proyectos">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre Proyecto</th>
                  <th>Tipo</th>
                  <th>Duración</th>
                  <th>Area</th>
                  <th>Empresa</th>
                  <th>Acciones</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="contenedor">
      <p>Copyright &copy; BCB</p>
    </div>
  </footer>

  <script>
    $(document).ready(function() {
      var tabla = $('#tbl-proyectos').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
        },
        data: <?php echo $getProyectosJson; ?>,
        columns: [{
            data: 'fila'
          },
          {
            data: 'nombre'
          },
          {
            data: 'tipo'
          },
          {
            data: 'duracion'
          },
          {
            data: 'area'
          },
          {
            data: 'empresa'
          },
          {
            targets: -1,
            data: null,
            defaultContent: "<div class='btn-acciones'>" +
              "<a role=button id='btn-consultar-proyecto' class='btn btn-primary'><i class='bi bi-eye-fill'></i></a>" +
              "<a role=button id='btn-asignar-proyecto' class='btn btn-success'><i class='bi bi-plus-square'></i></a>" +
              "</div>",
          },
        ]
      });

      $('#tbl-proyectos tbody').on('click', '#btn-consultar-proyecto', function(e) {
        fncConsultarProyecto(tabla.row($(this).parents('tr')).data().id);
      });

      $('#tbl-proyectos tbody').on('click', '#btn-asignar-proyecto', function(e) {
        fncAsignarProyecto(tabla.row($(this).parents('tr')).data().id);
      });

    });

    function fncConsultarProyecto(id_proyecto) {
      window.location.href = "/Admon/ConProyecto.php?IdProyecto=" + id_proyecto;
    }

    function fncAsignarProyecto(id_proyecto) {
      window.location.href = "/Admon/AsignarAP.php?IdProyecto=" + id_proyecto;
    }

  </script>

  <!-- Bootstrap core JavaScript -->
  <script src="../Estilos/dist/js/jquery.js"></script>
  <script src="../Estilos/dist/js/bootstrap.min.js"></script>
</body>

</html>