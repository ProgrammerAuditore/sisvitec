<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
session_start();
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../ConsultaAlumno.php");
    exit();
}
  
// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'user', 'pass', 'NombreA', 'NumeroC',
    'Correo', 'Direccion', 'Area', 'Carrera', 'postActualizarAlumno'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        // Muestra un mensaje de error y 3 seg después
        // se redirige a ConsultaAlumno
        header("refresh:3;url=../EdiAlumno.php?IdUsuario=" . $_GET['id']);
        print "Campos incorrectos, verificar campos";
        exit();
    }
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

// Crear consulta
$consultaQ = "UPDATE `alumnos` SET 
Nombre = ?, Num_Control = ?,  Correo = ?,  
Direccion = ?, id_Area = ?, id_Carrera = ? 
WHERE id_Login = ? AND Existe = 1 ; ";

// preparar y parametrar
$stmt = $mysqli->prepare($consultaQ);
$stmt->bind_param("ssssiii", $NombreA, $NumeroC, $Correo, $Direccion, $Area, $Carrera, $idAlumno);

try {

    // establecer parametros y ejecutar cambios
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $NombreA   = $_POST['NombreA'];
    $NumeroC = $_POST['NumeroC'];
    $Correo = $_POST['Correo'];
    $Direccion = $_POST['Direccion'];
    $Area = 1;
    $Carrera = 1;
    $idAlumno = $_GET['id'];
    $stmt->execute();
    

    // ***** Efectuar cambios */
    $mysqli->commit();
    // En caso de no tener errores
    // Muestra un mensaje exitosa y 3 seg después
    // se redirige a ConsultaAlumno
    header("refresh:3;url=../ConsultaAlumno.php");
    print "Usuario Actualizado exitosamente.";

} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    $mysqli->rollback();
    // En caso de tener error en MYSQL
    // Muestra un mensaje de error y 3 seg después
    // se redirige a ConsultaAlumno
    header("refresh:3;url=../ConsultaAlumno.php");
    print "Usuario no actualizado satisfactoriamente";
    
    print $exception;
    //throw $exception;
}

$mysqli->close();
$stmt->close();
?>

