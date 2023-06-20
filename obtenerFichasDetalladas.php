<?php

if(isset($_GET["fichas"])){
    $fichasSolicitadas = json_decode($_GET["fichas"]);
    $fichasDevueltas = array();
    for($i = 0 ; $i < sizeof($fichasSolicitadas) ; $i++){
        array_push($fichasDevueltas, obtenerDetalleDelRegistro($fichasSolicitadas[$i]));
    }
    echo json_encode($fichasDevueltas, JSON_UNESCAPED_UNICODE);
} else {
    echo "[]";
    
}
    

function obtenerDetalleDelRegistro($idRegistro){
    
    require "utils/constants.php";
    require_once "utils/database_utils.php";
    
    $registroDB = getRowFromDatabase($TABLA_REGISTROS, $idRegistro);
    
    if(isset($idRegistro) && $registroDB != "NOT_FOUND"){
        
        //Encontrar el usuario que hizo el registro
        $registrante = getRowFromDatabase($TABLA_USUARIOS, $registroDB->{"ID_USUARIO"}, "ID");
        
        //Encontrar el usuario que hizo el registro
        $museo = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $registroDB->{"ID_MUSEO"}, "ID");
        
		$registroJSON = file_get_contents($DIRECTORIO_REGISTROS . "/" . $idRegistro . ".json");
		if(!$registroJSON){
			return "JSON_FILE_NOT_FOUND";
		}
		
		$seccionProcedencia = null;
		$seccionMedidas = null;
		$seccionMedidasBase = null;
		$archivos = null;

		$registroTemp = json_decode($registroJSON);
		foreach ($registroTemp as $seccionTemp){
		    
		    if(isset($seccionTemp->archivos) && $seccionTemp->archivos != "")
		        $archivos = $seccionTemp->archivos;
		       
    			foreach ($seccionTemp->campos as $campoTemp) {
    			    
    			    $tmpValue = getRowFromDatabase($TABLA_CAMPOS, $campoTemp->idCampo);
    			    $campoTemp->nombre = $tmpValue->{"NOMBRE"};
    			    $tipoDeCampo = "texto";
    			    if($tmpValue->{"ID_TIPO_CAMPO"} == "3"){
    			        $tipoDeCampo = "catalogo";
    			        
    			    }
    			    
    			    
    			    $campoTemp->valorReal = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campoTemp->{"valor"}, "ID")->{"NOMBRE"};
    			    $campoTemp->tipo = $tipoDeCampo;
    			}
		}
		
		$registro = new stdClass();
		$registro->procedencia = $seccionProcedencia;
		$registro->medidas = $seccionMedidas;
		$registro->medidasBase = $seccionMedidasBase;
		$registro->archivos = $archivos;
		$registro->id = $idRegistro;
		$registro->{"id_registrante"} = $registrante->{"ID"};
		$registro->{"ficha"} = getRowFromDatabase("fichas", $registroDB->{"ID_FICHA"}, "ID")->{"NOMBRE"};
		$registro->{"id_coleccion"} = $registroDB->{"ID_PERIODO"};
		$registro->status = filterRegisterStatus($registroDB->{"ID_ESTATUS_REGISTRO"});
		$registro->usuario = $registrante->{"NOMBRE"} . " " . $registrante->{"APELLIDOS"};
		$registro->museo = $museo->{"NOMBRE"};
		$registro->secciones = $registroTemp;
		return $registro;
	} else {
		return "NOT_FOUND";
	}
}


?>