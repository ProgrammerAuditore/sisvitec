<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$goTo = "Location:/Admon/ConsultarAlumnos.php";
$againTo = "<br/><hr><a href=/Admon/AgrAlu.php>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'cuenta-usuario',
    'cuenta-password',
    'alumno-nombre',
    'alumno-numero-control',
    'alumno-correo',
    'alumno-direccion',
    'alumno-area-id',
    'alumno-carrera-id',
    'postCrearAlumno'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    
    $_POST[$key] = trim($_POST[$key]);
    $_POST[$key] = strtr($_POST[$key], $words_mex_encode);
    $_POST[$key] = htmlentities($_POST[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=Alumno no registrado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// Crear variables de datos recibidos
$CuentaTipo = 1; // <=== Tipo alumno;
$CuentaUsuario = strtr(htmlspecialchars($_POST['cuenta-usuario'], ENT_QUOTES), $words_mex_decode);
$CuentaPassorwd = strtr(htmlspecialchars($_POST['cuenta-password'], ENT_QUOTES), $words_mex_decode);
$AlumnoNombre =  strtr(htmlspecialchars($_POST['alumno-nombre'], ENT_QUOTES), $words_mex_decode);
$AlumnoNumeroControl = strtr(htmlspecialchars($_POST['alumno-numero-control'], ENT_QUOTES), $words_mex_decode);
$AlumnoCorreo = filter_var($_POST['alumno-correo'], FILTER_SANITIZE_EMAIL);
$AlumnoDireccion = strtr(htmlspecialchars($_POST['alumno-direccion'], ENT_QUOTES), $words_mex_decode);
$AlumnoAreaId = filter_var($_POST['alumno-area-id'], FILTER_SANITIZE_NUMBER_INT);
$AlumnoCarreraId = filter_var($_POST['alumno-carrera-id'], FILTER_SANITIZE_NUMBER_INT);
$Existe = 1;

$consultaVerificarAlumno = "SELECT * FROM `alumnos` WHERE Num_Control = ? ; ";

$consultaVerificarUsuario = "SELECT * FROM `login` WHERE User = ? ; ";

$consultaObtenerIdLogin = "SELECT MAX(id_Login) AS max_login FROM `login` ; ";

$consultaCrearUsuario = "INSERT INTO `login`  
(`tipo`, `User`, `Password`, `Existe`) 
VALUES (?,?,?,?) ; ";

$consultaCrearAlumno = "INSERT INTO `alumnos`   
(`Nombre`, `Num_Control`,  `Correo`,  
`Direccion`, `id_Area`, `id_Carrera`, `id_Login`, `Existe`) 
VALUES (?,?,?,?,?,?,?,?); ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtCrearUsuario = $mysqli->prepare($consultaCrearUsuario);
    $stmtCrearUsuario->bind_param(
        "issi",
        $CuentaTipo,
        $CuentaUsuario,
        $CuentaPassorwd,
        $Existe
    );
    $stmtCrearUsuario->execute();

    // ***** Obtener ID Login del usuario creado recientemente */
    $queryObtenerIdLogin = $mysqli->query($consultaObtenerIdLogin);
    $rowLogin = $queryObtenerIdLogin->fetch_array();
    $CuentaLoginId = $rowLogin['max_login'];

    // ***** Registrar Alumno */
    // preparar y parametrar
    $stmtCrearAlumno = $mysqli->prepare($consultaCrearAlumno);
    $stmtCrearAlumno->bind_param(
        "ssssiiii",
        $AlumnoNombre,
        $AlumnoNumeroControl,
        $AlumnoCorreo,
        $AlumnoDireccion,
        $AlumnoAreaId,
        $AlumnoCarreraId,
        $CuentaLoginId,
        $Existe
    );
    $stmtCrearAlumno->execute();

    // ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s", $CuentaUsuario);
    $stmtVerificarUsuario->execute();

    // Obtener los resultados de la ejecucion SQL
    $stmtVerificarUsuario->store_result();
    $rowUsuario = $stmtVerificarUsuario->num_rows;

    // ***** Verificar Alumno */
    // preparar y parametrar
    $stmtVerificarAlumno = $mysqli->prepare($consultaVerificarAlumno);
    $stmtVerificarAlumno->bind_param("s", $AlumnoNumeroControl);
    $stmtVerificarAlumno->execute();

    // Obtener los resultados de la ejecucion SQL
    $stmtVerificarAlumno->store_result();
    $rowAlumno = $stmtVerificarAlumno->num_rows;

    if ($rowUsuario > 1 || $rowAlumno > 1) {

        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=Alumno no registrado.";
        $goTo .= "&msg=El usuario <mark>$CuentaUsuario</mark> con número de control <mark>$AlumnoNumeroControl</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
        $goTo .= $againTo;
    } else {

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo .= "?action=success";
        $goTo .= "&title=Alumno registrado.";
    }
} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    $mysqli->rollback();
    $goTo .= "?action=error";
    $goTo .= "&title=Alumno no actualizado.";
    $goTo .= $againTo;
    //print $exception;
    //throwLogin $exception;
}

$mysqli->close();
$stmtCrearUsuario->close();
$stmtCrearAlumno->close();
$stmtVerificarUsuario->close();
$stmtVerificarAlumno->close();
header($goTo);
