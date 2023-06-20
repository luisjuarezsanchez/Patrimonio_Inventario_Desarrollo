<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 01/08/2019
 * Time: 06:59 PM
 */

include_once "conexion.php";
include_once "funcionesGenerales.php";
include_once "funcionesAdministrativas.php";

$valorSeleccionado=0;

if($_GET["ficha"]!=0) {
    $objeto = (object)[
    'valores' => obtenerSecciones($_GET["ficha"]),
    ];
    echo json_encode($objeto);
}
else if ($_GET["seccion"]!=0) {
    $valorSeleccionado=$_GET["seccion"];
}
else if($_GET["tiposCampos"]!=0) {
    $valorSeleccionado=$_GET["tiposCampo"];
}


?>