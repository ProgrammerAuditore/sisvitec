<?php
    class Conexion
    {
        private $conn;
        function __construct()
        {
            $this->conn=new mysqli("localhost","root","","ceuarkos_sisvintec", 3306);
            //$this->conn=new mysqli("v196512","ceuarkos_SisVinTec","#~AI,w6oONSd","ceuarkos_SisvinTec");
        }
        function _ObtenerConexion()
        {
            return  $this->conn;
        }
    }

    $words_mex_encode = array(
        "ñ" => "%n%",
        "ü" => "%uu%", 
        "á" => "%a%", 
        "é" => "%e%", 
        "í" => "%i%", 
        "ó" => "%o%", 
        "ú" => "%u%"
    );
    
    $words_mex_decode = array(
        "%n%" => "ñ",
        "%uu%" => "ü", 
        "%a%" => "á", 
        "%e%" => "é", 
        "%i%" => "í", 
        "%o%" => "ó", 
        "%u%" => "ú"
    );
