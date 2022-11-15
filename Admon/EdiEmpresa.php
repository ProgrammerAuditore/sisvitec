<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include '../conexion.php';
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
LEFT JOIN `tipo_sat` AS sat ON sat.id_tipo = e.id_tipo 
WHERE e.id_login = $IdEmpresa ; ";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

//****  Verificar si existe registro del proyecto */
if ($result->num_rows <= 0) {
    header("Location: /Admon/ConsultarProyectos.php");
}

// Obtener los registros del proyecto
$getEmpresa = $result->fetch_assoc();
//print var_dump($getEmpresa);

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

                <!-- Información de registro -->
                <span style="font-weight:bold;color:#000080;">Información de registro&nbsp;</span>
                <hr>
                <label for="cuenta-user" class="col-lg-3 control-label">Usuario:</label>
                <div class="col-lg-9">
                    <input type="text" value="<?php echo $getEmpresa['EmpresaUser']; ?>" placeholder="Usuario" name="cuenta-user" class="form-control" id="cuenta-user" required><br>
                </div>

                <label for="cuenta-password" class="col-lg-3 control-label">Contraseña:</label>
                <div class="col-lg-9">
                    <input value="<?php echo $getEmpresa['EmpresaPassword']; ?>" type="password" placeholder="Contraseña" id="cuenta-password" name="cuenta-password" class="form-control" data-toggle="password" required><br>
                </div>

                <!-- Información de la empresa -->
                <span style="font-weight:bold;color:#000080;">Información de la empresa &nbsp;</span>
                <hr>
                <label for="empresa-nombre" class="col-lg-3 control-label">Nombre:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="empresa-nombre" name="empresa-nombre" type="text" placeholder="Nombre" value="<?php echo $getEmpresa['EmpresaNombre']; ?>" required><br>
                </div>

                <!-- Tipo de convenio -->
                <label for="empresa-tipo-convenio" class="col-lg-3 control-label">Tipo de Convenio:</label>
                <div class="col-lg-9">
                    <select name="empresa-tipo-convenio" id="empresa-tipo-convenio" class="form-control" required>
                        <?php
                        $opcionesConvenio = array(
                            "Seleccionar tipo de convenio...",
                            "Servicios profesionales y consultoría",
                            "Desarrollo y transferencia de tecnología"
                        );
                        foreach ($opcionesConvenio as $valor) {
                            if (trim($valor) == trim($getEmpresa["EmpresaTipoConvenio"])) {
                                echo "<option value='$valor' selected>$valor</option>";
                            } else {
                                echo "<option value='$valor'>$valor</option>";
                            }
                        }
                        ?>
                    </select><br>
                </div>

                <label for="empresa-razon-social" class="col-lg-3 control-label">Razón Social:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="empresa-razon-social" name="empresa-razon-social" type="text" placeholder="Razon social" value="<?php echo $getEmpresa['EmpresaRazonSocial']; ?>" required><br>
                </div>

                <label for="empresa-rfc" class="col-lg-3 control-label">RFC:</label>
                <div class="col-lg-9">
                    <input class="form-control" id="empresa-rfc" name="empresa-rfc" type="text" placeholder="RFC" value="<?php echo $getEmpresa['EmpresaRFC']; ?>" required><br>
                </div>

                <label for="empresa-direccion" class="col-lg-3 control-label">Dirección:</label>
                <div class="col-lg-9 m-2">
                    <input class="form-control" id="empresa-direccion" name="empresa-direccion" type="text" placeholder="Dirección" value="<?php echo $getEmpresa['EmpresaDireccion']; ?>" required><br>
                </div>

                <!-- Botones (Para acciones) -->
                <hr>
                <br><br>
                <a class="btn btn-primary" href="/Admon/ConsultarEmpresas.php" role="button">Regresar</a>
                <input class="btn btn-warning" type="submit" name="postActualizarEmpresa" value="Actualizar">
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