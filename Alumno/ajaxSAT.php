<?php

$link = mysqli_connect("localhost","root","","ceuarkos_sisvintec", 3306) or die('No se pudo conectar: ' . mysqli_error());

$query="SELECT * from tipo_sat";
$result = mysqli_query($link,$query)
        or die("Ocurrio un error en la consulta SQL");

echo $query;
 echo "<option>Seleccionar...</option>";
while (($fila = mysqli_fetch_array($result)) != NULL) {
    echo '<option value="'.$fila['id_tipo'].'">'.$fila['Nombre'].'</option>';
}
// Liberar resultados
mysqli_free_result($result);

// Cerrar la conexi¨®n
mysqli_close($link);
?>