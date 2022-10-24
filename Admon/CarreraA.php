<?php
$link = mysqli_connect("localhost","root","","ceuarkos_sisvintec", 3306) or die('No se pudo conectar: ' . mysqli_error());
mysqli_select_db($link,'ceuarkos_SisvinTec') or die('No se pudo seleccionar la base de datos');

$query="SELECT * from carrera";
$result = mysqli_query($link,$query)
        or die("Ocurrio un error en la consulta SQL");

echo $query;
 echo "<option>Seleccionar...</option>";
while (($fila = mysqli_fetch_array($result)) != NULL) {
    if(isset($_GET['idCarrera']) && $fila['id_carrera'] == $_GET['idCarrera'] ){
        echo '<option selected value="'.$fila['id_carrera'].'">'.$fila['Nombre'].'</option>';
    }else {
        echo '<option value="'.$fila['id_carrera'].'">'.$fila['Nombre'].'</option>';
    }
}
// Liberar resultados
mysqli_free_result($result);

// Cerrar la conexi¨®n
mysqli_close($link);
?>