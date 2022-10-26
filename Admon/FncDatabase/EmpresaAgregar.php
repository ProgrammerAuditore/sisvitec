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
    'user', 'pass', 'NombreE', 'RazonS', 'TelRH',
    'RFCE', 'idtipo', 'direccion', 'magnitud', 'alcance',
    'Giro', 'Mision', 'tipoEmpresa', 'NombreRL', 'CorreoRL',
    'RFCRL', 'TelRL', 'NombreRH', 'CorreoRH', 'RFCRH',
    'postAgregarEmpresa'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        // Muestra un mensaje de error y 3 seg después
        // se redirige a ConsultaAlumno
        header("Location: /Admon/ConsultarEmpresas.php?action=created_error");
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
$consultaAgregarEmpresa = "INSERT INTO empresa   
(Nombre, Razon_Social,  RFC, id_tipo, Direccion, Magnitud, Alcance, Giro,
Mision, Tipo_empresa, id_login, Existe )  
VALUES (?,?,?,?,?,?,?,?,?,?,?,?); ";

try {

    // ***** Obtener ID Login */
    $queryObtenerIdLogin = $mysqli->query($consultaObtenerIdLogin);
    $rowLogin = $queryObtenerIdLogin->fetch_array();


    // ***** Registrar Usuario */
    // preparar y parametrar
    $stmtCrearUsuario = $mysqli->prepare($consultaCrearUsuario);
    $stmtCrearUsuario->bind_param("issi", $tipo, $user, $pass, $Existe);

    // establecer parametros y ejecutar cambios
    $tipo = 2; // <=== Tipo empresa
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $Existe = 1;
    $stmtCrearUsuario->execute();

    // ***** Registrar Alumno */
    // preparar y parametrar
    $stmtAgregarEmpresa = $mysqli->prepare($consultaAgregarEmpresa);
    $stmtAgregarEmpresa->bind_param(
        "sssissssssii",
        $NombreE,
        $RazonSE,
        $RFCE,
        $idtipo,
        $Direccion,
        $magnitud,
        $alcance,
        $Giro,
        $Mision,
        $tipoEmpresa,
        $idLogin,
        $Existe
    );

    // establecer parametros y ejecutar cambios
    $NombreE   = $_POST['NombreE'];
    $RazonSE = $_POST['RazonS'];
    $RFCE = $_POST['RFCE'];
    $idtipo = intval($_POST['idtipo']);
    $Direccion = $_POST['direccion'];
    $magnitud = $_POST['magnitud'];
    $alcance = $_POST['alcance'];
    $Giro = $_POST['Giro'];
    $Mision = $_POST['Mision'];
    $tipoEmpresa = $_POST['tipoEmpresa'];
    $idLogin = $rowLogin['max_login'];
    $Existe = 1;
    $stmtAgregarEmpresa->execute();

    // ***** Efectuar cambios */
    $mysqli->commit();
    // En caso de no tener errores
    // Muestra un mensaje exitosa y 3 seg después
    // se redirige a ConsultaAlumno
    header("Location: /Admon/ConsultarEmpresas.php?action=created_success");
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