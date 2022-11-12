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

    $_POST[$key] = trim($_POST[$key]);
    $_POST[$key] = strtr($_POST[$key], $words_mex_encode);
    $_POST[$key] = htmlentities($_POST[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

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
$idEmpresa = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT); // <==== ID empresa
$ProyectoNombre = strtr(htmlspecialchars($_POST["proyecto-nombre"], ENT_QUOTES), $words_mex_decode);
$ProyectoDescripcion = strtr(htmlspecialchars($_POST["proyecto-descripcion"], ENT_QUOTES), $words_mex_decode);
$ProyectoArea = filter_var(trim($_POST["proyecto-area"]), FILTER_SANITIZE_NUMBER_INT);
$ProyectoObjGeneral = strtr(htmlspecialchars($_POST["proyecto-obj-general"], ENT_QUOTES), $words_mex_decode);
$ProyectoObjEspecifico = strtr(htmlspecialchars($_POST["proyecto-obj-especifico"], ENT_QUOTES), $words_mex_decode);
$ProyectoDuracion = filter_var(trim($_POST["proyecto-duracion"]), FILTER_SANITIZE_NUMBER_INT);
$ProyectoTipo = strtr(htmlspecialchars($_POST["proyecto-tipo"], ENT_QUOTES), $words_mex_decode);
$Exsite = 1; // <==== Existente

// Crear consulta
$consultaVerificarUsuario = "SELECT * FROM `empresa` 
WHERE id_empresa = ? AND Existe = ? ; ";

// Crear consulta
$consultaObtenerIdProyecto = "SELECT 
MAX(id_Proyecto) AS max_proyecto 
FROM `proyecto` ; ";

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
        $idEmpresa,
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

        // ***** Obtener ID Login de la proyecto nuevo */
        $queryObtenerIdProyecto = $mysqli->query($consultaObtenerIdProyecto);
        $rowProyecto = $queryObtenerIdProyecto->fetch_array();

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo = "Location:/Empresa/ConProyecto.php";
        $goTo .= "?IdProyecto=" . $rowProyecto['max_proyecto'];
        $goTo .= "&action=success";
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
