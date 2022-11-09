<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Proyecto";
$goTo = "Location:/Empresa/ConsultarProyecto.php";
$againTo = "<br/><hr><a href=/Empresa/AgrProyecto.php>Volver a intentarlo.</a>";

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
    'postAgregarProyecto'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=$title no registrado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// Crear variables de campos recibidos
$ProyectoNombre = $_POST["proyecto-nombre"];
$ProyectoArea = $_POST["proyecto-area"];
$ProyectoDescripcion = $_POST["proyecto-descripcion"];
$ProyectoIdEmpresa = $_GET["id"];
$ProyectoObjGeneral = $_POST["proyecto-obj-general"];
$ProyectoObjEspecifico = $_POST["proyecto-obj-especifico"];
$ProyectoDuracion = $_POST["proyecto-duracion"];
$ProyectoTipo = $_POST["proyecto-tipo"];
$idEmpresa = $_GET['id']; // <==== ID empresa
$Exsite = 1; // <==== Existente

// Crear consulta
$consultaVerificarUsuario = "SELECT * FROM `empresa` 
WHERE id_empresa = ? AND Existe = ? ; ";

// Crear consulta
$consultaAgregarProyecto = "INSERT INTO `proyecto` 
( Nombre, id_Area, Descripcion, id_Empresa, 
Objetivo_General, Objetivo_Espesifico, Duracion, Tipo_Proyect ) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Registrar Proyecto */
    // preparar y parametrar
    $stmtAgregarProyecto = $mysqli->prepare($consultaAgregarProyecto);
    $stmtAgregarProyecto->bind_param(
        "sisissis",
        $ProyectoNombre,
        $ProyectoArea,
        $ProyectoDescripcion,
        $ProyectoIdEmpresa,
        $ProyectoObjGeneral,
        $ProyectoObjEspecifico,
        $ProyectoDuracion,
        $ProyectoTipo
    );
    $stmtAgregarProyecto->execute();

    // ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("ii", $idEmpresa, $Existe);
    $stmtVerificarUsuario->execute();

    $stmtVerificarUsuario->store_result();
    $rowUsuario = $stmtVerificarUsuario->num_rows;

    if ($rowUsuario > 1) {

        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=$title no registrado.";
        $goTo .= "&msg=El usuario <mark>$user</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
        $goTo .= $againTo;
    } else {

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo .= "?action=success";
        $goTo .= "&title=$title registrado.";
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
$stmtAgregarProyecto->close();
$stmtVerificarUsuario->close();
header($goTo);
