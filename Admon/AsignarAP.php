<?php
// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['IdProyecto']) || empty($_GET['IdProyecto'])) {
    header("Location: ./ConsultaAlumno.php");
}

//****  Obtener todo los datos del usuario */
// Obtener el IdUsuario
$IdProyecto = $_GET['IdProyecto'];

// Crear consulta
$consultaQ = "SELECT 
p.Nombre AS ProyectoNombre,
p.Tipo_Proyect AS ProyectoTipo, 
a.Nombre AS ProyectoArea,
p.Duracion AS ProyectoDuracion, 
p.Descripcion AS ProyectoDescripcion, 
p.Objetivo_Espesifico AS ProyectoObjetivoEspecifico, 
p.Objetivo_General AS ProyectoObjetivoGeneral
FROM proyecto AS p 
Left JOIN area AS a ON p.id_Area=a.id_Area 
WHERE p.id_Proyecto = $IdProyecto ; ";

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
    textarea {
        resize: none;
        margin: 0.5em 0px;
    }

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
        <p class="lead">Consultar Proyecto</p>
        <hr>
    </section>
    <section class="cuerpo">
        <div class="container">
            <!-- Informacion del proyecto -->
            <span style="font-weight:bold;color:#000080;">Informacion del proyecto&nbsp;</span>
            <hr>

            <label for="nombre" class="col-lg-3 control-label">Nombre:</label>
            <div class="col-lg-9">
                <p class="form-control"><?php echo $getProyecto['ProyectoNombre']; ?></p>
            </div>

            <label for="nombre" class="col-lg-3 control-label">Tipo de proyecto:</label>
            <div class="col-lg-9">
                <p class="form-control"><?php echo $getProyecto['ProyectoTipo']; ?></p>
            </div>

            <label for="nombre" class="col-lg-3 control-label">Area:</label>
            <div class="col-lg-9">
                <p class="form-control"><?php echo $getProyecto['ProyectoArea']; ?></p>
            </div>

            <label for="nombre" class="col-lg-3 control-label">Duración:</label>
            <div class="col-lg-9">
                <p class="form-control"><?php echo $getProyecto['ProyectoDuracion']; ?></p>
            </div>

            <label class="col-lg-3 control-label">Descripción:</label>
            <div class="col-lg-9 m-2">
                <textarea readonly name="descripcion" class="form-control"><?php echo $getProyecto['ProyectoDescripcion']; ?></textarea>
            </div>

            <!-- Objetivos -->
            <span style="font-weight:bold;color:#000080;">Objetivos&nbsp;</span>
            <hr>
            <label class="col-lg-3 control-label">Objetivo Especifico:</label>
            <div class="col-lg-9">
                <textarea readonly name="descripcion" class="form-control"><?php echo $getProyecto['ProyectoObjetivoEspecifico']; ?></textarea>
            </div>
            <label class="col-lg-3 control-label">Objetivo General:</label>
            <div class="col-lg-9">
                <textarea readonly name="descripcion" class="form-control"><?php echo $getProyecto['ProyectoObjetivoGeneral']; ?></textarea>
            </div>

        </div>
    </section>

    <!-- Listado de alumnos-->
    <section class="cuerpo">
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span> </span>
                    <h5>Listado de alumnos</h5>
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-responsive table-bordered" id="tbl-trabajadores">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Número de control</th>
                                <th>Area</th>
                                <th>Correo</th>
                                <th>Carrera</th>
                                <th>Asingar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="cuerpo">
        <div class="container">
            <a class="btn btn-primary" href="/Admon/ConsultarProyectos.php" role="button">Regresar</a>
            <a class="btn btn-info" href="/Admon/ConProyecto.php?IdProyecto=<?php echo $IdProyecto; ?>" role="button">Consultar</a>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            var tabla = $('#tbl-trabajadores').DataTable({
                ajax: {
                    url: './FncDatabase/ListarAlumnos.php?lista=noasig&idP=<?php echo $IdProyecto; ?>',
                    dataSrc: ''
                },
                columns: [{
                        data: 'numero'
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        data: 'numero_control'
                    },
                    {
                        data: 'area'
                    },
                    {
                        data: 'correo'
                    },
                    {
                        data: 'carrera'
                    },
                    {
                        targets: -1,
                        data: null,
                        defaultContent: "<a role=button class='btn btn-success'>Asignar</a>",
                    },
                ]
            });

            $('#tbl-trabajadores tbody').on('click', 'a', function(e) {
                let alumno = tabla.row($(this).parents('tr')).data();
                var searchParams = new URLSearchParams(window.location.search);
                var IdProyecto = searchParams.get('IdProyecto');
                var IdAlumno = alumno.id;

                $.ajax({
                    url: "/Admon/FncDatabase/AsignarAlumno.php?idP=" + IdProyecto + "&idA=" + IdAlumno,
                    async: false, //This is deprecated in the latest version of jquery must use now callbacks
                    dataType: 'json',
                    success: function(resp) {
                        Swal.fire({
                            icon: resp.icon,
                            title: resp.title,
                            html: resp.msg
                        }).then((r) => {
                            window.location.reload();
                        });
                    }
                });

            });
        });
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