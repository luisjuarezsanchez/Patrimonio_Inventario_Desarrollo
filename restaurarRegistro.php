<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 01/07/2019
 * Time: 03:01 PM
 */

include("lib/funcionesAdministrativas.php");

if (isset($_POST['folio'])) {
    $idRegistro = $_POST['folio'];
    restaurarRegistro($idRegistro);
}
?>



