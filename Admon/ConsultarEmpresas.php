<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Crear consulta
$consultaQ = "SELECT 
lng.id_Login AS EmpresaId,
lng.User AS EmpresaUser,
lng.Password AS EmpresaPassword,
e.Nombre AS EmpresaNombre,
e.Tipo_Empresa AS EmpresaTipoConvenio,
e.Razon_Social AS EmpresaRazonSocial,
e.Direccion AS EmpresaDireccion,
e.RFC AS EmpresaRFC,
sat.Nombre AS EmpresaTipoSAT
FROM `empresa` AS e 
LEFT JOIN `login` AS lng ON lng.id_Login = e.id_login
LEFT JOIN `tipo_sat` AS sat ON sat.id_tipo = e.id_tipo ; ";

// Obtener resultado de la consulta
$resultado = $mysqli->query($consultaQ);
$mysqli->close();

$skillData = array();
$fila = 0;
while ($row = $resultado->fetch_assoc()) {
  $fila++;
  $data['fila'] = $fila;
  $data['nombre'] = $row['EmpresaNombre'];
  $data['razon_social'] = $row['EmpresaRazonSocial'];
  $data['direccion'] = $row['EmpresaDireccion'];
  $data['user'] = $row['EmpresaUser'];
  $data['id'] = $row['EmpresaId'];
  array_push($skillData, $data);
}

$getEmpresasJson = json_encode($skillData);

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
      <h1>Listado De Empresas</h1>
      <p class="lead">SISVINTEC</p><br>
      <p class="lead">Usuario Administrador</p>
    </div>
    <div class="col-12 col-md-12">
    </div>

    <div class="row">
      <div class="col-12 col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-hover table-responsive table-bordered" id="tbl-empresas">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Usuario</th>
                  <th>Nombre</th>
                  <th>Razón Social</th>
                  <th>Dirección Fiscal</th>
                  <th>Acciones</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Fin Contenido -->
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
      var tabla = $('#tbl-empresas').DataTable({
        language: {
                url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json"
        },
        data: <?php echo $getEmpresasJson; ?>,
        columns: [{
            data: 'fila'
          },
          {
            data: 'user'
          },
          {
            data: 'nombre'
          },
          {
            data: 'razon_social'
          },
          {
            data: 'direccion'
          },
          {
            targets: -1,
            data: null,
            defaultContent: "<div class='btn-acciones'>" +
              "<a role=button id='btn-consultar-empresa' class='btn btn-primary'><i class='bi bi-eye-fill'></i></a>" +
              "<a role=button id='btn-editar-empresa' class='btn btn-warning'><i class='bi bi-pencil-fill'></i></a>" +
              "<a role=button id='btn-eliminar-empresa' class='btn btn-danger'><i class='bi bi-trash-fill'></i></a>" +
              "</div>",
          },
        ]
      });

      $('#tbl-empresas tbody').on('click', '#btn-consultar-empresa', function(e) {
        fncConsultarEmpresa(tabla.row($(this).parents('tr')).data().id);
      });

      $('#tbl-empresas tbody').on('click', '#btn-editar-empresa', function(e) {
        fncEditarEmpresa(tabla.row($(this).parents('tr')).data().id);
      });

      $('#tbl-empresas tbody').on('click', '#btn-eliminar-empresa', function(e) {
        fncEliminarEmpresa(tabla.row($(this).parents('tr')).data().id);
      });

    });

    function fncConsultarEmpresa(id_empresa) {
      window.location.href = "/Admon/ConEmpresa.php?IdEmpresa=" + id_empresa;
    }

    function fncEditarEmpresa(id_empresa) {
      window.location.href = "/Admon/EdiEmpresa.php?IdEmpresa=" + id_empresa;
    }

    function fncEliminarEmpresa(id_empresa) {
      Swal.fire({
        title: 'Estás seguro de eliminarlo?',
        text: "Sé eliminará de manera permanente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminalo!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            method: "GET",
            url: "/Admon/FncDatabase/EmpresaEliminar.php?id=" + id_empresa,
            success: function(resp) {
              Swal.fire(
                'Eliminado!',
                'La empresa se elimino exitosamente.',
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