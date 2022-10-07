<?php
    class Conexion
    {
        private $conn;
        function __construct()
        {
             $this->conn=new mysqli("v196512","ceuarkos_SisVinTec","#~AI,w6oONSd","ceuarkos_SisvinTec");
        }
        function _ObtenerConexion()
        {
            return  $this->conn;
        }
    }
 ?>