<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Proyecto";
$goTo = "Location:/Empresa/ConsultarProyecto.php";
$againTo = "<br/><hr><a href=/Empresa/EdiProyecto.php?IdProyecto={$_GET['id']}>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // En caso de recibir campos incorrectos
    $goTo .= "?action=error&msg=Usuario no valido&msg=Este usuario no existe.";
    $mysqli->close();
    header($goTo);
    exit();
}

// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'proyecto-nombre',
    'proyecto-area',
    'proyecto-descripcion',
    'proyecto-obj-general',
    'proyecto-obj-especifico',
    'proyecto-duracion',
    'proyecto-tipo',
    'postActualizarProyecto'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=$title no actualizado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// Crear variables de campos recibidos
$idProyecto = $_GET['id'];
$ProyectoNombre = $_POST["proyecto-nombre"];
$ProyectoArea = $_POST["proyecto-area"];
$ProyectoDescripcion = $_POST["proyecto-descripcion"];
$ProyectoObjGeneral = $_POST["proyecto-obj-general"];
$ProyectoObjEspecifico = $_POST["proyecto-obj-especifico"];
$ProyectoDuracion = $_POST["proyecto-duracion"];
$ProyectoTipo = $_POST["proyecto-tipo"];

// Crear consulta
$consultaVerificarProyecto = "SELECT * FROM `proyecto` WHERE id_Proyecto = ? ; ";

// Crear consulta
$consultaActualizarProyecto = "UPDATE `proyecto` SET  
Nombre = ?, Descripcion = ?, Objetivo_General = ?, 
Objetivo_Espesifico = ?, Tipo_Proyect = ?,
id_Area = ?, Duracion = ?  WHERE id_Proyecto = ? ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Actualizar Proyecto */
    // preparar y parametrar
    $stmtActualizarProyecto = $mysqli->prepare($consultaActualizarProyecto);
    $stmtActualizarProyecto->bind_param(
        "sssssiii",
        $ProyectoNombre,
        $ProyectoDescripcion,
        $ProyectoObjGeneral,
        $ProyectoObjEspecifico,
        $ProyectoTipo,
        $ProyectoArea,
        $ProyectoDuracion,
        $idProyecto,
    );
    $stmtActualizarProyecto->execute();

    // ***** Verificar Proyecto */
    // preparar y parametrar
    $stmtVerificarProyecto = $mysqli->prepare($consultaVerificarProyecto);
    $stmtVerificarProyecto->bind_param(
        "i",
        $idProyecto
    );
    $stmtVerificarProyecto->execute();

    $stmtVerificarProyecto->store_result();
    $rowProyecto = $stmtVerificarProyecto->num_rows;

    if ($rowProyecto > 1) {

        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=$title no actualizado.";
        $goTo .= "Probablemente el proyecto no existe.";
        $goTo .= $againTo;
    } else {

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo .= "?action=success";
        $goTo .= "&title=$title actualizado.";
    }
    
} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    $mysqli->rollback();
    // En caso de tener error en MYSQL
    $goTo .= "?action=error";
    $goTo .= "&title=$title no actualizado.";
    $goTo .= $againTo;
    //print $exception;
    //throwLogin $exception;
}

$mysqli->close();
$stmtActualizarProyecto->close();
$stmtVerificarProyecto->close();
header($goTo);
