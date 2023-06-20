<?php
require_once 'conexion.php';

$db =  conecta_DB();

$query = 'SELECT * FROM notificaciones WHERE TITULO like "%se a%" ;';
$result = $db->query($query);

while($row = $result->fetch_assoc()) {
    $titulo = str_replace ( " se a ", " se ha ", $row["TITULO"]);

    $update = 'UPDATE notificaciones SET TITULO = "' .$titulo. '" WHERE ID = ' . $row["ID"] . ';';
    echo $update . "<br>";
}


?>