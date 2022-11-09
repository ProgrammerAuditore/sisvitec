<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
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
$idProyecto = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// ***** Eliminar proyecto */
// Crear consulta
$consultaEliminarProyecto = "DELETE FROM `proyecto`  
WHERE id_Proyecto = ?; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // preparar y parametrar
    $stmtEliminarProyecto = $mysqli->prepare($consultaEliminarProyecto);
    $stmtEliminarProyecto->bind_param("i", $idProyecto);

    if ($stmtEliminarProyecto->execute()) {
    
        // Efectuar cambios
        $mysqli->commit();
    
    }

} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    $mysqli->rollback();
    //print $exception;
    //throw $exception;

}

// Cerrar conexiones y consultas
$mysqli->close();
$stmtEliminarProyecto->close();

// Enviar mensaje
echo "Proyecto eliminado.";
