<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Empresa";
$goTo = "Location:/Admon/ConsultarEmpresas.php";
$againTo = "<br/><hr><a href=/Admon/EdiEmpresa.php?IdEmpresa={$_GET['id']}>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro ID de empresa */
// id es la cuenta id_login
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../ConsultarEmpresa.php");
    exit();
}


// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'cuenta-user',
    'cuenta-password',
    'empresa-nombre',
    'empresa-tipo-convenio',
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
        $goTo .= "&title=$title no registrado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// Crear variables de campos recibidos
$CuentaTipo = 2;
$EmpresaIdLogin = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);
$CuentaUser = strtr(htmlspecialchars($_POST['cuenta-user'], ENT_QUOTES), $words_mex_decode);
$CuentaPassword = strtr(htmlspecialchars($_POST['cuenta-password'], ENT_QUOTES), $words_mex_decode);
$EmpresaNombre = strtr(htmlspecialchars($_POST['empresa-nombre'], ENT_QUOTES), $words_mex_decode);
$EmpresaTipoConvenio = strtr(htmlspecialchars($_POST['empresa-tipo-convenio'], ENT_QUOTES), $words_mex_decode);
$EmpresaRazonSocial = strtr(htmlspecialchars($_POST['empresa-razon-social'], ENT_QUOTES), $words_mex_decode);
$EmpresaRFC = strtr(htmlspecialchars($_POST['empresa-rfc'], ENT_QUOTES), $words_mex_decode);
$EmpresaDireccion = strtr(htmlspecialchars($_POST['empresa-direccion'], ENT_QUOTES), $words_mex_decode);
$Existe = 1;

$consultaVerificarUsuario = "SELECT * FROM `login` WHERE User = ? ; ";

// Crear consulta
$consultaActualizarUsuario = "UPDATE `login` SET   
tipo = ?, User = ?, `Password` = ?   
WHERE id_Login = ? AND Existe = ?; ";

// Crear consulta
$consultaActualizarEmpresa = "UPDATE empresa SET    
Nombre = ?, Razon_Social  = ?,   
RFC  = ?, tipo_empresa  = ?, Direccion  = ?  
WHERE id_login = ? AND Existe = ? ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtActualizarUsuario = $mysqli->prepare($consultaActualizarUsuario);
    $stmtActualizarUsuario->bind_param(
        "issii",
        $CuentaTipo,
        $CuentaUser,
        $CuentaPassword,
        $EmpresaIdLogin,
        $Existe
    );
    $stmtActualizarUsuario->execute();

    // ***** Actualizar Empresa */
    // preparar y parametrar
    $stmtAgregarEmpresa = $mysqli->prepare($consultaActualizarEmpresa);
    $stmtAgregarEmpresa->bind_param(
        "sssssii",
        $EmpresaNombre,
        $EmpresaRazonSocial,
        $EmpresaRFC,
        $EmpresaTipoConvenio,
        $EmpresaDireccion,
        $EmpresaIdLogin,
        $Existe
    );
    $stmtAgregarEmpresa->execute();

    /// ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s", $CuentaUser);
    $stmtVerificarUsuario->execute();

    $stmtVerificarUsuario->store_result();
    $rowUsuario = $stmtVerificarUsuario->num_rows;

    if ($rowUsuario > 1) {

        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=$title no actualizado.";
        $goTo .= "&msg=El usuario <mark>$user</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
        $goTo .= $againTo;
    } else {

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo = "Location:/Admon/ConEmpresa.php";
        $goTo .= "?IdEmpresa=" . $EmpresaIdLogin;
        $goTo .= "&action=success";
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
