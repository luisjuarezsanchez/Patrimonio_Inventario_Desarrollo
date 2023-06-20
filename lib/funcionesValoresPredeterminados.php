<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/06/2019
 * Time: 05:01 PM
 */
include_once "conexion.php";

function nuevoRol($rol)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_roles (NOMBRE)
            VALUES('".decodifica($rol)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el usuario favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevoPermiso($permiso)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_permisos (NOMBRE)
            VALUES('".decodifica($permiso)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el permiso favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevaRelacionRolPermiso($rol,$permiso){
    $con = conecta_DB();
    $sql= "INSERT INTO cat_permisos_roles (ID_ROL,ID_PERMISO)
            VALUES(".$rol.",".$permiso.")";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el permiso favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}

function nuevoTipoCampo($campo,$numCaracteres)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_tipos_campos (NOMBRE,NUM_CARACTERES)
            VALUES('".decodifica($campo)."',".$numCaracteres.")";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo tipo de campo favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function obtenerIDCampo($nombre,$nota,$idTipoCampo,$obligatorio)
{
    $con = conecta_DB();
    $sql= "SELECT * FROM campos WHERE NOMBRE = '".decodifica($nombre)."' AND NOTA = '".decodifica($nota)."' AND ID_TIPO_CAMPO = ".$idTipoCampo." AND OBLIGATORIO = ".$obligatorio." ";
    $result = mysqli_query($con,$sql);
    mysqli_close($con);
    $idCampo="";
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $idCampo = $row['ID'];
    }
    mysqli_free_result($result);
    return $idCampo;
}

function nuevoEstadoPapelera($nombre)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_estatus_papelera (NOMBRE)
            VALUES('".decodifica($nombre)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo estado para la papelera favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}

function nuevoTipoRegistroPapelera($nombre)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_tipos_papelera (NOMBRE)
            VALUES('".decodifica($nombre)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo tipo para la papelera favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevoEstadoUsuario($nombre)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_estado_usuario (NOMBRE)
            VALUES('".decodifica($nombre)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo estado para los usuarios favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevoEstadoRegistro($nombre){
    $con = conecta_DB();
    $sql= "INSERT INTO cat_estatus_registros (NOMBRE)
            VALUES('".decodifica($nombre)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo estado para los usuarios favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}


function nuevoTipoPeriodo($nombre)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_tipos_periodos (NOMBRE)
            VALUES('".decodifica($nombre)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo tipo de periodo favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevoPeriodo($nombre,$tipo)
{
    $con = conecta_DB();
    $sql= "INSERT INTO periodos (NOMBRE,ID_TIPO_PERIODO)
            VALUES('".decodifica($nombre)."',".$tipo.")";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo tipo de periodo favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevaFicha($nombre,$periodo){
$con = conecta_DB();
    $sql= "INSERT INTO fichas (NOMBRE,ID_PERIODO)
            VALUES('".decodifica($nombre)."',".$periodo.")";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar la nueva ficha favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}


function nuevoEstadoSeccion($nombre)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_estatus_seccion (NOMBRE)
            VALUES('".decodifica($nombre)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo estado para las secciones favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevaSeccion($nombre,$ficha,$bndArchivos)
{
    $con = conecta_DB();
    $sql= "INSERT INTO secciones (NOMBRE,ID_FICHA,ID_ESTATUS_SECCION,ARCHIVOS)
            VALUES('".decodifica($nombre)."',".$ficha.",1,".$bndArchivos.")";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar la nueva ficha favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}


function nuevaRelacionOpcionCampo($idFicha,$idSeccion,$idOpcion,$idCampo)
{
    /*
    $con = conecta_DB();
    $sql= "INSERT INTO opcion_catalago (NOMBRE,ID_FICHA,ID_ESTATUS_SECCION)
            VALUES('".decodifica($nombre)."',".$ficha.",1)";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar la nueva ficha favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
    */
}
?>