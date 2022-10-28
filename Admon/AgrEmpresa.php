<?php
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
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
        <p class="lead">Solicitud Empresa</p>
        <hr>
    </section>
    <section class="cuerpo">
        <div class="container">
            <form class="form-datos" action="./FncDatabase/EmpresaAgregar.php" method="POST" role="form">

                <!-- Informacion de registro  -->
                <span style="font-weight:bold;color:#000080;">Informacion de registro&nbsp;</span>
                <hr>
                <label for="nombre" class="col-lg-3 control-label">Usuario:</label>
                <div class="col-lg-9">
                    <input type="text" placeholder="Usuario" name="cuenta-usuario" class="form-control" id="nombre"><br>
                </div>
                <label class="col-lg-3 control-label">Contrasena:</label>
                <div class="col-lg-9">
                    <input type="password" placeholder="Contraseña" name="cuenta-password" class="form-control" data-toggle="password"><br>
                </div>

                <!-- Datos Generales  -->
                <span style="font-weight:bold;color:#000080;">Datos Generales&nbsp;</span>
                <hr>
                <label class="col-lg-3 control-label">Nombre De La Empresa:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="empresa-nombre" name="empresa-nombre"><br>
                </div>
                <label class="col-lg-3 control-label">Razon Social:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="empresa-razon-social"><br>
                </div>
                <label class="col-lg-3 control-label">RFC:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="empresa-rfc"><br>
                </div>
                <label class="col-lg-3 control-label">Tipo de Persona SAT:</label>
                <div class="col-lg-9">
                    <div class="selector-pais">
                        <select name="empresa-tipo-sat" class="form-control">
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: "ajaxSAT.php",
                                        success: function(response) {
                                            $('.selector-pais select').html(response).fadeIn();
                                        }
                                    });

                                });
                            </script>
                        </select><br>
                    </div>
                </div>
                <label class="col-lg-3 control-label">Domicilio Fiscal:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="direc" name="empresa-direccion"><br>
                </div>
                <label for="turno" class="col-lg-3 control-label">Tamaño de la Empresa:</label>
                <div class="col-lg-9">
                    <select name="empresa-magnitud" class="form-control">
                        <Option select value="">Seleccione</Option>
                        <Option value="MicroEmpresa">MicroEmpresa</Option>
                        <Option value="Chica">Chica</Option>
                        <Option value="Mediana">Mediana</Option>
                        <Option value="Grande">Grande</Option>
                    </select><br>
                </div>
                <label for="turno" class="col-lg-3 control-label">Alcance:</label>
                <div class="col-lg-9">
                    <select name="empresa-alcance" class="form-control">
                        <Option select value="">Seleccione</Option>
                        <Option value="Estatal">Estatal</Option>
                        <Option value="Nacional">Nacional</Option>
                        <Option value="Internacional">Internacional</Option>
                    </select><br>
                </div>
                <label class="col-lg-3 control-label">Giro De La Empresa:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="empresa-giro"><br>
                </div>
                <label class="col-lg-3 control-label">Mision:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="empresa-mision"><br>
                </div>
                <label for="turno" class="col-lg-3 control-label">Tipo de Convenio:</label>
                <div class="col-lg-9">
                    <select name="empresa-tipo-convenio" class="form-control">
                        <Option select value="">Seleccione</Option>
                        <Option value="Servicios profesionales y consultoría ">Servicios profesionales y consultoría </Option>
                        <Option value="Desarrollo y transferencia de tecnología">Desarrollo y transferencia de tecnología</Option>
                    </select><br>
                </div>

                <!-- Datos de Representante Legal -->
                <span style="font-weight:bold;color:#000080;">Datos de Representante Legal&nbsp;</span>
                <hr>
                <label class="col-lg-3 control-label">Nombre:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="representante-nombre"><br>
                </div>
                <label class="col-lg-3 control-label">RFC:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="representante-rfc"><br>
                </div>

                <label class="col-lg-3 control-label">Correo:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="representante-correo"><br>
                </div>

                <label class="col-lg-3 control-label">Tel/CEl:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="representante-telefono"><br>
                </div>

                <!-- Datos de Recursos Humanos -->
                <span style="font-weight:bold;color:#000080;">Datos de Recursos Humanos&nbsp;</span>
                <hr>
                <label class="col-lg-3 control-label">Nombre:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="recursoshumanos-nombre"><br>
                </div>
                <label class="col-lg-3 control-label">RFC:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="recursoshumanos-rfc"><br>
                </div>

                <label class="col-lg-3 control-label">Correo:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="recursoshumanos-correo"><br>
                </div>

                <label class="col-lg-3 control-label">Tel/CEl:</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" id="nc" name="recursoshumanos-telefono"><br>
                </div>


                <hr>
                <br><br>
                <input class="btn btn-primary" type="submit" name="postAgregarEmpresa" value="Guardar">
                <a class="btn btn-primary" href="ConsultarEmpresa.php" role="button">Consultar o Eliminar</a>
            </form>
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