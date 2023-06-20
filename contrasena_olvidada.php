<?php

if(isset($_POST["mail"])){
    
    require_once "utils/constants.php";
    require_once "utils/database_utils.php";
    
    $correo = $_POST["mail"];
    $registroActual = getRowFromDatabase($TABLA_USUARIOS, $correo, "CORREO");
    if($registroActual != "NOT_FOUND" && !empty((array) $registroActual)){
        $newPassword = "pass" . rand(1,10000);
        $updateResult = simpleUpdateRegister($TABLA_USUARIOS, "CONTRASENA", md5($newPassword), "CORREO" , $correo);
        
        if($updateResult == "SUCCESS"){
            //Enviar mail y decir que listo...
            $mensaje = "Tu clave de acceso ha sido renovada de acuerdo a tu solicitud, la nueva clave de acceso es : " . $newPassword;
            enviarEmailA($correo, "Renovar clave de acceso - Patrimonio del Edo. de Mex ",  $mensaje);
            echo "Se envió un correo electrónico con tu nueva contraseña.";
        } else {
            echo "El servidor no pudo atender tu petición en este momento :(";
        }
        
        
        
        //var_dump($registroActual->{"ID"});
    } else {
        echo "Correo electrónico no registrado.";
    }
    
} else {
    echo "Debes indicar tu correo electrónico.";
}

function enviarEmailA($destinatario, $asunto, $mensaje){
    $headers = 'From: admin@creadoresedomex.com.mx' . "\r\n" .
        'Reply-To: admin@creadoresedomex.com.mx' . "\r\n" .
        'Content-Type: text/html; charset=UTF-8' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($destinatario, $asunto, $mensaje, $headers);
}

?>