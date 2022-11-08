<?php
    session_start(); 
    include '../conexion.php';
    $mysql = new Conexion();
    $conn= $mysql->_ObtenerConexion();
    $id = $_GET['id'];
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}  
	$sql = "SELECT id_login from empresa where id_empresa=".$id;
	$idusu=mysqli_query($conn,$sql);
	$row = mysqli_fetch_row($idusu);
	if (count($row)>0) 
	{
	    $eliminausuario = 'UPDATE login set Existe=0 where id_Login='.$row[0];
		if ($conn->query($eliminausuario) === TRUE) {
			 $EliminarEmp = 'UPDATE empresa set Existe=0 where id_empresa='.$id;
			if ($conn->query($EliminarEmp) === TRUE) {
					header('location:ConsultarEmpresa.php');
			} else {
				    echo "Error: " .$EliminarEmp. "<br>" . $conn->error;
			}
				
		} else {
			    echo "Error: " .$eliminausuario. "<br>" . $conn->error;
		}
	} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
	 
?>
