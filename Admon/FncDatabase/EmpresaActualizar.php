<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
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
$CuentaTipo = 1;
$EmpresaIdLogin = $_GET['id'];
$CuentaUser = $_POST['cuenta-user'];
$CuentaPassword = $_POST['cuenta-password'];
$EmpresaNombre = $_POST['empresa-nombre'];
$EmpresaTipoConvenio = $_POST['empresa-tipo-convenio'];
$EmpresaRazonSocial = $_POST['empresa-razon-social'];
$EmpresaRFC = $_POST['empresa-rfc'];
$EmpresaDireccion = $_POST['empresa-direccion'];
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
    $stmtVerificarUsuario->bind_param("s",$CuentaUser);
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
