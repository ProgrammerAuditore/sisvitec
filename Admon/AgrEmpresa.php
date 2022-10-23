<?php
      session_start();
    include'conexion.php';
    $mysql = new Conexion();
    $mysqli= $mysql->_ObtenerConexion();
    ?>
<!DOCTYPE html>
<html lang="en">
  <head><meta charset="windows-1251">
  
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
          include'Menu.php'; 
       ?>
     <section class="jumbotron" style="text-align: center;">
          <div class="container">
              <h1>SisVinTec</h1>
              <p class="lead">Sistema para Vinculacion del TECMM</p><br>
          </div>
           <hr>
           <p class="lead" >Solicitud Empresa</p>
              <hr>
      </section>
    <section class="cuerpo">
          <div class="container">
          <form  class="form-datos" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" role="form">
             <span style="font-weight:bold;color:#000080;">Informacion de registro&nbsp;</span>
              <hr>
                  <label for="nombre" class="col-lg-3 control-label">Usuario:</label>
                  <div class="col-lg-9">
                      <input type="text" placeholder="Usuario" name="user" class="form-control" id="nombre"><br>
                  </div>
              
                  <label  class="col-lg-3 control-label">Contrasena:</label>
                  <div class="col-lg-9">
                      <input type="password" placeholder="Contraseña" name="pass" class="form-control" data-toggle="password"><br>
                  </div>
                    
              
              <span style="font-weight:bold;color:#000080;">Datos Generales&nbsp;</span>
              <hr>
              <label class="col-lg-3 control-label">Nombre De La Empresa:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="name" name="NombreE"><br>
                  </div>
                  <label class="col-lg-3 control-label">Razon Social:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="RazonS"><br>
                  </div>
                  <label class="col-lg-3 control-label">RFC:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="RFCE"><br>
                  </div>

              <label class="col-lg-3 control-label">Tipo de Persona SAT:</label>
              <div class="col-lg-9">
                                  <div class="selector-pais">
                                <select name="idtipo" class="form-control">
                                    <script type="text/javascript">
                                         $(document).ready(function() {
                                            $.ajax({
                                                    type: "POST",
                                                    url: "ajaxSAT.php",
                                                    success: function(response)
                                                    {
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
                      <input type="text" class="form-control" id="direc" name="direccion"><br>
                  </div>
              <label for="turno" class="col-lg-3 control-label">Tamaño de la Empresa:</label>
                  <div class="col-lg-9">
                      <select name="magnitud" class="form-control">
                                <Option select value="">Seleccione</Option>
                                <Option value="MicroEmpresa">MicroEmpresa</Option>
                                <Option value="Chica">Chica</Option>
                                <Option value="Mediana">Mediana</Option>
                                <Option value="Grande">Grande</Option>
                                </select><br>
                  </div>
                  <label for="turno" class="col-lg-3 control-label">Alcance:</label>
                  <div class="col-lg-9">
                      <select name="alcance" class="form-control">
                                <Option select value="">Seleccione</Option>
                                <Option value="Estatal">Estatal</Option>
                                <Option value="Nacional">Nacional</Option>
                                <Option value="Internacional">Internacional</Option>
                                </select><br>
                  </div>
                  <label class="col-lg-3 control-label">Giro De La Empresa:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="Giro"><br>
                  </div>
                                    <label class="col-lg-3 control-label">Mision:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="Mision"><br>
                  </div>
                  <label for="turno" class="col-lg-3 control-label">Tipo de Convenio:</label>
                  <div class="col-lg-9">
                      <select name="tipoEmpresa" class="form-control">
                                <Option select value="">Seleccione</Option>
                                <Option value="Servicios profesionales y consultoría ">Servicios profesionales y consultoría </Option>
                                <Option value="Desarrollo y transferencia de tecnología">Desarrollo y transferencia de tecnología</Option>
                                </select><br>
                  </div>
 
              <span style="font-weight:bold;color:#000080;">Datos de  Representante Legal&nbsp;</span>
              <hr>
              <label class="col-lg-3 control-label">Nombre:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="NombreRL"><br>
                  </div>
                                <label class="col-lg-3 control-label">RFC:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="RFCRL"><br>
                  </div>

              <label class="col-lg-3 control-label">Correo:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="CorreoRL"><br>
                  </div>

              <label class="col-lg-3 control-label">Tel/CEl:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="TelRL"><br>
                  </div>
              <span style="font-weight:bold;color:#000080;">Datos de Recursos Humanos&nbsp;</span>
              <hr>
              <label class="col-lg-3 control-label">Nombre:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="NombreRH"><br>
                  </div>
                                <label class="col-lg-3 control-label">RFC:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="RFCRH"><br>
                  </div>

              <label class="col-lg-3 control-label">Correo:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="CorreoRH"><br>
                  </div>

              <label class="col-lg-3 control-label">Tel/CEl:</label>
                  <div class="col-lg-9">
                      <input type="text" class="form-control" id="nc" name="TelRH"><br>
                  </div>


              <hr>
              <br><br> 
              <input class="btn btn-primary" type="submit" name="enviar" value="Guardar" >
               <a class="btn btn-primary" href="ConsultarEmpresa.php" role="button">Consultar o Eliminar</a>
              </form>
              </div>
      </section>
      <?php
            
      if(isset($_POST["enviar"]))
      {
        $mysql = new conexion();
        $con = $mysql->_ObtenerConexion();
          if(!$con)
          {
            die("Connection failed: " . $con->connect_error);
          }
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $NombreE   = $_POST['NombreE'];
            $RazonSE = $_POST['RazonS'];
            $RFCE = $_POST['RFCE'];
            $idtipo = $_POST['idtipo'];
            $Direccion = $_POST['direccion'];
            $magnitud = $_POST['magnitud'];
            $alcance = $_POST['alcance'];
            $Giro = $_POST['Giro'];
            $Mision = $_POST['Mision'];
            $tipoEmpresa = $_POST['tipoEmpresa'];

            $NombreRL   = $_POST['NombreRL'];
            $CorreoRL = $_POST['CorreoRL'];
            $RFCRL = $_POST['RFCRL'];
            $TelRL = $_POST['TelRL'];

            $NombreRH   = $_POST['NombreRH'];
            $CorreoRH = $_POST['CorreoRH'];
            $RFCRH = $_POST['RFCRH'];
            $TelRH = $_POST['TelRH'];

           mysqli_query($con, "START TRANSACTION;");
              $result = mysqli_query($con, "select * from login where User = '".$user."' and Password = '".$pass."' and Existe=1;");
              if(mysqli_num_rows($result)>0)
              {
                 echo 'el usuario ya existe';
                 mysqli_query($con, "RollBack;");
              }
              else
              {
                  $sql = "INSERT INTO `login`(`tipo`, `User`, `Password`, `Existe`) VALUES (2,'".$user."','".$pass."',1)";
         
                 if(mysqli_query($con, $sql))
                    {
                        $validaEmpresa = mysqli_query($con,"SELECT * FROM empresa WHERE Nombre = '$NombreE' and Razon_Social = '$RazonSE' and Existe=1");
                        if(mysqli_num_rows($validaEmpresa)>0)
                          {
                          mysqli_query($con, "RollBack;");
                            echo 'el registro ya existe';
                          }
                        else
                          {
                              $sql1 = "SELECT MAX(id_Login) From login";
                              $idusu=mysqli_query($con,$sql1);
                              $row = mysqli_fetch_row($idusu);
                              $insertarEmpresa = "INSERT INTO `empresa`(`Nombre`, `Razon_Social`, `RFC`, `id_tipo`, `Direccion`, `Magnitud`, `Alcance`, `Giro`, `Mision`, `Tipo_empresa`, `id_login`, `Existe`) VALUES ('".$NombreE."','".$RazonSE."','".$RFCE."',".$idtipo.",'".$Direccion."','".$magnitud."','".$alcance."','".$Giro."','".$Mision."','".$tipoEmpresa."','".$row[0]."',1)";

                               
                              if (mysqli_query($con,$insertarEmpresa))
                                {
                                    $sql4 = "SELECT MAX(id_empresa) From empresa";
                                    $empre=mysqli_query($con,$sql4);
                                    $row4 = mysqli_fetch_row($empre);
                                    $insertRL="INSERT INTO `trabajador`(`Nombre`, `RFC`,`Correo`,`Puesto`,`id_Empresa`,`Existe`,`Tel`) VALUES ('".$NombreRL."','".$RFCRL."','".$CorreoRL."','REPRESENTANTE LEGAL',".$row4[0].",1,'".$TelRL."')";

                                  if(mysqli_query($con,$insertRL))
                                  {
                                      $insertRH="INSERT INTO `trabajador`(`Nombre`, `RFC`,`Correo`,`Puesto`,`id_Empresa`,`Existe`,`Tel`) VALUES ('".$NombreRH."','".$RFCRH."','".$CorreoRH."','RECURSOS HUMANOS',".$row4[0].",'1','".$TelRH."')";
                                    
                                        if(mysqli_query($con,$insertRH))
                                            {
                                                    mysqli_query($con, "COMMIT;");
                                                    ?>
                                                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                                                        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" />
                                                        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>
                                                        <script>
                                                            $.jGrowl("EL REGISTRO SE GUARDO CON EXITO!",{
                                                                life : 3000,
                                                                position:'bottom-right',
                                                                theme: 'test' 
                                                            });
                                                        </script>
                                                                  <style>
                                                                      .test{
                                                                                background-color:       #31B404;
                                                                                          width:  300px;
                                                                                height:80px;
                                                                                          text-align:center;
                                                                            }
                                                                  </style>
                                                     <?php
                                            }
                            
                                  
                                          else
                                            {
                                              mysqli_query($con, "RollBack;");
                                              echo 'ERROR AL AGREGAR Recursos Humanos';
                                            }
                                  }
                                  else
                                  {
                                      mysqli_query($con, "RollBack;");
                                      echo 'ERROR AL INSERTAR Representante legal';
                                  }
                          }
                        else 
                        {
                            mysqli_query($con, "RollBack;");
                            echo 'error al agregar Empresa'; 
                            echo "Error: " . $insertarEmpresa . "" . mysqli_error($con);
                        }
                        
                    }
                }
                else 
                {
                  mysqli_query($con, "RollBack;");
                  echo 'error al agregar usuario';
                }
            }
            $con->close();
          
      }
        ?>
      <footer>
            <div class="contenedor">
                <p >Copyright &copy; BCB</p>
            </div>          
       </footer>
      <!-- Bootstrap core JavaScript -->
    <script src="../Estilos/dist/js/jquery.js"></script>
    <script src="../Estilos/dist/js/bootstrap.min.js"></script>
  </body>
</html>
