<?php

require_once 'lib/conexion.php';

$bd = conecta_DB();


$idSeccion = 1; //Origen
$idSeccionDest = 174; //Destino

$model = 'SELECT * FROM secciones where ID_FICHA IN (1,2,3,4,5)';
$results = $bd->query($model);

$i = 0;
while ($fila = $results->fetch_assoc()) {
    $i += 1;
    printf ('DELETE FROM secciones_campos WHERE ID_SECCION = ' . $fila["ID"] . ';');
    printf ("<br/>");
    
    
}

printf($i);

$results->free();
$bd->close();

?>