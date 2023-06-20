<?php

include("lib/funcionesAdministrativas.php");

if (isset($_POST['email'])) {
    $email = addslashes($_POST['email']);
    eliminarUsuario($email);
}

?>
