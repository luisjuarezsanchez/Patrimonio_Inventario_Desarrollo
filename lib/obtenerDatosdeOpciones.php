<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 01/08/2019
 * Time: 06:59 PM
 */

include_once "conexion.php";
include_once "funcionesGenerales.php";

$valorSeleccionado=0;

if($_GET["pais"]!=0) {
    $valorSeleccionado=$_GET["pais"];
}
else if ($_GET["estado"]!=0) {
    $valorSeleccionado=$_GET["estado"];
}
else if($_GET["municipio"]!=0) {
    $valorSeleccionado=$_GET["municipio"];
}

$objeto = (object)[
    'campoDestino'=>$_GET["campoOrigen"]+1,
    'valores' => obtenerValoresOpcionOpcion($valorSeleccionado),
];


echo json_encode($objeto);

function obtenerValoresOpcionOpcion($opcion)
{
    $con = conecta_DB();
    $arrayOpciones = array();
    $sql = "SELECT opcion_opciones.ID_OPCION_DESTINO as id , cat_opciones.NOMBRE as valor FROM opcion_opciones 
          INNER JOIN cat_opciones ON cat_opciones.ID = opcion_opciones.ID_OPCION_DESTINO 
          WHERE opcion_opciones.ID_OPCION =  " . $opcion . " ";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $opcion = (object)[
            'id_valor' => $row['id'],
            'nombre' => codifica($row['valor']),
        ];
        $arrayOpciones[]=$opcion;
    }
    mysqli_free_result($result);
    return $arrayOpciones;
}
?>