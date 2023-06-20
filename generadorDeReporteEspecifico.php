<?php

ini_set("include_path", '/home/rua91aoa240w/php:' . ini_get("include_path") );

require_once 'Spreadsheet/Excel/Writer.php';
require_once "utils/constants.php";


function getRowFromDatabase($tableName, $rowId, $fieldName = "id"){
    $functionResult;
    $dbLink = new mysqli("localhost", "srvccm", "720./Srv.xi8", "patrimonio_dev");
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

function getRowsFromDatabase($tableName, $rowId, $fieldName = "id"){
    $functionResult;
    $dbLink = new mysqli("localhost", "srvccm", "720./Srv.xi8", "patrimonio_dev");
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
        mysqli_free_result($result);
    }
    mysqli_close($dbLink);
    return $functionResult;
}

function selectResultToObject($result){
    $object = new stdClass();
    foreach ($result as $key => $value)
        $object->{$key} = $value;
    return $object;
}


if(isset($_GET["ficha"]) && isset($_GET["registros"])){
	$fichaSeleccionada = $_GET["ficha"];
	$registrosSeleccionados = $_GET["registros"];
	
    $workbook = new Spreadsheet_Excel_Writer();
    $workbook->send('reporte.xls');
    $worksheet =& $workbook->addWorksheet('Reporte de Fichas');

	$contadorDeColumnas = 0;
	$columnKeys = array();
	$seccionesDelReporte =  getRowsFromDatabase("secciones", $fichaSeleccionada, "ID_FICHA");
	for($i = 0 ; $i < sizeof($seccionesDelReporte) ; $i++){
	    $camposDeSeccion = getRowsFromDatabase("secciones_campos", $seccionesDelReporte[$i]->{"ID"}, "ID_SECCION");
	    
	    for($j = 0 ; $j < sizeof($camposDeSeccion) ; $j++){
	        $objetoCampo = getRowsFromDatabase("campos", $camposDeSeccion[$j]->{"ID_CAMPO"}, "ID")[0];
	        $string = mb_convert_encoding($objetoCampo->{"NOMBRE"}, 'ISO-8859-1');
	        $worksheet->write(0, $contadorDeColumnas, $string);
	        $columnKeys[$objetoCampo->{"ID"}] = $contadorDeColumnas;
	        $contadorDeColumnas += 1;
	    }
	}
	
	
	$registrosAMostrar = json_decode($registrosSeleccionados);
	$filaDeDatos = 1;
	foreach($registrosAMostrar as $registroAMostrar){
	    
	    $registro =  getRowsFromDatabase("registros", $registroAMostrar, "ID")[0];
	    
	    $archivoRegistro = "reg/" . $registro->{"ID"} . ".json";
        $json = file_get_contents($archivoRegistro);
        $jsonData = json_decode($json,true);
        foreach($jsonData as $jsonSeccion){
            $campos = $jsonSeccion["campos"];
            foreach($campos as $campo){
                $tipoDeCampo = getRowFromDatabase("campos", $campo["idCampo"], "ID")->{"ID_TIPO_CAMPO"};
                if($tipoDeCampo == "3"){
                    $campo["valor"] = getRowFromDatabase("cat_opciones", $campo["valor"], "ID")->{"NOMBRE"};
                }
                $string = mb_convert_encoding($campo["valor"], 'ISO-8859-1');
                $worksheet->write($filaDeDatos, $columnKeys[$campo["idCampo"]], $string);
            }
        }
        $filaDeDatos += 1;
	}
	
    
	$workbook->close();

} else {
    echo "No específico";
}

?>

