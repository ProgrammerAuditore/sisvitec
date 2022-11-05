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
if (!isset($_GET['idT']) || empty($_GET['idT'])) {
    //header("Location: ../ConsultaAlumno.php");
    //exit();
}

if (!isset($_GET['idE']) || empty($_GET['idE'])) {
    //header("Location: ../ConsultaAlumno.php");
    //exit();
}

// Crear variables de campos recibidos
$trabajadorId = $_GET['idT'];
$empresaId = $_GET['idE'];
$Existe = 1;

// ***** Iniciar Transición */
$mysqli->begin_transaction();

// Crear consulta
$consultaQ = "DELETE FROM `trabajador`   
WHERE id_Trabajador = ? AND id_Empresa = ? AND Existe = ? ; ";

try {


    // preparar y parametrar
    $stmt = $mysqli->prepare($consultaQ);
    $stmt->bind_param("iii", $trabajadorId, $empresaId, $Existe);
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
