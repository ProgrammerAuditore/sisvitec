<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Empresa";
$goTo = "Location:/Admon/ConsultarEmpresas.php";
$againTo = "<br/><hr><a href=/Admon/AgrEmpresa.php>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'cuenta-usuario', 
    'cuenta-password', 
    'empresa-nombre', 
    'empresa-razon-social', 
    'empresa-rfc', 
    'empresa-tipo-sat',
    'empresa-direccion',
    'empresa-magnitud',
    'empresa-alcance',
    'empresa-giro',
    'empresa-mision',
    'empresa-tipo-convenio',
    'representante-nombre',
    'representante-rfc',
    'representante-correo',
    'representante-telefono',
    'recursoshumanos-nombre',
    'recursoshumanos-rfc',
    'recursoshumanos-correo',
    'recursoshumanos-telefono',
    'postAgregarEmpresa'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=$title no agregado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

$consultaVerificarUsuario = "SELECT * FROM `login` WHERE User = ? ; ";

// Obtener Loggin ID
$consultaObtenerIdLogin = "SELECT MAX(id_Login) AS max_login FROM `login` ; ";

// Crear consulta
$consultaCrearUsuario = "INSERT INTO `login`  
(`tipo`, `User`, `Password`, `Existe`) 
VALUES (?,?,?,?) ; ";

// Crear consulta
$consultaAgregarEmpresa = "INSERT INTO empresa   
(Nombre, Razon_Social,  RFC, id_tipo, Direccion, Magnitud, Alcance, Giro,
Mision, Tipo_empresa, id_login, Existe )  
VALUES (?,?,?,?,?,?,?,?,?,?,?,?); ";

try {

    // ***** Registrar una nuevo Cuenta */
    // preparar y parametrar
    $stmtCrearUsuario = $mysqli->prepare($consultaCrearUsuario);
    $stmtCrearUsuario->bind_param("issi", $CuentaTipo, $CuentaUsuario, $CuentaPassword, $Existe);

    // establecer parametros y ejecutar cambios
    $CuentaTipo = 2; // <=== Tipo empresa
    $CuentaUsuario = $_POST['cuenta-usuario'];
    $CuentaPassword = $_POST['cuenta-password'];
    $Existe = 1;
    $stmtCrearUsuario->execute();

    // ***** Obtener ID Login de la cuenta nueva */
    $queryObtenerIdLogin = $mysqli->query($consultaObtenerIdLogin);
    $rowLogin = $queryObtenerIdLogin->fetch_array();

    // ***** Registrar Alumno */
    // preparar y parametrar
    $stmtAgregarEmpresa = $mysqli->prepare($consultaAgregarEmpresa);
    $stmtAgregarEmpresa->bind_param(
        "sssissssssii",
        $EmpresaNombre,
        $EmpresaRazonSocial,
        $EmpresaRFC,
        $EmpresaTipoSAT,
        $EmpresaDireccion,
        $EmpresaMagnitud,
        $EmpresaAlcance,
        $EmpresaGiro,
        $EmpresaMision,
        $EmpresaTipoConvenio,
        $CuentaIdLogin,
        $Existe
    );

    // establecer parametros y ejecutar cambios
    $EmpresaNombre   = $_POST['empresa-nombre'];
    $EmpresaRazonSocial = $_POST['empresa-razon-social'];
    $EmpresaRFC = $_POST['empresa-rfc'];
    $EmpresaTipoSAT = intval($_POST['empresa-tipo-sat']);
    $EmpresaDireccion = $_POST['empresa-direccion'];
    $EmpresaMagnitud = $_POST['empresa-magnitud'];
    $EmpresaAlcance = $_POST['empresa-alcance'];
    $EmpresaGiro = $_POST['empresa-giro'];
    $EmpresaMision = $_POST['empresa-mision'];
    $EmpresaTipoConvenio = $_POST['empresa-tipo-convenio'];
    $CuentaIdLogin = $rowLogin['max_login'];
    $Existe = 1;
    $stmtAgregarEmpresa->execute();

    // ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s", $user);

    // establecer parametros y ejecutar cambios
    $user = $_POST['user'];
    $stmtVerificarUsuario->execute();

    $stmtVerificarUsuario->store_result();
    $rowUsuario = $stmtVerificarUsuario->num_rows;

    if ($rowUsuario > 1) {

        // ***** Deshacer cambios */
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=$title no agregado.";
        $goTo .= "&msg=El usuario <mark>$user</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
        $goTo .= $againTo;

    } else {

        // ***** Efectuar cambios */
        // En caso de no tener errores
        $mysqli->commit();
        $goTo .= "?action=success";
        $goTo .= "&title=$title agregado.";
    }
} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    $mysqli->rollback();
    // En caso de tener error en MYSQL
    $goTo .= "?action=error";
    $goTo .= "&title=$title no actualizado.";
    $goTo .= $againTo;
    //print $exception;
    //throwLogin $exception;
}

$mysqli->close();
$stmtCrearUsuario->close();
$stmtAgregarEmpresa->close();
$stmtVerificarUsuario->close();
header($goTo);
