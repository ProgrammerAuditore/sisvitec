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

//****  Verificar que existe el parametro id */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location:/Empresa/ConsultarProyecto.php");
    exit();
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

// ***** Eliminar proyecto */
// Crear consulta
$consultaQ = "DELETE FROM `proyecto`  
WHERE id_Proyecto = ?; ";

try {

    // preparar y parametrar
    $stmt = $mysqli->prepare($consultaQ);
    $stmt->bind_param("i", $id);

    // establecer parametros y ejecutar cambios
    $id = $_GET['id'];
    $stmt->execute();

    // ***** Efectuar cambios */
    $mysqli->commit();

} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    $mysqli->rollback();
    //print $exception;
    //throw $exception;

}

// Cerrar conexiones y consultas
$mysqli->close();
$stmt->close();

// Enviar mensaje
echo "Proyecto eliminado.";