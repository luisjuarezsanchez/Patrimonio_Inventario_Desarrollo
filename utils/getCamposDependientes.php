<?php

if(isset($_GET["oid"])){
    $camposDependientes = obtenerCamposDependientes($_GET["oid"]);
    echo json_encode($camposDependientes, JSON_UNESCAPED_UNICODE);
} else {
    echo "NOT_FOUND";
}

function obtenerCamposDependientes($opcionSeleccionadaID){
    require_once "constants.php";
    $apuntadoresACampos = getRowsFromDatabase($TABLA_CAMPOS_DEPENDIENTES, $opcionSeleccionadaID, "ID_OPCION");
    $camposDependientes = array();
    foreach($apuntadoresACampos as $apuntadorACampo){
        $campo = getRowFromDatabase($TABLA_CAMPOS, $apuntadorACampo->{"ID_CAMPO"}, "ID");
        if($campo->{"ID_TIPO_CAMPO"} == 3){
            //Es un catálogo y tiene opciones
            $campo->{"opciones"} = getRowsFromDatabase($TABLA_CATALOGO_OPCIONES, $campo->{"ID"}, "ID_CAMPO");
        }
        array_push($camposDependientes, $campo);
    }
    return $camposDependientes;
}

function getRowsFromDatabase($tableName, $rowId, $fieldName = "id"){
    require_once "../lib/conexion.php";
    require_once "constants.php";
    $functionResult;
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT * FROM ". $tableName ." WHERE ". $fieldName ."='". $rowId ."';";
    $result = mysqli_query($dbLink, $query);
    if(!$result)
        $functionResult = "NOT_FOUND";
    else{
        $functionResult = array();
        while($row = mysqli_fetch_assoc($result)) {
            array_push($functionResult, selectResultToObject($row));
        }
    }
    mysqli_free_result($result);
    mysqli_close($dbLink);
    return $functionResult;
}

function getRowFromDatabase($tableName, $rowId, $fieldName = "id"){
    require_once "constants.php";
    require_once "../lib/conexion.php";
    $functionResult;
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT * FROM ". $tableName ." WHERE ". $fieldName ."='". $rowId ."';";
    $result = mysqli_query($dbLink, $query);
    if(!$result)
        $functionResult = "NOT_FOUND";
    else{
        $functionResult = selectResultToObject(mysqli_fetch_assoc($result));
    }
    mysqli_free_result($result);
    mysqli_close($dbLink);
    return $functionResult;
}

function selectResultToObject($result){
    $object = new stdClass();
    foreach ($result as $key => $value)
        $object->{$key} = $value;
    return $object;
}

?>