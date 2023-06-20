<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 10/07/2019
 * Time: 05:52 PM
 */

include("lib/funcionesAdministrativas.php");

if (isset($_POST['nombreCampo'])) {
    $ficha = addslashes($_POST['ficha']);
    $seccion=addslashes($_POST['seccion']);
    $tipoCampo=addslashes($_POST['tipoCampo']);
    $nombre=addslashes($_POST['nombreCampo']);
   
    $nota = "custom";
    $indice = 100;
    
    
    $idNuevoCampo = nuevoCampo($nombre,$nota,$tipoCampo,0);
    if ($idNuevoCampo != "NO_EXITOSO")
    {
        nuevaSeccionCampo($seccion,$idNuevoCampo,$indice);
        
        if($tipoCampo==3)
        {
            //$array = array("foo", "bar", "hello", "world");
            $arrayOpciones = json_decode($_POST['options']);
          
            
            foreach ($arrayOpciones as $opcion) {
              nuevaOpcion($opcion,$idNuevoCampo,$indice);
            }
            
             redirect("main.php?mr=34");
        }
        
        
    }
    
    if($tipoCampo!=3)
    redirect("main.php?mr=33");

}


function redirect($url, $statusCode = 303)
{
   header('Location: ' . $url, true, $statusCode);
   die();
}

?>