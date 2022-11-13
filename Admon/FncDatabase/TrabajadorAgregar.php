<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
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
$empresaId = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
$trabajadorNombre = strtr(htmlspecialchars($_POST['trabajador-nombre'], ENT_QUOTES), $words_mex_decode);
$trabajadorRFC = strtr(htmlspecialchars($_POST['trabajador-rfc'], ENT_QUOTES), $words_mex_decode);
$trabajadorCorreo = strtr(htmlspecialchars($_POST['trabajador-correo'], ENT_QUOTES), $words_mex_decode);
$trabajadorTelefono = strtr(htmlspecialchars($_POST['trabajador-telefono'], ENT_QUOTES), $words_mex_decode);
$trabajadorPuesto = strtr(htmlspecialchars($_POST['trabajador-puesto'], ENT_QUOTES), $words_mex_decode);
$Existe = 1;

// Crear consulta
$consultaAgregarTrabajador = "INSERT INTO `trabajador`        
(Nombre, RFC, Correo, Puesto, Tel, id_Empresa, Existe)
VALUES (?,?,?,?,?,?,?) ; ";

$consultaObtenerIdLoginEmpresa = "SELECT 
e.id_login AS EmpresaIdLogin
FROM `empresa` AS e
WHERE e.id_empresa = $empresaId; ";

// Crear consulta
$consultaObtenerIdTrabajador = "SELECT 
MAX(id_Trabajador) AS TrabajadorId 
FROM `trabajador` ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Agregar Trabajador */
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

    // ***** Obtener ID del trabajador creado recientemente */
    $queryObtenerIdTrabajador = $mysqli->query($consultaObtenerIdTrabajador);
    $rowTrabajador = $queryObtenerIdTrabajador->fetch_array();
    $TrabajadorId = $rowTrabajador['TrabajadorId'];

    // ***** Obtener id_Login de la Empresa  */
    $queryObtenerIdLoginEmpresa = $mysqli->query($consultaObtenerIdLoginEmpresa);
    $rowEmpresa = $queryObtenerIdLoginEmpresa->fetch_array();
    $EmpresaIdLogin = $rowEmpresa['EmpresaIdLogin'];

    if ($stmtAgregarTrabajador->execute()) {

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo = "Location:/Admon/ConEmpresa.php";
        $goTo .= "?IdEmpresa=" . $EmpresaIdLogin;
        $goTo .= "&action=success";
        $goTo .= "&title=$title registrado.";

    } else {
        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=$title no actualizado.";
        $goTo .= $againTo;
    }
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
