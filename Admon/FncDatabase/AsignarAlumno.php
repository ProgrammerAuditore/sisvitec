<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

$estado = array();
$estado['icon'] = "";
$estado['title'] = "";
$estado['msg'] = "";

// Verificar la conexion
if ($mysqli->connect_errno) {
    //die("Error en la conexion" . $mysqli->connect_error);
}
// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array('idA', 'idP');

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
        $estado['icon'] = "danger";
        $estado['title'] = "Alumno no asignado.";
        $estado['msg'] = "Error al recibir parametros GET.";
    }
}

// Crear variables de campos recibidos
$alumnoId = $_GET['idA'];
$proyectoId = $_GET['idP'];

// Crear consulta
$consultaAsignarAlumno = "INSERT INTO `alu_proyect` (`id_Alumno`,`id_Proyecto`) VALUES (?, ?); ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtAsingarAlumno = $mysqli->prepare($consultaAsignarAlumno);
    $stmtAsingarAlumno->bind_param("ii", $alumnoId, $proyectoId);
    $stmtAsingarAlumno->execute();

    if ($stmtAsingarAlumno->affected_rows > 0) {

        // ***** Efectuar cambios */
        // En caso de no tener errores
        $mysqli->commit();
        $estado['icon'] = "success";
        $estado['title'] = "Alumno asignado.";

    } else {

        // ***** Deshacer cambios */
        // En caso de tener errores
        $mysqli->rollback();
        $estado['icon'] = "warning";
        $estado['title'] = "Alumno no asignado.";

    }

} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    // En caso de tener errores
    $mysqli->rollback();
    $estado['icon'] = "danger";
    $estado['title'] = "Alumno no asignado.";
    $estado['msg'] = "Error en la consulta SQL.";
}

$mysqli->close();
$stmtAsingarAlumno->close();
echo json_encode($estado);
