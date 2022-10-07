<?php
     session_start();
	 include 'conexion.php';
     include 'R.php';
	$mysql = new Conexion();
	$conexion= $mysql->_ObtenerConexion();
    $re = new Redi();
 if(!$conexion)
        {
            die('error de conexion de servidor:'.mysql_error());
        }
	$users = $_POST["user"];
	$pass = $_POST['password'];
	$tipo = $_POST['tipo'];


	$consulta="Select * from login where User='".$users."' and Password='".$pass."' and tipo=".$tipo;
	$resultado=mysqli_query($conexion,$consulta);
	$filas=mysqli_fetch_row($resultado);


    $consulta2="Select * from alumnos where Id_Login=".$filas[0]." and Existe=1";
	$resultado2=mysqli_query($conexion,$consulta2);
	$filas2=mysqli_fetch_row($resultado2);

    $consulta1="Select * from empresa where id_login='".$filas[0]."' and Existe=1";
	$resultado1=mysqli_query($conexion,$consulta1);
	$filas1=mysqli_fetch_row($resultado1);

    $_SESSION['idE'] = $filas1[0];
    $_SESSION['id'] = $filas2[0];
    $iddoc = $_SESSION['id'];
    $ida = $_SESSION['ida'];
    $idce = $_SESSION['idce'];
        if ($filas>0)
        {
            $_SESSION['entro'] = "on";
            if($tipo==1)
            {
                $re->_redic('Alumno/PAlumno.php');
            }
            if($tipo==3)
            {
                
                $re->_redic('Admon/PAdmon.php');
            }
            if($tipo==2)
            {
                $re->_redic('Empresa/PEmpresa.php');
            } 
        }
    else
    {
        header('location:popup.php');
	}
?>