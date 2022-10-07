<?php
      session_start();
    include'conexion.php';
    $mysql = new Conexion();
    $mysqli= $mysql->_ObtenerConexion();
    ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Bienvenido a SACEUA</title>
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
              <h1>SACEUA</h1>
              <p class="lead">Sistema Academico de Centro de Estudio Universitario ARKOS</p><br>
          </div>
          <hr>
           <p class="lead">Asignar Alumnos</p>
              <hr>
      </section>
      <section class="jumbotron">
          <div class="container">
                          <?php
              $id = $_GET['id'];
              if(!$mysqli)
            {
                die('error de conexion de servidor:' .mysql_error());
            }
                $result = mysqli_query($mysqli,"SELECT p.Nombre,a.Nombre as idArea,p.Descripcion,e.Nombre as idema,p.Duracion,p.id_Area From proyecto as p 
LEFT JOIN area as a ON p.id_Area = a.id_Area
LEFT JOIN empresa as e on p.id_Empresa=e.id_empresa
Where p.id_Proyecto=".$id);
  if(!$result)
            {
                echo "error de consulta: ".mysqli_error();
                exit();
            }
            else
            {
            $row = mysqli_fetch_row($result);
            }
              ?>
          <form action="InsertarAlumnosP.php" method="POST" role="form" class="form-datos">
              <label class="col-lg-3 control-label">Nombre Proyecto:</label>
              <div class="col-lg-9">
                  <select name="Clave" id="combo1" class="form-control" disabled>
                      <option selected value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option><br>

                      </select><br>
                  </div>
              <label class="col-lg-3 control-label">Area:</label>
              <div class="col-lg-9">
                  <input type="text" value="<?php echo $row[1]; ?>" name="Grupo" placeholder="ingresa nombre de grupo" onKeyUp="this.value=this.value.toUpperCase();" class="form-control" disabled><br>
                  </div>
              <label class="col-lg-3 control-label">Descripcion:</label>
              <input type="hidden" value="<?php echo $id;?>" name="id" class="form">
                  <div class="col-lg-9" >
                      <select name="Periodo" class="form-control" disabled>
                <option selected><?php echo $row[2]; ?>&nbsp;<?php echo $row[2]; ?></option><br>
                </select><br>
                  </div>
                                <label class="col-lg-3 control-label">Nombre Empresa:</label>
              <input type="hidden" value="<?php echo $id;?>" name="id" class="form">
                  <div class="col-lg-9" >
                      <select name="Periodo" class="form-control" disabled>
                <option  selected><?php echo $row[3]; ?>&nbsp;<?php echo $row[3]; ?></option><br>
                </select><br>
                  </div>
              <label class="col-lg-3 control-label">Duracion Proyecto en Semanas:</label>
               <div class="col-lg-9">
                      <select name="Carrera" id="combo1" class="form-control" onchange="gennumcon(this.value);" disabled>
                <option selected value="" ><?php echo $row[4]; ?></option><br>
                  </select>
                  <br>
              </div>
              <br><br>
              <div name="div_dinamico" id="div_dinamico" class="div_dinamico">
                    <?php
                    if(!$mysqli)
                    {
                        die('error de conexion de servidor: '.mysql_error());
                    }
                    $result1 = mysqli_query($mysqli,"SELECT a.id_Alumnos,a.Nombre,a.Num_Control,c.Nombre as Carrera FROM alumnos as a LEFT JOIN carrera as c ON a.id_Carrera= c.id_carrera WHERE id_Area=".$row[5]);   
                    if(!$result1)
                    {
                        echo "error de consulta: ".mysqli_error();
                    }
                    $vas=0;
                        echo "<table border = '1' align = 'center' width='70%' class='table table-bordered table-condensed'><tr align='center' class='table table-hover'>
                        <td><font color='black'>Numero de control</td>
                        <td><font color='black'>Nombre alumno</td>
                        <td><font color='black'>Carrera</td>
                        <td><font color='black'>Asignar</td>
                        </tr>";
                                  while ($row3 = mysqli_fetch_row($result1))
                                      { 
                                         echo "<tr><td>".$row3[2]."</td><td>".$row3[1]."</td><td>".$row3[3]."</td>
                                        <td><input type='checkbox' name='Alumno[]' value='".$row3[0]."' checked></td></tr>"; 
                                      } 

                    
                ?>

               
              </div>
             <input class="btn btn-primary" type="submit" name="enviar" value="Guardar" > 
     </form>
    <script src="../Estilos/dist/js/bootstrap.min.js"></script>
               </div>
      </section>  
  </body>
</html>