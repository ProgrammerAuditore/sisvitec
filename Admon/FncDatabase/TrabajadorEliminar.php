<?php

// Iniciar session, hacer conexion  a la base de datos
// y obtener la conexion
include 'conexion.php';
$mysql = new Conexion();
$mysqli = $mysql->_ObtenerConexion();
$title = "Empresa";
$goTo = "Location:/Admon/ConsultarEmpresas.php";
$againTo = "<br/><hr><a href=/Admon/EdiEmpresa.php?IdEmpresa={$_GET['idE']}>Volver a intentarlo.</a>";

// Verificar la conexion
if ($mysqli->connect_errno) {
    die("Error en la conexion" . $mysqli->connect_error);
}

// Listar campos a recibir
$camposGet = array('idT', 'idE');

// Verificar campos recibidos
foreach ($camposGet as $key) {

    $_GET[$key] = trim($_GET[$key]);
    $_GET[$key] = strtr($_GET[$key], $words_mex_encode);
    $_GET[$key] = htmlentities($_GET[$key], ENT_QUOTES | ENT_IGNORE, "UTF-8");

    if (!isset($_GET[$key]) || empty(trim($_GET[$key]))) {
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
$trabajadorId = filter_var(trim($_GET['idT']), FILTER_SANITIZE_NUMBER_INT);
$empresaId = filter_var(trim($_GET['idE']), FILTER_SANITIZE_NUMBER_INT);
$Existe = 1;

// Crear consulta
$consultaQ = "DELETE FROM `trabajador`   
WHERE id_Trabajador = ? AND id_Empresa = ? AND Existe = ? ; ";

// ***** Iniciar Transición */
$mysqli->begin_transaction();

try {


    // preparar y parametrar
    $stmt = $mysqli->prepare($consultaQ);
    $stmt->bind_param("iii", $trabajadorId, $empresaId, $Existe);
    $stmt->execute();

    // Efectuar cambios
    $mysqli->commit();
    
} catch (mysqli_sql_exception $exception) {

    // Deshacer cambios
    $mysqli->rollback();
    //print $exception;
    //throw $exception;

}

$mysqli->close();
$stmt->close();
