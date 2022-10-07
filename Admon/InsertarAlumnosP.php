<?php 
  	include 'conexion.php';
        $id=$_POST['id'];
        $Alumno=$_POST['Alumno'];
	$mysql = new Conexion();
	$con= $mysql->_ObtenerConexion();
	if ($con->connect_error) {
	    die("Conection failed: " . $con->connect_error);
	} 
	mysqli_query($con,"Start Transaction;");
                            for ($i=0; $i < count($Alumno) ; $i++) 
                            {
                                    $insertarAlu = "INSERT INTO alu_proyect (`id_Alumno`, `id_Proyecto`) VALUES (".$Alumno[$i].",".$id.")";
                                    if ($con->query($insertarAlu) === TRUE) 
                                    {
                                        mysqli_query($con,"Commit;");
                                        header("location:CP.php");
                                    }
                                    else 
                                    {
                                        mysqli_query($con,"ROll Back;");
                                        echo "Error: " . $insertarAlu . "" . mysqli_error($con);
                                    }
                                
                            }
    	
		
	$con->close();
 ?>