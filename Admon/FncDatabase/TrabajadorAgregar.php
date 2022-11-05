<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Personalizar Notificación
$title = "Trabajador";
$goTo = "Location:/Admon/ConsultarEmpresas.php";
$againTo = "<br/><hr><a href=/Admon/AgrTrabajador.php?IdEmpresa={$_GET['id']}>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro ID de empresa */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // En caso de recibir campos incorrectos
    $goTo .= "?action=error";
    $goTo .= "&title=$title no registrado.";
    $goTo .= "&msg=Empresa no identificado.";
    $goTo .= $againTo;
    $mysqli->close();
    header($goTo);
    exit();
}


// Listar campos a recibir desde la pagina Editar Trabajador
$camposHTML = array(
    'trabajador-nombre',
    'trabajador-rfc',
    'trabajador-correo',
    'trabajador-telefono',
    'trabajador-puesto',
    'postAgregarTrabajador'
);

// Crear variables de campos recibidos
$empresaId = $_GET['id'];
$trabajadorNombre = $_POST['trabajador-nombre'];
$trabajadorRFC = $_POST['trabajador-rfc'];
$trabajadorCorreo = $_POST['trabajador-correo'];
$trabajadorTelefono = $_POST['trabajador-telefono'];
$trabajadorPuesto = $_POST['trabajador-puesto'];
$Existe = 1;

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

// ***** Iniciar Transición */
$mysqli->begin_transaction();

// Crear consulta
$consultaAgregarTrabajador = "INSERT INTO `trabajador`        
(Nombre, RFC, Correo, Puesto, Tel, id_Empresa, Existe)
VALUES (?,?,?,?,?,?,?) ; ";

try {

    // ***** Actualizar Trabajador */
    // preparar y parametrar
    $stmtAgregarTrabajador = $mysqli->prepare($consultaAgregarTrabajador);
    $stmtAgregarTrabajador->bind_param(
        "sssssii",
        $trabajadorNombre,
        $trabajadorRFC,
        $trabajadorCorreo,
        $trabajadorPuesto,
        $trabajadorTelefono,
        $empresaId,
        $Existe
    );
    $stmtAgregarTrabajador->execute();

    // Efectuar cambios
    // En caso de no tener errores
    $mysqli->commit();
    $goTo .= "?action=success";
    $goTo .= "&title=$title registrado.";

} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    // En caso de un error en la base de datos
    $mysqli->rollback();
    $goTo .= "?action=error";
    $goTo .= "&title=$title no registrado.";
    $goTo .= $againTo;
    //print $exception;
    //throw $exception;

}

$mysqli->close();
$stmtAgregarTrabajador->close();
header($goTo);
