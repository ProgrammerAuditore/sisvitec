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

// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'user', 'pass', 'NombreA', 'NumeroC',
    'Correo', 'Direccion', 'Area', 'Carrera', 'postCrearAlumno'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        // Muestra un mensaje de error y 3 seg después
        // se redirige a ConsultaAlumno
        header("Location: /Admon/ConsultaAlumno.php?action=created_error");
        print "Campos incorrectos, verificar campos";
        exit();
    }
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

// Obtener Loggin ID
$consultaObtenerIdLogin = "SELECT MAX(id_Login) AS max_login FROM `login` ; ";

// Crear consulta
$consultaCrearUsuario = "INSERT INTO `login`  
(`tipo`, `User`, `Password`, `Existe`) 
VALUES (?,?,?,?) ; ";

// Crear consulta
$consultaCrearAlumno = "INSERT INTO `alumnos`   
(`Nombre`, `Num_Control`,  `Correo`,  
`Direccion`, `id_Area`, `id_Carrera`, `id_Login`, `Existe`) 
VALUES (?,?,?,?,?,?,?,?); ";

try {

    // ***** Obtener ID Login */
    $queryObtenerIdLogin = $mysqli->query($consultaObtenerIdLogin);
    $rowLogin = $queryObtenerIdLogin->fetch_array();
    

    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtCrearUsuario = $mysqli->prepare($consultaCrearUsuario);
    $stmtCrearUsuario->bind_param("issi", $tipo, $user, $pass, $Existe);

    // establecer parametros y ejecutar cambios
    $tipo = 1;
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $Existe = 1;
    $stmtCrearUsuario->execute();

    // ***** Registrar Alumno */
    // preparar y parametrar
    $stmtCrearAlumno = $mysqli->prepare($consultaCrearAlumno);
    $stmtCrearAlumno->bind_param("ssssiiii", $NombreA, $NumeroC, $Correo, $Direccion, $Area, $Carrera, $idAlumno, $Existe);

    // establecer parametros y ejecutar cambios
    $NombreA   = $_POST['NombreA'];
    $NumeroC = $_POST['NumeroC'];
    $Correo = $_POST['Correo'];
    $Direccion = $_POST['Direccion'];
    $Area = $_POST['Area'];
    $Carrera = $_POST['Carrera'];
    $idAlumno = $rowLogin['max_login'];
    $Existe = 1;
    $stmtCrearAlumno->execute();

    // ***** Efectuar cambios */
    $mysqli->commit();
    // En caso de no tener errores
    // Muestra un mensaje exitosa y 3 seg después
    // se redirige a ConsultaAlumno
    header("Location: /Admon/ConsultaAlumno.php?action=created_success");
    print "Usuario creado exitosamente.";
} catch (mysqli_sql_exception $exception) {

    // ***** Deshacer cambios */
    $mysqli->rollback();
    // En caso de tener error en MYSQL
    // Muestra un mensaje de error y 3 seg después
    // se redirige a ConsultaAlumno
    //header("refresh:3;url=../ConsultaAlumno.php");
    print "Usuario no actualizado satisfactoriamente";

    print $exception;
    //throwLogin $exception;
}

$mysqli->close();
$stmtCrearUsuario->close();