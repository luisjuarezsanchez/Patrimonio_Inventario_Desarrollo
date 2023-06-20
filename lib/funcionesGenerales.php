<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/06/2019
 * Time: 07:15 PM
 */

if(isset($_GET["estadoNotificacion"])) {
    include_once "conexion.php";
    if($_GET["estadoNotificacion"]==1)
    {
        $notifPendientes=json_encode(obtenerNotificaciones((int)$_GET["idusuario"],1));
        cambiarEstadoNotificacion((int)$_GET['idusuario']);
        echo $notifPendientes;
    }else
       echo json_encode(obtenerNotificaciones((int)$_GET["idusuario"],$_GET["estadoNotificacion"]));
}

function codifica($cadena){
    return utf8_encode($cadena);
}

function decodifica($cadena){
    return utf8_decode($cadena);
}

function enviarEmailA($destinatario, $asunto, $mensaje){
    $headers = 'From: admin@creadoresedomex.com.mx' . "\r\n" .
        'Reply-To: admin@creadoresedomex.com.mx' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($destinatario, $asunto, $mensaje, $headers);
}

/*function nuevaNotificacion($folio,$idUsuarioDestino,$idUsuarioOrigen,$idColeccion,$idTipo,$mensaje,$titulo)
{
    
    $titulo = str_replace (" se a ", " se ha ", $titulo);
    $con = conecta_DB();
    $fecha =microtime(true);//fecha y hora de microsegundos
    $sql= "INSERT INTO notificaciones (ID_REGISTRO,ID_USUARIO_DESTINO,ID_USUARIO_ORIGEN,ID_COLECCION,ID_ESTATUS,FECHA,ID_TIPO,MENSAJE,TITULO)
            VALUES('".decodifica($folio)."',".$idUsuarioDestino.",".$idUsuarioOrigen.",".$idColeccion.",1,'".decodifica($fecha)."',".$idTipo.",'".decodifica($mensaje)."','".decodifica($titulo)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);

    if(!$result){
        echo "No se pudo registrar el nuevo tipo de campo favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}*/

function obtenerNotificaciones($idUsuario,$estadoNotificacion)
{
    
    $con = conecta_DB();

    $aprobados="";
    if($estadoNotificacion == 3)
    {
        $estadoNotificacion=2;
        $aprobados=" AND cat_estatus_registros.ID = 3";
    }
    
    $sql = "SELECT notificaciones.ID_REGISTRO,
                    usuarios.NOMBRE AS nombreUsuario,
                    usuarios.APELLIDOS as apellidosUsuario,
                    usuarios.IMAGEN as imagenPerfil,
                    periodos.NOMBRE,
                    notificaciones.ID_ESTATUS as estadoNotificacion,
                    notificaciones.FECHA,
                    notificaciones.ID_COLECCION as idColecc,
                    notificaciones.ID_TIPO as tipoNotificacion,
                    notificaciones.MENSAJE,
                    notificaciones.TITULO,
                     cat_estatus_registros.NOMBRE as estadoNombre 
                     FROM notificaciones
                    inner join usuarios on usuarios.ID = notificaciones.ID_USUARIO_ORIGEN 
                    inner join registros on registros.ID = notificaciones.ID_REGISTRO
                    inner join periodos on periodos.ID = notificaciones.ID_COLECCION 
                    INNER join cat_estatus_registros on cat_estatus_registros.ID = registros.ID_ESTATUS_REGISTRO
                    WHERE notificaciones.ID_USUARIO_DESTINO = ".$idUsuario." AND notificaciones.ID_ESTATUS = ".$estadoNotificacion." ".$aprobados."
                    ORDER by notificaciones.FECHA DESC";

    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $notificaciones = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $floatFecha =floatval( $row['FECHA']);
        $fecha= gmdate("Y-m-d",$floatFecha);
        $object = (object) [
            'registro'=>codifica($row['ID_REGISTRO']),
            'nombreUsuario'=> codifica($row['nombreUsuario']),
            'apellidosUsuario'=>codifica($row['apellidosUsuario']),
            'imagen'=>codifica($row['imagenPerfil']),
            'coleccion'=>codifica($row['NOMBRE']),
            'idColeccion'=>$row['idColecc'],
            'estado'=>$row['estadoNotificacion'],
            'fecha'=>codifica($fecha),
            'tipo'=>$row['tipoNotificacion'],
            'mensaje'=>codifica($row['MENSAJE']),
            'titulo'=>codifica($row['TITULO']),
            'estadoRegistro'=>codifica($row['estadoNombre']),

        ];
        $notificaciones[] = $object;
    }
    
    mysqli_free_result($result);
    return $notificaciones;
}

function obtenerNotificacionesPendientes($idUsuario)
{
    $con = conecta_DB();
    $sql = "SELECT notificaciones.TITULO, notificaciones.FECHA, usuarios.IMAGEN as imagenPerfil FROM notificaciones
                    inner join usuarios on usuarios.ID = notificaciones.ID_USUARIO_ORIGEN
                    WHERE notificaciones.ID_USUARIO_DESTINO = ".$idUsuario." and notificaciones.ID_ESTATUS = 1  ORDER by notificaciones.FECHA DESC";

    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $notificaciones = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $floatFecha =floatval( $row['FECHA']);
        $fecha= gmdate("Y-m-d",$floatFecha);
        $object = (object) [
            'titulo'=>codifica($row['TITULO']),
            'fecha'=>codifica($fecha),
            'imagen'=>codifica($row['imagenPerfil']),
        ];
        $notificaciones[] = $object;
    }
    mysqli_free_result($result);
    return $notificaciones;
}
function cambiarEstadoNotificacion($idUsuario)
{
    $con = conecta_DB();
    $sql = "UPDATE notificaciones SET ID_ESTATUS = 2 WHERE ID_USUARIO_DESTINO = " . $idUsuario . " AND ID_ESTATUS =1";
    $result = mysqli_query($con, $sql);

    mysqli_close($con);
    if (!$result) {
        echo "No se finalizó realizar la acción";
    }
}
?>