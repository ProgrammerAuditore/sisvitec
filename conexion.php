<?php
class Conexion
{
    private $conn;
    function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "ceuarkos_sisvintec", 3306);
        // $this->conn=new mysqli("v196512","ceuarkos_SisVinTec","#~AI,w6oONSd","ceuarkos_SisvinTec");
    }
    function _ObtenerConexion()
    {
        return  $this->conn;
    }
}
$words_mex_encode = array(
    "ñ" => "%n%",
    "ü" => "%-u%",
    "á" => "%a%",
    "é" => "%e%",
    "í" => "%i%",
    "ó" => "%o%",
    "ú" => "%u%",
    "Ñ" => "%N%",
    "Ü" => "%-U%",
    "Á" => "%A%",
    "É" => "%E%",
    "Í" => "%I%",
    "Ó" => "%O%",
    "Ú" => "%U%"
);

$words_mex_decode = array(
    "%n%" => "ñ",
    "%-u%" => "ü",
    "%a%" => "á",
    "%e%" => "é",
    "%i%" => "í",
    "%o%" => "ó",
    "%u%" => "ú",
    "%N%" => "Ñ",
    "%-U%" => "Ü",
    "%A%" => "Á",
    "%E%" => "É",
    "%I%" => "Í",
    "%O%" => "Ó",
    "%U%" => "Ú"
);
