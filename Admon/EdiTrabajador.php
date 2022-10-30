<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['IdTrabajador']) || empty($_GET['IdTrabajador'])) {
    header("Location: ./ConsultaAlumno.php");
}

//****  Obtener todo los datos del usuario */
// Obtener el IdUsuario
$IdTrabajador = $_GET['IdTrabajador'];

// Crear consulta
$consultaGetTrabajador = "SELECT
t.Nombre AS TrabajadorNombre,  
t.RFC AS TrabajadorRFC,  
t.Correo AS TrabajadorCorreo,  
t.Puesto AS TrabajadorPuesto,  
e.Nombre AS TrabajadorEmpresa,  
t.Tel AS TrabajadorTelefono  
FROM `trabajador` AS t    
LEFT JOIN `empresa` AS e ON e.id_empresa = t.id_Empresa  
WHERE t.id_Trabajador = $IdTrabajador ; ";

// Obtener resultado de la consulta
$resultGetTrabajador = $mysqli->query($consultaGetTrabajador);

//****  Verificar si existe registro del proyecto */
if ($resultGetTrabajador->num_rows <= 0) {
    header("Location: /Admon/ConsultarProyectos.php");
}

// Obtener los registros del proyecto
$getTrabajador = $resultGetTrabajador->fetch_assoc();
//print var_dump($getTrabajador);

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
        <p class="lead">Perfil Trabajador</p>
        <hr>
    </section>
    <section class="cuerpo">
        <div class="container">
            <form class="form-datos" action="<?php echo "./FncDatabase/TrabajadorActualizar.php?id=$IdTrabajador"; ?>" method="POST" role="form">

                <!-- Información del trabajador -->
                <span style="font-weight:bold;color:#000080;">Información del trabajador&nbsp;</span>
                <hr>

                <label for="nombre" class="col-lg-3 control-label">Nombre:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="trabajador-nombre" name="trabajador-nombre" type="text"
                    placeholder="Nombre" value="<?php echo $getTrabajador['TrabajadorNombre']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">RFC:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="trabajador-rfc" name="trabajador-rfc" type="text"
                    placeholder="RFC" value="<?php echo $getTrabajador['TrabajadorRFC']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">Correo:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="trabajador-correo" name="trabajador-correo" type="text"
                    placeholder="Correo" value="<?php echo $getTrabajador['TrabajadorCorreo']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">Telefono:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="trabajador-telefono" name="trabajador-telefono" type="text"
                    placeholder="Telefono" value="<?php echo $getTrabajador['TrabajadorTelefono']; ?>"><br>
                </div>

                <label for="nombre" class="col-lg-3 control-label">Puesto:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="trabajador-puesto" name="trabajador-puesto" type="text"
                    placeholder="Puesto" value="<?php echo $getTrabajador['TrabajadorPuesto']; ?>"><br>
                </div>
                
                <!-- Botones (Para acciones) -->
                <hr>
                <br><br>
                <input class="btn btn-warning" type="submit" name="postActualizarTrabajador" value="Actualizar">

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