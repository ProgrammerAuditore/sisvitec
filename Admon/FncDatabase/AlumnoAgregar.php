<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
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
    'user', 'pass', 'NombreA', 'NumeroC',
    'Correo', 'Direccion', 'Area', 'Carrera', 'postCrearAlumno'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=error";
        $goTo .= "&title=Alumno no agregado.";
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

$consultaObtenerIdLogin = "SELECT MAX(id_Login) AS max_login FROM `login` ; ";

$consultaCrearUsuario = "INSERT INTO `login`  
(`tipo`, `User`, `Password`, `Existe`) 
VALUES (?,?,?,?) ; ";

$consultaCrearAlumno = "INSERT INTO `alumnos`   
(`Nombre`, `Num_Control`,  `Correo`,  
`Direccion`, `id_Area`, `id_Carrera`, `id_Login`, `Existe`) 
VALUES (?,?,?,?,?,?,?,?); ";

try {

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtCrearUsuario = $mysqli->prepare($consultaCrearUsuario);
    $stmtCrearUsuario->bind_param("issi", $tipo, $user, $pass, $Existe);

    // establecer parametros y ejecutar cambios
    $tipo = 1; // <=== Tipo alumno
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $Existe = 1;
    $stmtCrearUsuario->execute();

    // ***** Obtener ID Login del usuario creado recientemente */
    $queryObtenerIdLogin = $mysqli->query($consultaObtenerIdLogin);
    $rowLogin = $queryObtenerIdLogin->fetch_array();

    // ***** Registrar Alumno */
    // preparar y parametrar
    $stmtCrearAlumno = $mysqli->prepare($consultaCrearAlumno);
    $stmtCrearAlumno->bind_param("ssssiiii", $NombreA, $NumeroC, $Correo, $Direccion, $Area, $Carrera, $idLogin, $Existe);

    // establecer parametros y ejecutar cambios
    $NombreA   = $_POST['NombreA'];
    $NumeroC = $_POST['NumeroC'];
    $Correo = $_POST['Correo'];
    $Direccion = $_POST['Direccion'];
    $Area = $_POST['Area'];
    $Carrera = $_POST['Carrera'];
    $idLogin  = $rowLogin['max_login'];
    $Existe = 1;
    $stmtCrearAlumno->execute();

    // ***** Verificar Usuario */
    // preparar y parametrar
    $stmtVerificarUsuario = $mysqli->prepare($consultaVerificarUsuario);
    $stmtVerificarUsuario->bind_param("s",$user);

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
        $goTo .= "&title=Alumno no agregado.";
        $goTo .= "&msg=El usuario <mark>$user</mark><br/>";
        $goTo .= "<b>Ya está registrado.<b>";
        $goTo .= $againTo;

    } else {

        // ***** Efectuar cambios */
        // En caso de no tener errores
        $mysqli->commit();
        $goTo .= "?action=success";
        $goTo .= "&title=Alumno agregado.";

    }
} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
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
header($goTo);