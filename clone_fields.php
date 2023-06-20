<?php

require_once 'lib/conexion.php';

$bd = conecta_DB();

$model = 'SELECT * FROM `secciones_campos` where ID_SECCION = 8 ORDER BY INDICE, ID;';
$results = $bd->query($model);

$i = 0;
while ($fila = $results->fetch_assoc()) {
    $i += 1;
    printf ('INSERT INTO secciones_campos (ID_SECCION, ID_CAMPO, INDICE) VALUES (184,' . $fila["ID_CAMPO"] . ',' . $fila["INDICE"] . ');');
    printf ("<br/>");
    
    
}

$results->free();
$bd->close();

?>