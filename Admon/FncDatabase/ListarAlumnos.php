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
$camposHTML = array('lista', 'idP');

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    
    $_GET[$key] = trim($_GET[$key]);
    $_GET[$key] = strtr($_GET[$key], $words_mex_encode);
    $_GET[$key] = htmlentities($_GET[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
        print "Error en el campo";
        exit();
    }
}

// Crear variables de campos recibidos
$litaTipo = strtr(htmlspecialchars($_GET['lista'], ENT_QUOTES), $words_mex_decode);
$proyectoId = filter_var(trim($_GET['idP']), FILTER_SANITIZE_NUMBER_INT);

// Crear consulta
$consultaListarAlumnosNoAsignados = "SELECT
alu.id_Alumnos AS AlumnoId,
alu.Nombre AS AlumnoNombre, 
alu.Num_Control AS AlumnoNumControl, 
alu.Nombre AS AlumnoArea, 
alu.Correo AS AlumnoCorreo, 
car.Nombre AS AlumnoCarrera, 
lng.User AS CuentaUser 
FROM `alumnos` AS alu
LEFT JOIN `area` AS ar ON ar.id_Area = alu.id_Area    
LEFT JOIN `carrera` AS car ON car.id_carrera = alu.id_Carrera   
LEFT JOIN `login` AS lng ON lng.id_Login = alu.id_Login
WHERE NOT EXISTS 
(SELECT * FROM `alu_proyect` AS t2 
WHERE alu.id_Alumnos = t2.id_Alumno);";

$consultaListarAlumnosAsignados = "SELECT
alu.id_Alumnos AS AlumnoId,
alu.Nombre AS AlumnoNombre, 
alu.Num_Control AS AlumnoNumControl, 
alu.Nombre AS AlumnoArea, 
alu.Correo AS AlumnoCorreo, 
car.Nombre AS AlumnoCarrera, 
lng.User AS CuentaUser 
FROM `alumnos` AS alu
LEFT JOIN `area` AS ar ON ar.id_Area = alu.id_Area    
LEFT JOIN `carrera` AS car ON car.id_carrera = alu.id_Carrera   
LEFT JOIN `login` AS lng ON lng.id_Login = alu.id_Login 
LEFT JOIN `alu_proyect` AS asig ON asig.id_Alumno = alu.id_Alumnos
WHERE asig.id_Alumno = alu.id_Alumnos AND asig.id_Proyecto = $proyectoId;";

// Seleccionar consulta
$consulta = NULL;

switch ($litaTipo) {
    case "asig":
        $consulta = $consultaListarAlumnosAsignados;
        break;
    case "noasig":
        $consulta = $consultaListarAlumnosNoAsignados;
        break;
    default:
        $consulta = $consultaListarAlumnosNoAsignados;
}

// ***** Buscar Alumno */
$resultado = $mysqli->query($consulta);
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
        $data['id'] = $row['AlumnoId'];
        array_push($skillData, $data);
    }
}

echo json_encode($skillData);
