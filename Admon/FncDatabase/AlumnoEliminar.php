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

// Crear variables de parametros recibidos
$idAlumno = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$Existe = 1;

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // Crear consulta
    $consultaQ = "DELETE FROM `alumnos` 
    WHERE id_Login = ? AND Existe = ? ; ";

    // preparar y parametrar
    $stmt = $mysqli->prepare($consultaQ);
    $stmt->bind_param("ii", $idAlumno, $Existe);
    $stmt->execute();


    // Efectuar cambios
    $mysqli->commit();

} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    $mysqli->rollback();
    //print $exception;
    //throw $exception;
    
}

$mysqli->close();
$stmt->close();
