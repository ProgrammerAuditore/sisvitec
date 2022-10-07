<?php
        session_start();
      ?>
<!DOCTYPE html>
<html lang="en">
    <head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
	
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SisVInTEc</title>
    <!-- Bootstrap core CSS -->
      <link href="../Estilos/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link href="../Estilos/EstilosAgregar.css" rel="stylesheet">
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  </head>
 
  <body>
      <?php
          include'Menu.php'; 
       ?>
      <section class="jumbotron" style="text-align: center">
          <div class="container">
              <h1>SISVINTEC</h1>
              <p class="lead">Sistema DE Vinculacion del TECMM</p><br>
          </div>
          <hr>
          <p class="lead">Perfil Empresa</p>
          <hr>
           
      </section>
      <section class="cuerpo">
          <div class="container">
              <span style="font-weight:bold;color:#000080;">Informacion de registro&nbsp;</span>
              <hr>
              <?php
             include 'conexion.php';
            $mysql = new Conexion();
            $con= $mysql->_ObtenerConexion();
              $id = $_GET['id'];
              $idusu=$_GET['IdUsuario'];
            if(!$con)
            {
                die('error de conexion de servidor: '.mysql_error());
            }
            
              
              $result0 = mysqli_query($con,"SELECT `User`, `Password` from login where Existe=1 and id_Login=".$idusu);
            if(!$result0)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
            else
            {
                echo "<table width='100%' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center' >
        <td colspan=6 ><font color='black'>Usuario</td>
        <td colspan=6 ><font color='black'>Contraseña</td>
         </tr>";
                while ($row = mysqli_fetch_row($result0))
                {
                   echo "<tr><td colspan=6 >".$row[0]."</td><td colspan=6 >".$row[1]."</td></tr>";
                }
                echo "</table";
            }
              ?>
              <hr></div>
              
              <?php
            $result = mysqli_query($con,"SELECT `Nombre`, `Razon_Social`, `RFC`, `Direccion`, `Magnitud`, `Alcance`, `Giro`, `Mision`, `Tipo_empresa`, `id_login`, `Existe` FROM empresa where Existe=1 and id_empresa=".$id);
            if(!$result)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
              
            else
            {
                echo "<table width='100%'' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center'>
        <td colspan=1><font color='black'>Nombre electronico</td>
        <td colspan=3><font color='black'>Razon Social</td>
        <td colspan=1><font color='black'>RFC</td>
        <td colspan=1><font color='black'>Direccion Fiscal</td>
        <td colspan=1><font color='black'>Magnitud</td>
        <td colspan=3><font color='black'>Alcance</td>
        <td colspan=1><font color='black'>Giro</td>
        <td colspan=1><font color='black'>Mision</td>
        <td colspan=1><font color='black'>Tipo de empresa</td>

         </tr>";
                while ($row = mysqli_fetch_row($result))
                {
                   echo "<tr><td colspan=1>".$row[0]."</td><td colspan=3>".$row[1]."</td><td colspan=1>".$row[2]."</td><td colspan=1>".$row[3]."</td><td colspan=1>".$row[4]."</td><td colspan=3>".$row[5]."</td><td colspan=3>".$row[6]."</td><td colspan=3>".$row[7]."</td></tr>";
                }
                echo "</table";
            }
              ?>
              <div><span style="font-weight:bold;color:#000080;">Trabajadores&nbsp;</span>
              <hr></div>
              
              <?php
            $result = mysqli_query($con,"SELECT `Nombre`, `RFC`, `Correo`, `Puesto`,`Tel` FROM trabajador WHERE id_Empresa=".$id);
            if(!$result)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
              
            else
            {
                echo "<table width='100%'' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center'>
        <td colspan=1><font color='black'>Nombre</td>
        <td colspan=3><font color='black'>RFC</td>
        <td colspan=1><font color='black'>Correo</td>
        <td colspan=1><font color='black'>Puesto</td>
        <td colspan=1><font color='black'>Tel/Cel</td>
         </tr>";
                while ($row = mysqli_fetch_row($result))
                {
                   echo "<tr><td colspan=1>".$row[0]."</td><td colspan=3>".$row[1]."</td><td colspan=1>".$row[2]."</td><td colspan=1>".$row[3]."</td><td colspan=1>".$row[4]."</td></tr>";
                }
                echo "</table";
            }
              ?>
              
               <center>
      <a href="Alumno.php" align="center" >
      <button type="submit" class="btn btn-nuevo">Regresar </button>
      </a>
          </center>
          </div>
      </section> 
      <footer>
            <div class="contenedor">
            </div>           
       </footer>
      <!-- Bootstrap core JavaScript -->
    <script src="../Estilos/dist/js/jquery.js"></script>
    <script src="../Estilos/dist/js/bootstrap.min.js"></script>
    </body>
</html>
