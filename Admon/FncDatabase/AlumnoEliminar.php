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

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../ConsultaAlumno.php");
    exit();
}
  
// ***** Iniciar Transición */
$mysqli->begin_transaction();

// Crear consulta
$consultaQ = "DELETE FROM `alumnos`  
WHERE id_Login = ? AND Existe = 1 ; ";

// preparar y parametrar
$stmt = $mysqli->prepare($consultaQ);
$stmt->bind_param("i", $idAlumno);

try {

    // establecer parametros y ejecutar cambios
    $idAlumno = $_GET['id'];
    $stmt->execute();
    

    // ***** Efectuar cambios */
    $mysqli->commit();

} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    $mysqli->rollback();
    
    print $exception;
    //throw $exception;
}

$mysqli->close();
$stmt->close();
?>

