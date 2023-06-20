<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/08/2019
 * Time: 10:39 PM
 */
include_once "conexion.php";
include_once "funcionesGenerales.php";



if($_GET["jsonProcedencia"]) {
    $origenes=0;
    $jsonArray = json_decode($_GET["jsonProcedencia"]);
   $arrayProcedencia = array();

    foreach ($jsonArray as $campo)
    {
        if($campo->campoOrigen+1!=70) {
            $objeto = (object)[
                'campoDestino' => $campo->campoOrigen + 1,
                'valores' => obtenerValoresOpcionOpcion($campo->valorSeleccionado),
            ];

            $arrayProcedencia[] = $objeto;
        }
    }
    echo json_encode($arrayProcedencia);

//echo $origenes;
}

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