<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Empresa";
$goTo = "Location:/Empresa/ConPerfil.php";
$againTo = "<br/><hr><a href=/Empresa/EdiPerfil.php>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro ID de empresa */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../ConsultarEmpresa.php");
    exit();
}


// Listar campos a recibir desde la pagina Editar Perfil
$camposHTML = array(
    'cuenta-usuario',
    'empresa-nombre', 
    'empresa-convenio-tipo',
    'empresa-razon-social', 
    'empresa-rfc', 
    'empresa-direccion',
    'postActualizarEmpresa'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {

    $_POST[$key] = trim($_POST[$key]);
    $_POST[$key] = strtr($_POST[$key], $words_mex_encode);
    $_POST[$key] = htmlentities($_POST[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=$title no actualizado.";
        $goTo .= "&msg=Verifique que los campos $key sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// Crear variables de campos recibidos
$empresaId = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
$cuentaUsuario = strtr(htmlspecialchars($_POST['cuenta-usuario'], ENT_QUOTES), $words_mex_decode);
$empresaNombre   = strtr(htmlspecialchars($_POST['empresa-nombre'], ENT_QUOTES), $words_mex_decode);
$empresaRazonSocial = strtr(htmlspecialchars($_POST['empresa-razon-social'], ENT_QUOTES), $words_mex_decode);
$empresaRFC = strtr(htmlspecialchars($_POST['empresa-rfc'], ENT_QUOTES), $words_mex_decode);
$empresaConvenioTipo = strtr(htmlspecialchars($_POST['empresa-convenio-tipo'], ENT_QUOTES), $words_mex_decode);
$empresaDireccion = strtr(htmlspecialchars($_POST['empresa-direccion'], ENT_QUOTES), $words_mex_decode);
$Existe = 1;

// Crear Consulta
$consultaVerificarUsuario = "SELECT * FROM `login` WHERE User = ? ; ";

// Crear consulta
$consultaActualizarEmpresa = "UPDATE empresa SET    
Nombre = ?, Razon_Social  = ?,   
RFC  = ?, tipo_empresa  = ?, Direccion  = ?  
WHERE id_empresa = ? AND Existe = ? ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Actualizar Empresa */
    // preparar y parametrar
    $stmtAgregarEmpresa = $mysqli->prepare($consultaActualizarEmpresa);
    $stmtAgregarEmpresa->bind_param(
        "sssssii",
        $empresaNombre,
        $empresaRazonSocial,
        $empresaRFC,
        $empresaConvenioTipo,
        $empresaDireccion,
        $empresaId,
        $Existe
    );
    $stmtAgregarEmpresa->execute();

    /// ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s", $cuentaUsuario);
    $stmtVerificarUsuario->execute();

    $stmtVerificarUsuario->store_result();
    $rowUsuario = $stmtVerificarUsuario->num_rows;

    if ($rowUsuario > 1) {

        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=$title no actualizado.";
        $goTo .= "&msg=El usuario <mark>$cuentaUsuario</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
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
    // En caso de un error en la base de datos
    $mysqli->rollback();
    $goTo .= "?action=error";
    $goTo .= "&title=$title no actualizado.";
    $goTo .= $againTo;
    //print $exception;
    //throw $exception;

}

$mysqli->close();
$stmtAgregarEmpresa->close();
$stmtVerificarUsuario->close();
header($goTo);
