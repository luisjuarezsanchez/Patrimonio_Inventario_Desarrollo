<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 10/07/2019
 * Time: 05:52 PM
 * SSH
 */

include_once "lib/conexion.php";
include_once "lib/funcionesGenerales.php";

include("lib/funcionesEditor.php");

if (isset($_POST['btnRevision'])) {
    $folio = addslashes($_POST['folio']);
    $usuario=addslashes($_POST['usuario']);
    $usuarioDestino=addslashes($_POST['usuarioDestino']);
    $coleccion=addslashes($_POST['coleccion']);
    //nuevaNotificacion($folio,$usuarioDestino,$usuario,$coleccion,2,"El borrador ".$folio." ha sido enviado a revisión puedes verlo en la sección de Dashboard",$folio." enviado a revisión");
    registroRevision($folio);
}

if (isset($_POST['btnValidar'])) {
    $folio = addslashes($_POST['folio']);
    $usuario=addslashes($_POST['usuario']);
    $usuarioDestino=addslashes($_POST['usuarioDestino']);
    $coleccion=addslashes($_POST['coleccion']);
    //nuevaNotificacion($folio,$usuarioDestino,$usuario,$coleccion,2,"El registro ".$folio." ha sido validado y aceptado","Registro validado");
    //nuevaNotificacion($folio,$usuario,$usuario,$coleccion,2,"El proceso de validación del registro ".$folio. " fue exitoso.","Has validado un registro");
    registroValidado($folio);
}

if (isset($_POST['btnPapelera'])) {
    $folio = addslashes($_POST['folio']);
    $usuario=addslashes($_POST['usuario']);
    $usuarioDestino=addslashes($_POST['usuarioDestino']);
    $coleccion=addslashes($_POST['coleccion']);
    //nuevaNotificacion($folio,$usuarioDestino,$usuario,$coleccion,2,"El registro ".$folio." ha sido enviado a papelera por el usuario ".$usuario.", solo el usuario que realizó dicha acción puede deshacerla.",$folio." se a mandado a papelera");
    //nuevaNotificacion($folio,$usuario,$usuario,$coleccion,2,"El registro ".$folio." ha sido enviado a papelera, ahora puedes verlo en la sección Papelera.","Has mandado a papelera el registro ".$folio);
    registroPonerPepelera($folio,$usuario);
}

if (isset($_POST['btnRestaurar'])) {
    $folio = addslashes($_POST['folio']);
    $usuario=addslashes($_POST['usuario']);
    $usuarioDestino=addslashes($_POST['usuarioDestino']);
    $coleccion=addslashes($_POST['coleccion']);
    //nuevaNotificacion($folio,$usuarioDestino,$usuario,$coleccion,2,"El registro ".$folio." ha sido restaurado puedes verlo en la sección de Mis Registros","Registro ".$folio." restaurado");
    registroSacarPepelera($folio);
}

if (isset($_POST['btnRechazar'])) {
     $folio = addslashes($_POST['folio']);
    $usuario=addslashes($_POST['usuario']);
    $usuarioDestino=addslashes($_POST['usuarioDestino']);
    $coleccion=addslashes($_POST['coleccion']);
    $mensaje =addslashes($_POST['mensaje']);
    //nuevaNotificacion($folio,$usuarioDestino,$usuario,$coleccion,1,$mensaje,"Registro ".$folio." no aceptado");
    //nuevaNotificacion($folio,$usuario,$usuario,$coleccion,1, $mensaje,"No has aceptado el registro ".$folio." ");
    registroRechazado($folio);
}

if(isset($_POST['btnBorrado']))
{
    $folio = addslashes($_POST['folio']);
    registroBorradoTotal($folio);
}

?>
