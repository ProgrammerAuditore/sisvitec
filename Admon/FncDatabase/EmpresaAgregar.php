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
        $goTo .= "&title=$title no registrado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// Crear variables de campos recibidos
$CuentaTipo = 2; // <==== Tipo empresa
$CuentaUsuario = $_POST['cuenta-usuario'];
$CuentaPassword = $_POST['cuenta-password'];

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

$RepresentanteNombre = $_POST["representante-nombre"];
$RepresentanteRFC = $_POST["representante-rfc"];
$RepresentanteCorreo = $_POST["representante-correo"];
$RepresentantePuesto = "Representante Legal";
$RepresentanteTelefono = $_POST["representante-telefono"];

$RecursosHumanosNombre = $_POST["recursoshumanos-nombre"];
$RecursosHumanosRFC = $_POST["recursoshumanos-rfc"];
$RecursosHumanosCorreo = $_POST["recursoshumanos-correo"];
$RecursosHumanosPuesto = "Recursos Humanos";
$RecursosHumanosTelefono = $_POST["recursoshumanos-telefono"];
$Existe = 1;



// ***** Iniciar Transición */
$mysqli->begin_transaction();

$consultaVerificarUsuario = "SELECT * FROM `login` WHERE User = ? ; ";

// Obtener Loggin ID
$consultaObtenerIdLogin = "SELECT MAX(id_Login) AS max_login FROM `login` ; ";

// Obtener Loggin ID
$consultaObtenerIdEmpresa = "SELECT MAX(id_empresa) AS max_empresa FROM `empresa` ; ";

// Crear consulta
$consultaCrearUsuario = "INSERT INTO `login`  
(`tipo`, `User`, `Password`, `Existe`) 
VALUES (?,?,?,?) ; ";

// Crear consulta
$consultaAgregarEmpresa = "INSERT INTO empresa   
(Nombre, Razon_Social,  RFC, id_tipo, Direccion, Magnitud, Alcance, Giro,
Mision, Tipo_empresa, id_login, Existe )  
VALUES (?,?,?,?,?,?,?,?,?,?,?,?); ";

// Crear Trabajador
$consultaAgregarTrabajador = "INSERT INTO trabajador   
(Nombre, RFC,  Correo, Puesto, id_Empresa, Existe, Tel )  
VALUES (?,?,?,?,?,?,?); ";

try {

    // ***** Registrar una nuevo Cuenta */
    // preparar y parametrar
    $stmtCrearUsuario = $mysqli->prepare($consultaCrearUsuario);
    $stmtCrearUsuario->bind_param(
        "issi",
        $CuentaTipo,  
        $CuentaUsuario,
        $CuentaPassword,
        $Existe
    );
    $stmtCrearUsuario->execute();

    // ***** Obtener ID Login de la cuenta nueva */
    $queryObtenerIdLogin = $mysqli->query($consultaObtenerIdLogin);
    $rowLogin = $queryObtenerIdLogin->fetch_array();

    // ***** Registrar Empresa */
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
        $rowLogin['max_login'],
        $Existe
    );
    $stmtAgregarEmpresa->execute();

    // ***** Obtener ID Login de la empresa nueva */
    $queryObtenerIdEmpresa = $mysqli->query($consultaObtenerIdEmpresa);
    $rowEmpresa = $queryObtenerIdEmpresa->fetch_array();

    // ***** Registrar Trabajador (Representante Legal) */
    // preparar y parametrar
    $stmtAgregarRepresentante = $mysqli->prepare($consultaAgregarTrabajador);
    $stmtAgregarRepresentante->bind_param(
        "ssssiis",
        $RepresentanteNombre,
        $RepresentanteRFC,
        $RepresentanteCorreo,
        $RepresentantePuesto,
        $rowEmpresa['max_empresa'],
        $Existe,
        $RepresentanteTelefono
    );
    $stmtAgregarRepresentante->execute();

    // ***** Registrar Trabajador (Recursos Humanos) */
    // preparar y parametrar
    $stmtAgregarRecursosHumanos = $mysqli->prepare($consultaAgregarTrabajador);
    $stmtAgregarRecursosHumanos->bind_param(
        "ssssiis",
        $RecursosHumanosNombre,
        $RecursosHumanosRFC,
        $RecursosHumanosCorreo,
        $RecursosHumanosPuesto,
        $rowEmpresa['max_empresa'],
        $Existe,
        $RecursosHumanosTelefono
    );
    $stmtAgregarRecursosHumanos->execute();

    // ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s", $user);

    // establecer parametros y ejecutar cambios
    $user = $_POST['cuenta-usuario'];
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
$stmtCrearUsuario->close();
$stmtAgregarEmpresa->close();
$stmtVerificarUsuario->close();
$stmtAgregarRepresentante->close();
$stmtAgregarRecursosHumanos->close();
header($goTo);
