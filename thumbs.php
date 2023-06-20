<?php

require 'lib/thumbsGenerator.php';

$dir = "uploads/regFiles";

$files = scandir($dir);

foreach($files as $file){
    
    
    
    if(endsWith($file, "jpeg") || endsWith($file, "jpg")){
        
        $fileInfo = pathinfo("uploads/regFiles/" . $file);
        $thumb = $dir . "/" . $fileInfo["filename"] . "_THUMB." . $fileInfo["extension"];
        if (strpos($file, "_THUMB.") == false){
            if(file_exists($thumb)){
                echo "DONE => " . $thumb . "<br/>";
            } else {
                echo "MISSING => " . $thumb . "<br/>";
                createThumbnail("uploads/regFiles/" . $file, $thumb, 160);
                echo "------------------------------------------> NOW_DONE<br/>";
            }
        }
        //;
        //echo "-> " . $file . " = YES to (" . pathinfo("/home/rua91aoa240w/public_html/patrimonio/uploads/regFiles/" . $file)['extension'] . ") <br/>";
    }
    
}


function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}
?>
