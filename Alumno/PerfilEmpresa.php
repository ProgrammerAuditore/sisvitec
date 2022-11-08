<?php
        session_start();
      ?>
<!DOCTYPE html>
<html lang="en">
    <head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
	
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SisVInTEc-Empresa</title>
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
      <section class="jumbotron">
          <div class="container">
              <h1>Información De Empresa</h1>
              <p class="lead">SisVinTec</p><br>
          </div>
          <hr>
          <p class="lead">Perfil De Empresa</p>
          <hr>
           
      </section>
      <section class="cuerpo">
          <div class="container">
              <span style="font-weight:bold;color:#000080;">Información de registro&nbsp;</span>
              <hr>
              <?php
             include '../conexion.php';
            $mysql = new Conexion();
            $con= $mysql->_ObtenerConexion();
              $id = $_GET['id'];
              $idusu=$_GET['IdUsuario'];
            if(!$con)
            {
                die('error de conexion de servidor: '.mysql_error());
            }
            
              
              $result0 = mysqli_query($con,"SELECT `Usuario`, `Clave` from tblusuario where EliminarL=1 and IdUsuario=".$idusu);
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
              <div><span style="font-weight:bold;color:#000080;">Datos escolares&nbsp;</span>
              <hr></div>
              
              <?php
            $result = mysqli_query($con,"SELECT `Correo`, `IdCarrera`, `status`, `TipoEst`, `numcontrol` ,`Turno`,`Periodo` from tblalumno where EliminarL=1 and IdAlumno=".$id);
              $cuatri="select MAX(m.IdCuatrimestre),MAX(m.IdPeriodo) from tblalumnosperiodo AS m LEFT JOIN tblalumno as c ON m.IdAlumno=c.IdAlumno LEFT JOIN tblcuatrimestre AS d on m.IdCuatrimestre=d.IdCuatrimestre where c.status='Activo' and m.IdAlumno=".$id;
              $nomcuatri=mysqli_query($con,$cuatri);
             $cuatrimestre = mysqli_fetch_row($nomcuatri);
            if(!$result)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
              
            else
            {
                echo "<table width='100%'' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center'>
        <td colspan=1><font color='black'>Correo electronico</td>
        <td colspan=3><font color='black'>Carrera</td>
        <td colspan=1><font color='black'>Status</td>
        <td colspan=1><font color='black'>Sistema de estudio</td>
        <td colspan=1><font color='black'>Cuatrimestre</td>
        <td colspan=1><font color='black'>numero de control</td>
        <td colspan=1><font color='black'>Turno</td>
        <td colspan=3><font color='black'>Periodo</td>
         </tr>";
                while ($row = mysqli_fetch_row($result))
                {
                   echo "<tr><td colspan=1>".$row[0]."</td><td colspan=3>".$row[1]."</td><td colspan=1>".$row[2]."</td><td colspan=1>".$row[3]."</td><td colspan=1>".$cuatrimestre[0]."</td><td colspan=1>".$row[4]."</td><td colspan=3>".$row[5]."</td><td colspan=1>".$cuatrimestre[1]."</td></tr>";
                }
                echo "</table";
            }
              ?>
              <div><span style="font-weight:bold;color:#000080;">Domiclio&nbsp;</span>
              <hr></div>
              
              <?php
            $result = mysqli_query($con,"SELECT `calle`, `numint`, `numext`, `col`, `cp` from tbldomicilio where idalu=".$id);
            if(!$result)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
              
            else
            {
                echo "<table width='100%'' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center'>
        <td colspan=1><font color='black'>Calle</td>
        <td colspan=3><font color='black'>Numero interior</td>
        <td colspan=1><font color='black'>Numero exterior</td>
        <td colspan=1><font color='black'>Colonia</td>
        <td colspan=1><font color='black'>Codigo postal</td>
         </tr>";
                while ($row = mysqli_fetch_row($result))
                {
                   echo "<tr><td colspan=1>".$row[0]."</td><td colspan=3>".$row[1]."</td><td colspan=1>".$row[2]."</td><td colspan=1>".$row[3]."</td><td colspan=1>".$row[4]."</td></tr>";
                }
                echo "</table";
            }
              ?>
               <div> <span style="font-weight:bold;color:#000080;">Datos del personales&nbsp;</span>
              <hr></div>
              <?php
              $result1 = mysqli_query($con,"SELECT  `NombreAlu`, `TelefonoPersonal`, `Cel`, `Sexo`, `correo` ,`LugarNacimiento`, `FechaNacimiento`, `EmpresaTrabajo`, `EscuelaProcedencia`, `Ciudad`, `Estado`, `Promedio` from tblalumno where EliminarL=1 and IdAlumno=".$id);
            if(!$result1)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
            else
            {
                
                echo "<table width='100%'' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center'>
        <td><font color='black'>Nombre</td>
        <td><font color='black'>Teléfono personal</td>
        <td><font color='black'>Celular</td>
        <td><font color='black'>Sexo</td>
        <td><font color='black'>Correo</td>
        <td><font color='black'>Lugar de nacimiento</td>
        <td><font color='black'>Fecha de nacimiento</td>
        <td><font color='black'>Empresa donde trabaja</td>
        <td><font color='black'>Escuela de procedencia</td>
        <td><font color='black'>Ciudad</td>
        <td><font color='black'>Estado</td>
        <td><font color='black'>Promedio</td>
         </tr>";
                while ($row = mysqli_fetch_row($result1))
                {
                   echo "<tr><td>".$row[0]."</td><td>".$row[2]."</td><td>".$row[1]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td><td>".$row[9]."</td><td>".$row[10]."</td><td>".$row[11]."</td></tr>";
                }
                echo "</table";
            }
              ?>
              <div> <span style="font-weight:bold;color:#000080;">Datos generales&nbsp;</span>
              <hr></div>
              <?php
              
              $result2 = mysqli_query($con,"SELECT  `Aficiones`, `DeportesPractica`, `PadecimientoEnfermedad`, `ProblemaFisico`, `TratamientoMedico`, `TipoSangre`, `Sexo` from tblalumno where EliminarL=1 and IdAlumno=".$id);
            if(!$result2)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
            else
            {
                  
                echo "<table width='100%'' border='1px' align='center' class='table table-striped table-bordered table-condensed'>    
        <tr align='center'>
        <td><font color='black'>Aficiones</td>
        <td><font color='black'>Deportes</td>
        <td><font color='black'>Padecimiento de enfermedad</td>
        <td><font color='black'>Problema fisico</td>
        <td><font color='black'>Tratamiento medico</td>
        <td><font color='black'>Tipo de sangre</td>
        <td><font color='black'>Sexo</td>
         </tr>";
                while ($row = mysqli_fetch_row($result2))
                {
                   echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td></tr>";
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
                <p >Copyright &copy; BCB</p>
            </div>           
       </footer>
      <!-- Bootstrap core JavaScript -->
    <script src="../Estilos/dist/js/jquery.js"></script>
    <script src="../Estilos/dist/js/bootstrap.min.js"></script>
    </body>
</html>
