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
$camposHTML = array('id');

// Verificar campos recibidos
// Solo números enteros
foreach ($camposHTML as $key) {

    $_GET[$key] = trim($_GET[$key]);
    $_GET[$key] = htmlentities($_GET[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
        $mysqli->close();
        exit();
    }
}

// Crear variables de parametros recibidos
$idAlumno = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$Existe = 1;

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    //***** Eliminar un alumno */
    // Crear consulta
    $consultaEliminarAlumno = "DELETE FROM `alumnos` 
    WHERE id_Login = ? AND Existe = ? ; ";

    // preparar y parametrar
    $stmtEliminarAlumno = $mysqli->prepare($consultaEliminarAlumno);
    $stmtEliminarAlumno->bind_param("ii", $idAlumno, $Existe);

    //***** Eliminar la cuenta */
    // Crear consulta
    $consultaEliminarCuenta = "DELETE FROM `login` 
    WHERE id_Login = ? AND Existe = ? ; ";

    // preparar y parametrar
    $stmtEliminarCuenta = $mysqli->prepare($consultaEliminarCuenta);
    $stmtEliminarCuenta->bind_param("ii", $idAlumno, $Existe);

    if ($stmtEliminarAlumno->execute() &&  $stmtEliminarCuenta->execute()) {
        // Efectuar cambios
        $mysqli->commit();
    }else {
        // Deshacer cambios
        $mysqli->rollback();   
    }

} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    $mysqli->rollback();
    //print $exception;
    //throw $exception;

}

$mysqli->close();
$stmtEliminarAlumno->close();
$stmtEliminarCuenta->close();
