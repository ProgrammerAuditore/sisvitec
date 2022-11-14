<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include '../../conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$goTo = "Location:/Admon/ConsultarAlumnos.php";
$againTo = "<br/><hr><a href=/Admon/EdiAlumno.php?IdUsuario={$_GET['id']}>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro id */
// id es el ID Login Cuenta 
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // En caso de recibir campos incorrectos
    $goTo .= "?action=error&msg=Usuario no valido&msg=Este usuario no existe.";
    $mysqli->close();
    header($goTo);
    exit();
}

// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'cuenta-user',
    'cuenta-password',
    'alumno-nombre',
    'alumno-numero-control',
    'alumno-correo',
    'alumno-direccion',
    'alumno-area-id',
    'alumno-carrera-id',
    'postActualizarAlumno'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {

    $_POST[$key] = trim($_POST[$key]);
    $_POST[$key] = strtr($_POST[$key], $words_mex_encode);
    $_POST[$key] = htmlentities($_POST[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=Alumno no actualizado.";
        $goTo .= "&msg=Verifique que los campos sean validos y no vacios.";
        $goTo .= $againTo;
        $mysqli->close();
        header($goTo);
        exit();
    }
    
}

// Crear variables de datos recibidos
$cuentaTipo = 1;
$cuentaUser = strtr(htmlspecialchars($_POST['cuenta-user'], ENT_QUOTES), $words_mex_decode);
$cuentaPassword = strtr(htmlspecialchars($_POST['cuenta-password'], ENT_QUOTES), $words_mex_decode);
$CuentaIdLogin = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$AlumnoNombre = strtr(htmlspecialchars($_POST['alumno-nombre'], ENT_QUOTES), $words_mex_decode);
$AlumnoNumeroControl = strtr(htmlspecialchars($_POST['alumno-numero-control'], ENT_QUOTES), $words_mex_decode);
$AlumnoCorreo = strtr(htmlspecialchars($_POST['alumno-correo'], ENT_QUOTES), $words_mex_decode);
$AlumnoDireccion = strtr(htmlspecialchars($_POST['alumno-direccion'], ENT_QUOTES), $words_mex_decode);
$AlumnoAreaId = filter_var($_POST['alumno-area-id'], FILTER_SANITIZE_NUMBER_INT);
$AlumnoCarreraId = filter_var($_POST['alumno-carrera-id'], FILTER_SANITIZE_NUMBER_INT);
$Existe = 1;

// Crear consulta
$consultaVerificarUsuario = "SELECT * FROM `login` WHERE User = ? ; ";

// Crear consulta
$consultaActualizarUsuario = "UPDATE `login` SET   
tipo = ?, User = ?, `Password` = ?   
WHERE id_Login = ? AND Existe = ?; ";

// Crear consulta
$consultaActualizarAlumno = "UPDATE `alumnos` SET 
Nombre = ?, Num_Control = ?,  Correo = ?,  
Direccion = ?, id_Area = ?, id_Carrera = ? 
WHERE id_Login = ? AND Existe = ? ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtActualizarUsuario = $mysqli->prepare($consultaActualizarUsuario);
    $stmtActualizarUsuario->bind_param(
        "issii",
        $cuentaTipo,
        $cuentaUser,
        $cuentaPassword,
        $CuentaIdLogin,
        $Existe
    );
    $stmtActualizarUsuario->execute();


    // ***** Actualizar Alumno */
    // preparar y parametrar
    $stmtActualizarAlumno = $mysqli->prepare($consultaActualizarAlumno);
    $stmtActualizarAlumno->bind_param(
        "ssssiiii",
        $AlumnoNombre,
        $AlumnoNumeroControl,
        $AlumnoCorreo,
        $AlumnoDireccion,
        $AlumnoAreaId,
        $AlumnoCarreraId,
        $CuentaIdLogin,
        $Existe
    );
    $stmtActualizarAlumno->execute();

    // ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s", $cuentaUser);
    $stmtVerificarUsuario->execute();

    $stmtVerificarUsuario->store_result();
    $rowUsuario = $stmtVerificarUsuario->num_rows;

    if ($rowUsuario > 1) {

        // Deshacer cambios
        // En caso de existir el usuario
        $mysqli->rollback();
        $goTo .= "?action=error";
        $goTo .= "&title=Alumno no actualizado.";
        $goTo .= "&msg=El usuario <mark>$user</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
        $goTo .= $againTo;
    } else {

        // Efectuar cambios
        // En caso de no tener errores
        $mysqli->commit();
        $goTo = "Location:/Admon/ConAlumno.php";
        $goTo .= "?IdUsuario=" . $CuentaIdLogin;
        $goTo .= "&action=success";
        $goTo .= "&title=Alumno actualizado.";
    }
} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    // En caso de un error en la base de datos
    $mysqli->rollback();
    $goTo .= "?action=error";
    $goTo .= "&title=Alumno no actualizado.";
    $goTo .= $againTo;
    //print $exception;
    //throw $exception;

}

$mysqli->close();
$stmtActualizarAlumno->close();
$stmtActualizarUsuario->close();
$stmtVerificarUsuario->close();
header($goTo);
