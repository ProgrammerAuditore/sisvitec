<?php
     session_start();
        include'conexion.php';
        $mysql = new Conexion();
        $mysqli= $mysql->_ObtenerConexion();
        error_reporting(0);
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
  </head>
  <body>
      <?php
          include'Menu.php'; 
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
          <tr align='center' class='table table-hover' >
            <th>#</th>
              <th>Nombre Empresas</th>
              <th>Razon Social</th>
              <th>Direccion Fiscal</th>
              <th>Consultar Empresa</th>
              <th>Editar Empresa</th>
              <th>Eliminar</th>
          </tr>
        </thead>
            <?php
        $mysql = new conexion();
        $con = $mysql->_ObtenerConexion();
        if(!$con)
        {
            die('error de conexion de servidor:'.mysql_error());
        }
            $consulta = "SELECT emp.id_empresa,emp.Nombre,emp.Razon_Social,emp.Direccion,log.id_Login FROM empresa as emp LEFT JOIN login as log ON emp.id_login=log.id_Login Where emp.Existe=1";
            $resultado = mysqli_query($con , $consulta);
            $contador=0; 
            while($misdatos = mysqli_fetch_assoc($resultado)){ $contador++;?>
            <tr>
              <td><?php echo $contador ?></td>
              <td><?php echo $misdatos["Nombre"]; ?></td>
              <td><?php echo $misdatos["Razon_Social"]; ?></td>
              <td><?php echo $misdatos["Direccion"]; ?></td>
               <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=ConsultaPempresa.php?id=".$misdatos["id_empresa"]."&IdUsuario=".$misdatos["id_Login"]."><font color='#ffffff'>Consultar</font></a>" ?></td>
              
                <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=EditarAlumno.php?id=".$misdatos["id_empresa"]."&IdUsuario=".$misdatos["id_Login"]."><font color='#ffffff'>Editar</font></a>" ?></td>
                  <td><?php echo "<a style='margin:3px' class='btn btn-primary' href=EliminarEmpresa.php?id=".$misdatos["id_empresa"]." data-confirm='¿Está seguro de que desea eliminar el alumno seleccionado?'><font color='#ffffff'>Eliminar</font></a>" ?></td>
               
              </tr>

            <?php }?>          

            </tbody>
                  </table>		
            <!-- Fin Contenido --> 
            </div>
            </div>
      </section>
      <section class="cuerpo">
          <div class="container">
               <center>
          </center>
          </div>

      </section> 
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
