<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}
// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array('nombre');

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
        print "Error en el campo";
        exit();
    }
}

// Crear variables de campos recibidos
$alumnoNombre = $_GET['nombre'];

// Crear consulta
$consultaBuscarAlumno = "SELECT  
alu.Nombre AS AlumnoNombre, 
alu.Num_Control AS AlumnoNumControl, 
alu.Nombre AS AlumnoArea, 
alu.Correo AS AlumnoCorreo, 
car.Nombre AS AlumnoCarrera, 
lng.User AS CuentaUser 
FROM `alumnos` AS alu 
LEFT JOIN `area` AS ar ON ar.id_Area = alu.id_Area    
LEFT JOIN `carrera` AS car ON car.id_carrera = alu.id_Carrera   
LEFT JOIN `login` AS lng ON lng.id_Login = alu.id_Login;";

// ***** Buscar Alumno */
$resultado = $mysqli->query($consultaBuscarAlumno);
$mysqli->close();

$skillData = array();
$fila = 0;
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $fila++;
        $data['numero'] = $fila;
        $data['nombre'] = $row['AlumnoNombre'];
        $data['numero_control'] = $row['AlumnoNumControl'];
        $data['area'] = $row['AlumnoArea'];
        $data['correo'] = $row['AlumnoCorreo'];
        $data['carrera'] = $row['AlumnoCarrera'];
        $data['user'] = $row['CuentaUser'];
        array_push($skillData, $data);
    }
}

echo json_encode($skillData);
