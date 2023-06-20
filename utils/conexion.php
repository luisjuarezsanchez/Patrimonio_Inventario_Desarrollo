<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 04/06/2019
 * Time: 04:05 PM
 */
require_once 'config.php';


function conecta_DB(){
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    //-> Emilio agregó esto para leer acentos correctamente.
    //$mysqli->set_charset("utf8");

    if($mysqli->connect_error){

        die('Error en la conexion' . $mysqli->connect_error);
    }

    return $mysqli;
}
?>