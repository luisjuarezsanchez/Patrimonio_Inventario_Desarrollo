<?php
require_once "lib/conexion.php";

/** DATABASE
 * This function return a full row from a table, given the table's name, the
 * identifier column of the criteria and the identifier value for the row.
 */
function getRowFromDatabase($tableName, $rowId, $fieldName = "id")
{
    //$functionResult;
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT * FROM " . $tableName . " WHERE " . $fieldName . "='" . $rowId . "';";
    $result = mysqli_query($dbLink, $query);
    if (!$result)
        $functionResult = "NOT_FOUND";
    else {
        $functionResult = selectResultToObject(mysqli_fetch_assoc($result));
    }
    mysqli_free_result($result);
    mysqli_close($dbLink);
    return $functionResult;
}

function getRowsFromDatabase($tableName, $rowId, $fieldName = "id")
{
    // $functionResult;
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT * FROM " . $tableName . " WHERE " . $fieldName . "='" . $rowId . "';";
    $result = mysqli_query($dbLink, $query);
    if (!$result)
        $functionResult = "NOT_FOUND";
    else {
        $functionResult = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($functionResult, selectResultToObject($row));
        }
        mysqli_free_result($result);
    }
    mysqli_close($dbLink);
    return $functionResult;
}

function getRowsFromDatabaseDash($seleccion,$filtro)
{
    // $functionResult;
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    //$query = "SELECT * FROM " . $tableName . " WHERE " . $fieldName . "='" . $rowId . "AND ".$idficha." = ".$filtro."';";
    $query = "SELECT * FROM registros WHERE ID_ESTATUS_REGISTRO = ".$filtro." AND ID_FICHA = '$seleccion' /*LIMIT 520,10*/";
    /*$query = "SELECT ID, FECHA, SUBSTRING(FROM_UNIXTIME(FECHA),1,10) AS FECHA_DECOD, ID_USUARIO, ID_PERIODO, ID_FICHA, ID_ESTATUS_PAPELERA, ID_ESTATUS_REGISTRO, ID_MUSEO, AUTOR, NUMERODECAT
    FROM registros
    WHERE ID_ESTATUS_REGISTRO IN (2, 3) AND ID_FICHA = 6 AND SUBSTRING(FROM_UNIXTIME(FECHA),1,10) LIKE '%2023-05-%' 
    ORDER BY FECHA_DECOD DESC
    LIMIT 349,1;";*/
    $result = mysqli_query($dbLink, $query);	
    
    if (!$result)
        $functionResult = "NOT_FOUND";
    else {
        $functionResult = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($functionResult, selectResultToObject($row));
        }
        mysqli_free_result($result);
    }
    mysqli_close($dbLink);
    return $functionResult;
}




















function rowExists_DB($tableName, $rowId)
{
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT * FROM " . $tableName . " WHERE id='" . $rowId . "';";
    $result = mysqli_query($dbLink, $query);
    mysqli_close($dbLink);
    if (!$result)
        return "NOT_FOUND";
    else {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row["ID_ESTATUS_REGISTRO"];
    }
}

function getColumnValue($tableName, $rowId, $columnName, $idColumnName = "id")
{
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT " . $columnName . ", ID_TIPO_CAMPO FROM " . $tableName . " WHERE " . $idColumnName . "='" . $rowId . "';";
    $result = mysqli_query($dbLink, $query);
    mysqli_close($dbLink);
    if (!$result)
        return "NOT_FOUND";
    else {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row[$columnName] . "|" . $row["ID_TIPO_CAMPO"];
    }
}

function getValueInCatalog($currentIDValue, $fieldId)
{
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT * FROM cat_opciones WHERE ID_CAMPO = " . $fieldId . " AND ID = " . $currentIDValue . ";";
    $result = mysqli_query($dbLink, $query);
    mysqli_close($dbLink);
    if (!$result)
        return "NOT_FOUND";
    else {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row["NOMBRE"];
    }
}

function filterRegisterStatus($key)
{
    switch ($key) {
        case 1:
            return "Guardado";
            break;
        case 2:
            return "Revisión";
            break;
        case 3:
            return "Validado";
            break;
        case 4:
            return "Publicado";
            break;
        case 5:
            return "Comentado";
            break;
        default:
            return "UNKNOW";
    }
}


function getRegisterInfo($idRegistro)
{
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "SELECT U.NOMBRE, U.APELLIDOS, M.NOMBRE museo FROM registros AS R, usuarios AS U, cat_opciones as M WHERE R.ID_USUARIO = U.ID AND R.ID_MUSEO = M.ID AND R.ID = '" . $idRegistro . "';";
    $result = mysqli_query($dbLink, $query);
    mysqli_close($dbLink);
    if (!$result)
        return "NOT_FOUND";
    else {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        $tmpResult = new stdClass();
        $tmpResult->nombreCompleto = $row["NOMBRE"] . " " . $row["APELLIDOS"];
        $tmpResult->museo = $row["museo"];
        return $tmpResult;
    }
}


function simpleUpdateRegister($tableName, $targetAttribute, $newValue, $keyAttribute, $keyValue)
{
    $dbLink = conecta_DB();
    $dbLink->set_charset("utf8");
    $query = "UPDATE " . $tableName . " SET " . $targetAttribute . " = \"" . $newValue . "\" WHERE " . $keyAttribute . " = '" . $keyValue . "';";
    $result = mysqli_query($dbLink, $query);
    $returnValue = "FAIL";
    if ($result)
        $returnValue = "SUCCESS";
    //mysqli_free_result($result);
    mysqli_close($dbLink);
    return $returnValue;
}





function selectResultToObject($result)
{
    $object = new stdClass();
    foreach ($result as $key => $value)
        $object->{$key} = $value;
    return $object;
}
?>