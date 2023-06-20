<?php

$enlace = mysqli_connect("localhost", "srvccm", "720./Srv.xi8", "patrimonio_dev");
$enlace->set_charset("utf8");

$countries = file_get_contents("json/countries.json");
$array = json_decode($countries, true);
foreach($array as $country){
    $query = 'INSERT INTO cat_opciones (NOMBRE, ID_CAMPO) VALUES ("' . $country . '",66)';
    mysqli_query($enlace, $query);
    echo $query . "<br/>";
}

mysqli_close($enlace);


?>