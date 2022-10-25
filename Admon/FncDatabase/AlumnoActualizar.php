<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

//****  Verificar que existe el parametro IdUsuario */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../ConsultaAlumno.php");
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
        // Muestra un mensaje de error y 3 seg después
        // se redirige a ConsultaAlumno
        header("Location: /Admon/ConsultarAlumnos.php?action=updated_error");
        print "Campos incorrectos, verificar campos";
        exit();
    }
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

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
    $stmtActualizarUsuario->bind_param("issii", 
        $tipo, $user, $pass, $idAlumno, $Existe);

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
    $stmtActualizarAlumno->bind_param("ssssiiii",
        $NombreA, $NumeroC, $Correo, $Direccion, $Area, $Carrera, $idAlumno, $Existe);

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

    // ***** Efectuar cambios */
    $mysqli->commit();
    // En caso de no tener errores
    // Muestra un mensaje exitosa y 3 seg después
    // se redirige a ConsultaAlumno
    header("Location: /Admon/ConsultarAlumnos.php?action=updated_success");
    print "Usuario Actualizado exitosamente.";
} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    $mysqli->rollback();
    // En caso de tener error en MYSQL
    // Muestra un mensaje de error y 3 seg después
    // se redirige a ConsultaAlumno
    header("refresh:3;url=../ConsultaAlumno.php");
    print "Usuario no actualizado satisfactoriamente";

    print $exception;
    //throw $exception;
}

$mysqli->close();
$stmtActualizarAlumno->close();
