<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['IdEmpresa']) || empty($_GET['IdEmpresa'])) {
    header("Location: ./ConsultaAlumno.php");
}

//****  Obtener todo los datos del usuario */
// Obtener el IdUsuario
$IdEmpresa = $_GET['IdEmpresa'];

// Crear consulta
$consultaQ = "SELECT 
e.Nombre AS EmpresaNombre,
e.Tipo_Empresa AS EmpresaTipo,
e.Razon_Social AS EmpresaRazonSocial,
e.Direccion AS EmpresaDireccion,
e.RFC AS EmpresaRFC,
e.id_Empresa AS EmpresaID
FROM empresa AS e 
WHERE e.id_empresa = $IdEmpresa ; ";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

//****  Verificar si existe registro del proyecto */
if ($result->num_rows <= 0) {
    header("Location: /Admon/ConsultarProyectos.php");
}

// Obtener los registros del proyecto
$getProyecto = $result->fetch_assoc();
//print var_dump($getProyecto);

$mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="windows-1251">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Bienvenido a SisVinTec</title>
    <!-- Bootstrap core CSS -->
    <link href="../Estilos/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="../Estilos/EstilosAgregar.css" rel="stylesheet">

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-show-password/1.0.3/bootstrap-show-password.min.js"></script>
</head>

<style>
    textarea {
        resize: none;
        margin: 0.5em 0px;
    }
</style>

<body>
    <?php
    include 'Menu.php';
    ?>
    <section class="jumbotron" style="text-align: center;">
        <div class="container">
            <h1>SisVinTec</h1>
            <p class="lead">Sistema para Vinculacion del TECMM</p><br>
        </div>
        <hr>
        <p class="lead">Perfil Empresa</p>
        <hr>
    </section>
    <section class="cuerpo">
        <div class="container">
        <form class="form-datos" action="<?php echo "./FncDatabase/EmpresaActualizar.php?id=$IdEmpresa"; ?>" method="POST" role="form">
                <!-- Información de la empresa -->
                <span style="font-weight:bold;color:#000080;">Información de la empresa &nbsp;</span>
                <hr>
                <label for="nombre" class="col-lg-3 control-label">Nombre:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="nombre" name="NombreE" type="text" placeholder="Usuario" value="<?php echo $getProyecto['EmpresaNombre']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">Tipo de empresa:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="tipoEmpresa" name="tipoEmpresa" type="text" placeholder="Usuario" value="<?php echo $getProyecto['EmpresaTipo']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">Razon Social:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="RazonS" name="RazonS" type="text" placeholder="Usuario" value="<?php echo $getProyecto['EmpresaRazonSocial']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">RFC:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="RFCE" name="RFCE" type="text" placeholder="Usuario" value="<?php echo $getProyecto['EmpresaRFC']; ?>"><br>
                </div>

                <label class="col-lg-3 control-label">Descripción:</label>
                <div class="col-lg-9 m-2">
                    <textarea name="direccion" class="form-control"><?php echo $getProyecto['EmpresaDireccion']; ?></textarea>
                </div>

                <!-- Botones (Para acciones) -->
                <hr>
                <br><br>
                <input class="btn btn-primary" type="submit" name="postActualizarEmpresa" value="Actualizar">
        </div>
        </form>
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