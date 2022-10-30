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
lng.User AS EmpresaUser,
lng.Password AS EmpresaPassword,
e.Nombre AS EmpresaNombre,
e.Tipo_Empresa AS EmpresaTipoConvenio,
e.Razon_Social AS EmpresaRazonSocial,
e.Direccion AS EmpresaDireccion,
e.RFC AS EmpresaRFC,
e.id_Empresa AS EmpresaID
FROM empresa AS e 
LEFT JOIN `login` AS lng ON lng.id_Login = e.id_login
WHERE e.id_empresa = $IdEmpresa ; ";

// Crear consulta
$consultaGetTrabajadores = "SELECT
t.id_Trabajador AS TrabajadorId,  
t.Nombre AS TrabajadorNombre,  
t.RFC AS TrabajadorRFC,  
t.Correo AS TrabajadorCorreo,  
t.Puesto AS TrabajadorPuesto,  
e.Nombre AS TrabajadorEmpresa,  
t.Tel AS TrabajadorTelefono  
FROM `trabajador` AS t    
LEFT JOIN `empresa` AS e ON e.id_empresa = t.id_Empresa  
WHERE t.id_Empresa = $IdEmpresa ; ";

// Obtener resultado de la consulta
$result = $mysqli->query($consultaQ);

//****  Verificar si existe registro del proyecto */
if ($result->num_rows <= 0) {
    header("Location: /Admon/ConsultarProyectos.php");
}

// Obtener los registros del proyecto
$getEmpresa = $result->fetch_assoc();
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

    <!-- Bootstrap Icons v5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<style>
    section.cuerpo {
        margin: 0px 0px 2em 0px;
    }

    td.btn-acciones {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }

    div.panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
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

    <!-- Botones (Para acciones) -->
    <section class="cuerpo">
        <div class="container">
            <br>
            <div class="panel panel-default">
                <div class="panel-body">
                    <a class="btn btn-primary" href="/Admon/ConsultarEmpresas.php" role="button">Volver</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Información de general -->
    <section class="cuerpo">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="btn btn-warning" href="/Admon/EdiEmpresa.php?IdEmpresa=<?php echo $_GET['IdEmpresa']; ?>" role="button">Editar</a>
                    <h5>Información de general</h5>
                </div>
                <div class="panel-body">

                    <!-- Información de registro -->
                    <span style="font-weight:bold;color:#000080;">Informacion de registro&nbsp;</span>
                    <hr>
                    <label for="nombre" class="col-lg-3 control-label">Usuario:</label>
                    <div class="col-lg-9">
                        <p class="form-control" disabled><?php echo $getEmpresa['EmpresaUser']; ?></p>
                    </div>

                    <label class="col-lg-3 control-label">Contrasena:</label>
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

                    <label for="nombre" class="col-lg-3 control-label">Razon Social:</label>
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

                </div>
            </div>
        </div>
    </section>

    <!-- Información de trabajadores -->
    <section class="cuerpo">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="btn btn-success" href="/Admon/AgrTrabajador.php" role="button">Agregar</a>
                    <h5>Información de trabajadores</h5>
                </div>
                <table class="table table-hover table-responsive table-bordered">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Correo</th>
                            <th>Puesto</th>
                            <th>Telefono</th>
                            <th>Acciones</th>
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
                                <td class="btn-acciones">
                                    <a 
                                    href="/Admon/EdiTrabajador.php?IdTrabajador=<?php echo $getTrabajadores['TrabajadorId']; ?>"
                                    class="btn btn-warning" role="button"><i class="bi bi-pencil-square"></i></a>
                                    <a href="#" class="btn btn-danger" role="button"><i class="bi bi-x-square"></i></a>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
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