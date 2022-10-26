<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$goTo = "Location:/Admon/ConsultarAlumnos.php";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // En caso de recibir campos incorrectos
    $goTo .= "?action=updated_error";
    $mysqli->close();
    header($goTo);
    exit();
}

// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'user', 'pass', 'NombreA', 'NumeroC',
    'Correo', 'Direccion', 'Area', 'Carrera', 'postActualizarAlumno'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        $goTo .= "?action=updated_error";
        $mysqli->close();
        header($goTo);
        exit();
    }
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

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

try {

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtActualizarUsuario = $mysqli->prepare($consultaActualizarUsuario);
    $stmtActualizarUsuario->bind_param(
        "issii",
        $tipo,
        $user,
        $pass,
        $idAlumno,
        $Existe
    );

    // establecer parametros y ejecutar cambios
    $tipo = 1;
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $idAlumno = $_GET['id'];
    $Existe = 1;
    $stmtActualizarUsuario->execute();


    // ***** Actualizar Alumno */
    // preparar y parametrar
    $stmtActualizarAlumno = $mysqli->prepare($consultaActualizarAlumno);
    $stmtActualizarAlumno->bind_param(
        "ssssiiii",
        $NombreA,
        $NumeroC,
        $Correo,
        $Direccion,
        $Area,
        $Carrera,
        $idAlumno,
        $Existe
    );

    // establecer parametros y ejecutar cambios
    $NombreA   = $_POST['NombreA'];
    $NumeroC = $_POST['NumeroC'];
    $Correo = $_POST['Correo'];
    $Direccion = $_POST['Direccion'];
    $Area = $_POST['Area'];
    $Carrera = $_POST['Carrera'];
    $idAlumno = $_GET['id'];
    $Existe = 1;
    $stmtActualizarAlumno->execute();

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
        $goTo .= "?action=updated_exist";
    } else {

        // ***** Efectuar cambios */
        // En caso de no tener errores
        $mysqli->commit();
        $goTo .= "?action=updated_success";
    }
} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    // En caso de un error en la base de datos
    $mysqli->rollback();
    $goTo .= "?action=updated_error";
    //print $exception;
    //throw $exception;
}

$mysqli->close();
$stmtActualizarAlumno->close();
$stmtActualizarUsuario->close();
$stmtVerificarUsuario->close();
header($goTo);
