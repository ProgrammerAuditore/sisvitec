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

// Listar campos a recibir
// Solo números enteros
$camposHTML = array('id');

// Verificar campos recibidos
foreach ($camposHTML as $key) {

    $_GET[$key] = trim($_GET[$key]);
    $_GET[$key] = strtr($_GET[$key], $words_mex_encode);
    $_GET[$key] = htmlentities($_GET[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
        $mysqli->close();
        exit();
    }
}

// Crear variables de parametros recibidos
$empresaIdLogin = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$Existe = 1;

// ***** Iniciar Transición */
$mysqli->begin_transaction();


try {

    // ***** Eliminar empresa */
    // Crear consulta
    $consultaEliminarEmpresa = "DELETE FROM `empresa`  
    WHERE id_login = ? AND Existe = ? ; ";

    // preparar y parametrar
    $stmtEliminarEmpresa = $mysqli->prepare($consultaEliminarEmpresa);
    $stmtEliminarEmpresa->bind_param("ii", $empresaIdLogin, $Existe);

    // ***** Eliminar cuenta */
    // Crear consulta
    $consultaEliminarCuenta = "DELETE FROM `login`  
    WHERE id_Login = ? AND Existe = ? ; ";

    // preparar y parametrar
    $stmtEliminarCuenta = $mysqli->prepare($consultaEliminarCuenta);
    $stmtEliminarCuenta->bind_param("ii", $empresaIdLogin, $Existe);

    if ($stmtEliminarEmpresa->execute() && $stmtEliminarCuenta->execute()) {
        // Efectuar cambios
        $mysqli->commit();
    } else {
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
$stmtEliminarEmpresa->close();
$stmtEliminarCuenta->close();
