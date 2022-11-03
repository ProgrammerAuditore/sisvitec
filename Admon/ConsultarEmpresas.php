<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Crear consulta
$consultaQ = "SELECT 
lng.User AS EmpresaUser,
lng.Password AS EmpresaPassword,
e.Nombre AS EmpresaNombre,
e.Tipo_Empresa AS EmpresaTipoConvenio,
e.Razon_Social AS EmpresaRazonSocial,
e.Direccion AS EmpresaDireccion,
e.RFC AS EmpresaRFC,
e.id_Empresa AS EmpresaId,
sat.Nombre AS EmpresaTipoSAT
FROM empresa AS e 
LEFT JOIN `login` AS lng ON lng.id_Login = e.id_login
LEFT JOIN `tipo_sat` AS sat ON sat.id_tipo = e.id_tipo ; ";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

//****  Verificar si existe registro del proyecto */
if ($result->num_rows <= 0) {
  header("Location: /Admon/ConsultarProyectos.php");
}

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
        <hr>
        <hr>
        <table class="table table-hover">
          <thead>
            <tr align='center' class='table table-hover'>
              <th>#</th>
              <th>Usuario</th>
              <th>Nombre Empresas</th>
              <th>Razon Social</th>
              <th>Direccion Fiscal</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $filas = 0;
            while ($getEmpresas = $result->fetch_assoc()) {
              $filas++;
            ?>
              <tr>
                <td><?php echo $filas ?></td>
                <td><?php echo $getEmpresas['EmpresaUser'] ?></td>
                <td><?php echo $getEmpresas['EmpresaNombre'] ?></td>
                <td><?php echo $getEmpresas['EmpresaRazonSocial'] ?></td>
                <td><?php echo $getEmpresas['EmpresaDireccion'] ?></td>
                <td class="btn-acciones">
                  <a href="<?php echo "/Admon/ConEmpresa.php?IdEmpresa=" . $getEmpresas['EmpresaId']; ?>" class="btn btn-primary" role="button">Consultar</a>
                  <a href="<?php echo "/Admon/EdiEmpresa.php?IdEmpresa=" . $getEmpresas['EmpresaId']; ?>" class="btn btn-warning" role="button">Editar</a>
                  <a href="?action=delete&IdEmpresa=<?php echo $getEmpresas['EmpresaId']; ?>" class="btn btn-danger" role="button">Eliminar</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
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

  <?php if (isset($_GET['action']) && $_GET['action'] == 'delete') { ?>
    <script>
      Swal.fire({
        title: 'Confirmar',
        text: "¿Seguro que desear eliminar está empresa?",
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
            url: "./FncDatabase/EmpresaEliminar.php?id=<?php echo $_GET['IdEmpresa']; ?>"
          });
          Swal.fire({
            icon: 'success',
            title: 'Empresa eliminado.',
          }).then((r) => {
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