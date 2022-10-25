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

//****  Verificar que existe el parametro ID de empresa */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../ConsultarEmpresa.php");
    exit();
}


// Listar campos a recibir desde la pagina Editar Alumno
$camposHTML = array(
    'NombreE', 'tipoEmpresa',
    'RazonS', 'RFCE', 'direccion',
    'postActualizarEmpresa'
);

// Verificar campos recibidos
foreach ($camposHTML as $key) {
    if (!isset($_POST[$key]) || empty(trim($_POST[$key]))) {
        // En caso de recibir campos incorrectos
        // Muestra un mensaje de error y 3 seg después
        // se redirige a ConsultaAlumno
        header("Location: /Admon/ConsultarEmpresa.php?action=updated_error");
        print "Campos incorrectos, verificar campos";
        exit();
    }
}

// ***** Iniciar Transición */
$mysqli->begin_transaction();

// Crear consulta
$consultaActualizarEmpresa = "UPDATE empresa SET    
Nombre = ?, Razon_Social  = ?,   
RFC  = ?, tipo_empresa  = ?, Direccion  = ?  
WHERE id_empresa = ? AND Existe = ? ; ";

try {
    // ***** Actualizar Empresa */
    // preparar y parametrar
    $stmtAgregarEmpresa = $mysqli->prepare($consultaActualizarEmpresa);
    $stmtAgregarEmpresa->bind_param(
        "sssssii",
        $NombreE,
        $RazonSE,
        $RFCE,
        $idtipo,
        $Direccion,
        $idEmpresa,
        $Existe
    );

    // establecer parametros y ejecutar cambios
    $NombreE   = $_POST['NombreE'];
    $RazonSE = $_POST['RazonS'];
    $RFCE = $_POST['RFCE'];
    $idtipo = $_POST['tipoEmpresa'];
    $Direccion = $_POST['direccion'];
    $idEmpresa = $_GET['id'];
    $Existe = 1;
    $stmtAgregarEmpresa->execute();

    // ***** Efectuar cambios */
    $mysqli->commit();
    // En caso de no tener errores
    // Muestra un mensaje exitosa y 3 seg después
    // se redirige a ConsultaAlumno
    header("Location: /Admon/ConsultarEmpresa.php?action=updated_success");
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
