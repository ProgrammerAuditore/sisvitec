<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// response para Ajax
$responseAjax = array();
$responseAjax['icon'] = "";
$responseAjax['title'] = "";
$responseAjax['msg'] = "";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}
// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array('idA', 'idP', 'action');

// Verificar campos recibidos
foreach ($camposHTML as $key) {

    $_GET[$key] = trim($_GET[$key]);
    $_GET[$key] = htmlentities($_GET[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
        $responseAjax['icon'] = "danger";
        $responseAjax['title'] = "Alumno no asignado.";
        $responseAjax['msg'] = "Error al recibir parametros GET.";
    }

}

// Crear variables de campos recibidos
$alumnoId = filter_var($_GET['idA'], FILTER_SANITIZE_NUMBER_INT);
$proyectoId = filter_var($_GET['idP'], FILTER_SANITIZE_NUMBER_INT);
$tipoAccion = htmlspecialchars($_GET['action'], ENT_QUOTES);

// Crear consulta
$consultaVerificarAsignacion = "SELECT * FROM 
`alu_proyect` WHERE id_Alumno = $alumnoId ; ";

// Crear consulta 
$consultaDesAsignarAlumno = "DELETE FROM `alu_proyect` 
WHERE id_Alumno = ? AND id_Proyecto = ? ; ";

// Crear consulta
$consultaAsignarAlumno = "INSERT INTO `alu_proyect` 
(id_Alumno, id_Proyecto ) VALUES (?, ?) ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    if ($tipoAccion == "asig") {

        // ***** Verificar asignación */
        // preparar y parametrar
        $sqlVerificarAsignacion = $mysqli->query($consultaVerificarAsignacion);
        $rowAsignacion = $sqlVerificarAsignacion->num_rows;

        // ***** Asignar un alumno a un proyecto */
        // preparar y parametrar
        $stmtAsingarAlumno = $mysqli->prepare($consultaAsignarAlumno);
        $stmtAsingarAlumno->bind_param("ii", $alumnoId, $proyectoId);
        $stmtAsingarAlumno->execute();

        // Verificar si se asigno correctamente
        if ($stmtAsingarAlumno->affected_rows > 0 && $rowAsignacion === 0) {

            // Efectuar cambios
            // En caso de no tener errores
            $mysqli->commit();
            $responseAjax['icon'] = "success";
            $responseAjax['title'] = "Alumno asignado al proyecto.";
        } else {

            // Deshacer cambios
            // En caso de tener errores
            $mysqli->rollback();
            $responseAjax['icon'] = "warning";
            $responseAjax['title'] = "Alumno no asignado.";
            $responseAjax['msg'] = "Posiblemente este asignado en otro proyecto.";
        }
    } else {

        // ***** Eliminar un alumno de un proyecto */
        // preparar y parametrar
        $stmtAsingarAlumno = $mysqli->prepare($consultaDesAsignarAlumno);
        $stmtAsingarAlumno->bind_param("ii", $alumnoId, $proyectoId);
        $stmtAsingarAlumno->execute();

        // Verificar si se asigno correctamente
        if ($stmtAsingarAlumno->affected_rows > 0) {

            // Efectuar cambios
            // En caso de no tener errores
            $mysqli->commit();
            $responseAjax['icon'] = "success";
            $responseAjax['title'] = "Alumno eliminado del proyecto.";
        } else {

            // Deshacer cambios
            // En caso de tener errores
            $mysqli->rollback();
            $responseAjax['icon'] = "danger";
            $responseAjax['title'] = "Error en la consulta.";
            $responseAjax['msg'] = "Es posible que este registro no exista.";
        }
    }
} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    // En caso de tener errores
    $mysqli->rollback();
    $responseAjax['icon'] = "danger";
    $responseAjax['title'] = "Alumno no asignado.";
    $responseAjax['msg'] = "Error en la consulta SQL.";
}

$mysqli->close();
$stmtAsingarAlumno->close();
echo json_encode($responseAjax);
