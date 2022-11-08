<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include '../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$IdEmpresa = $_SESSION['idE'];

// Crear consulta
$consultaGetEmpresa = "SELECT 
lng.User AS EmpresaUser,
e.Nombre AS EmpresaNombre,
e.Tipo_Empresa AS EmpresaTipoConvenio,
e.Razon_Social AS EmpresaRazonSocial,
e.Direccion AS EmpresaDireccion,
e.RFC AS EmpresaRFC,
e.id_Empresa AS EmpresaID
FROM `empresa` AS e 
LEFT JOIN `login` AS lng ON lng.id_Login = e.id_login
WHERE e.id_empresa = $IdEmpresa ; ";

// Crear consulta
$consultaGetTrabajadores = "SELECT
t.Nombre AS TrabajadorNombre,  
t.RFC AS TrabajadorRFC,  
t.Correo AS TrabajadorCorreo,  
t.Puesto AS TrabajadorPuesto,  
e.Nombre AS TrabajadorEmpresa,  
t.Tel AS TrabajadorTelefono  
FROM `trabajador` AS t    
LEFT JOIN `empresa` AS e ON e.id_empresa = t.id_Empresa  
WHERE t.id_Empresa = $IdEmpresa ; ";

// *** Obtener el registro de la empresa ***/
// Obtener resultado de la consulta
$resultadoGetEmpresa = $mysqli->query($consultaGetEmpresa);

//  Verificar si existe registro del proyecto
if ($resultadoGetEmpresa->num_rows <= 0) {
    header("Location: /Admon/ConsultarProyectos.php");
}

// Obtener los registros de la empresa
$getEmpresa = $resultadoGetEmpresa->fetch_assoc();
//print var_dump($getEmpresa);

// *** Obtener el registro de los trabajadores ***/
// Obtener resultado de la consulta
$resultadoGetTrabajadores = $mysqli->query($consultaGetTrabajadores);

//  Verificar si existe registro del proyecto
if ($resultadoGetTrabajadores->num_rows <= 0) {
    header("Location: /Admon/ConsultarProyectos.php");
}

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

    <!-- sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <!-- Información de registro -->
            <span style="font-weight:bold;color:#000080;">Información de registro&nbsp;</span>
            <hr>
            <label for="nombre" class="col-lg-3 control-label">Usuario:</label>
            <div class="col-lg-9">
                <p class="form-control" disabled><?php echo $getEmpresa['EmpresaUser']; ?></p>
            </div>

            <label class="col-lg-3 control-label">Contraseña:</label>
            <div class="col-lg-9">
                <p class="form-control" disabled><?php echo "******"; ?></p>
            </div>

            <!-- Información de la empresa -->
            <span style="font-weight:bold;color:#000080;">Información de la empresa &nbsp;</span>
            <hr>
            <label for="nombre" class="col-lg-3 control-label">Nombre:</label>
            <div class="col-lg-9">
                <p class="form-control" disabled><?php echo $getEmpresa['EmpresaNombre']; ?></p>
            </div>

            <label for="nombre" class="col-lg-3 control-label">Tipo de Convenio:</label>
            <div class="col-lg-9">
                <p class="form-control" disabled><?php echo $getEmpresa['EmpresaTipoConvenio']; ?></p>
            </div>

            <label for="nombre" class="col-lg-3 control-label">Razón Social:</label>
            <div class="col-lg-9">
                <p class="form-control" disabled><?php echo $getEmpresa['EmpresaRazonSocial']; ?></p>
            </div>

            <label for="nombre" class="col-lg-3 control-label">RFC:</label>
            <div class="col-lg-9">
                <p class="form-control" disabled><?php echo $getEmpresa['EmpresaRFC']; ?></p>
            </div>

            <label class="col-lg-3 control-label">Dirección:</label>
            <div class="col-lg-9 m-2">
                <p class="form-control" disabled><?php echo $getEmpresa['EmpresaDireccion']; ?></p>
            </div>

            <!-- Información de trabajadores -->
            <section class="cuerpo">
                <div class="container">
                    <span style="font-weight:bold;color:#000080;">Información de trabajadores&nbsp;</span>
                    <hr>
                    <table class="table table-hover table-responsive table-bordered">
                        <tbody>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>RFC</th>
                                <th>Correo electrónico</th>
                                <th>Puesto</th>
                                <th>Teléfono</th>
                            </tr>

                            <?php
                            $fila = 0;
                            while ($getTrabajadores = $resultadoGetTrabajadores->fetch_assoc()) {
                                $fila++;
                            ?>
                                <tr>
                                    <td><?php echo $fila; ?></td>
                                    <td><?php echo $getTrabajadores['TrabajadorNombre']; ?></td>
                                    <td><?php echo $getTrabajadores['TrabajadorRFC']; ?></td>
                                    <td><?php echo $getTrabajadores['TrabajadorCorreo']; ?></td>
                                    <td><?php echo $getTrabajadores['TrabajadorPuesto']; ?></td>
                                    <td><?php echo $getTrabajadores['TrabajadorTelefono']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Botones (Para acciones) -->
            <!-- <hr>
            <br><br>
            <a class="btn btn-primary" href="/Admon/ConsultarEmpresas.php" role="button">Volver</a>
            <a class="btn btn-warning" href="/Admon/EdiEmpresa.php?IdEmpresa=<?php echo $idEmpresa; ?>" role="button">Editar</a> -->
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