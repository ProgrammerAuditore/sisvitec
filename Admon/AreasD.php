<?php

$link = mysqli_connect('v196512', 'ceuarkos_SisVinTec', '#~AI,w6oONSd') or die('No se pudo conectar: ' . mysqli_error());
mysqli_select_db($link,'ceuarkos_SisvinTec') or die('No se pudo seleccionar la base de datos');
$query="SELECT * from area";
$result = mysqli_query($link,$query)
        or die("Ocurrio un error en la consulta SQL");

echo $query;
 echo "<option>Seleccionar...</option>";
while (($fila = mysqli_fetch_array($result)) != NULL) {
    echo '<option value="'.$fila['id_Area'].'">'.$fila['Nombre'].'</option>';
}
// Liberar resultados
mysqli_free_result($result);

// Cerrar la conexi¨®n
mysqli_close($link);
?>