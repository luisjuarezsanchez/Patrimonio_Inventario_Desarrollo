<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/06/2019
 * Time: 10:26 PM
 */
include("lib/funcionesAdministrativas.php");

if (isset($_POST['txtEmail']) && isset($_POST['txtPassword'])) {
    $usuario = addslashes($_POST['txtEmail']);
    $password = addslashes($_POST['txtPassword']);

    if ($usuario != "" && $password != "") {
        $usuario = trim($usuario);
        $password = trim($password);
    }

    validarUsuario($usuario,$password);
}
?>