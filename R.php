<?php
class Redi
    {
        function _redic($ubicacion)
        {

		/* Redirecciona a una página diferente en el mismo directorio el cual se hizo la petición */
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = $ubicacion;
		header("Location: http://$host$uri/$extra");
		exit;

        }
        

    }
    ?>