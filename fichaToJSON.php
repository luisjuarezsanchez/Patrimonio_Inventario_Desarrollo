<?php

header("Access-Control-Allow-Origin: *");

if(isset($_GET["ficha"])){
    $fichaSolicitada = $_GET["ficha"];
    $fichaDevuelta = obtenerDetalleDelRegistro($fichaSolicitada);
    if ( $fichaDevuelta == "NOT_FOUND") {
        echo $fichaDevuelta;
    } else {
        echo json_encode($fichaDevuelta, JSON_UNESCAPED_UNICODE);
    }
} else {
    echo "REQUEST_INCOMPLETE";
}
    
function obtenerDetalleDelRegistro($idRegistro){
    
    require "utils/constants.php";
    require_once "utils/database_utils.php";
    
    $registroDB = getRowFromDatabase($TABLA_REGISTROS, $idRegistro);
    
    if(isset($idRegistro) && $registroDB != "NOT_FOUND"){
        
        //Encontrar el usuario que hizo el registro
        $usuario = getRowFromDatabase($TABLA_USUARIOS, $registroDB->{"ID_USUARIO"}, "ID");

        //Encontrar el usuario que hizo el registro
        $museo = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $registroDB->{"ID_MUSEO"}, "ID");
        
		$registroJSON = file_get_contents($DIRECTORIO_REGISTROS . "/" . $idRegistro . ".json");
		if(!$registroJSON){
			return "NOT_FOUND";
		}
		
		$archivos = null; // Contiene la lista de archivos digitales asociados a esta ficha

		$registroTemp = json_decode($registroJSON);
		$i = 0;
		foreach ($registroTemp as $seccionTemp){
		    if(isset($seccionTemp->archivos) && $seccionTemp->archivos != ""){
		        $archivos = $seccionTemp->archivos;
		        unset($registroTemp[$i]);
		    }
		        
    		foreach ($seccionTemp->campos as $campoTemp) {
    			$dbCampo = getRowFromDatabase($TABLA_CAMPOS, $campoTemp->idCampo);
    			$campoTemp->nombre = $dbCampo->{"NOMBRE"};
    			if($dbCampo->{"ID_TIPO_CAMPO"} == "3"){
    			    $campoTemp->tipo = "catalogo";
    			    $campoTemp->valor = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campoTemp->{"valor"}, "ID")->{"NOMBRE"};
    			} else {
    			    $campoTemp->tipo = "texto";
    			}
    		}
    		
    		$i++;
		}
		
		$registro = new stdClass();
		$registro->archivos = $archivos;
		$registro->id = $idRegistro;
		$registro->{"id_registrante"} = $usuario->{"ID"};
		$registro->{"ficha"} = getRowFromDatabase("fichas", $registroDB->{"ID_FICHA"}, "ID")->{"NOMBRE"};
		$registro->{"id_coleccion"} = $registroDB->{"ID_PERIODO"};
		$registro->status = filterRegisterStatus($registroDB->{"ID_ESTATUS_REGISTRO"});
		$registro->usuario = $usuario->{"NOMBRE"} . " " . $usuario->{"APELLIDOS"};
		$registro->museo = $museo->{"NOMBRE"};
		$registro->secciones = $registroTemp;
		return $registro;
	} else {
		return "NOT_FOUND";
	}
}


?>