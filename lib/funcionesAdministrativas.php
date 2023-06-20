<?php
ini_set('memory_limit', '2056M');
set_time_limit(500);
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 04/06/2019
 * Time: 04:37 PM
 */
include_once "conexion.php";
include_once "funcionesGenerales.php";

function nuevoUsuario($nombre,$apellidos,$email,$password,$idrol,$idDependencia,$pathImage)
{

    $con = conecta_DB();
    $sql= "INSERT INTO usuarios (NOMBRE,APELLIDOS,CORREO,CONTRASENA,IMAGEN,ID_ROL,ID_ESTADO_USUARIO,ID_OPCION_DEPENDENCIA,ID_ESTATUS_PAPELERA) VALUES('".decodifica($nombre)."','".decodifica($apellidos)."','".decodifica($email)."','".md5(decodifica($password))."','".decodifica($pathImage)."',".$idrol.",1,".$idDependencia.",1)";
    $result = mysqli_query($con,$sql);

    //mysqli_close($con);
    if(!$result){
        echo "No se pudo registrar el nuevo usuario favor de intentarlo mas tarde.";
    }else {
        $mensaje = "Has sido registrado a la plataforma patrimonio del estado de méxico. Tu contraseña es: " . decodifica($password);
        enviarEmailA(decodifica($email), "Registro en Patrimonio del Edo. de México", $mensaje);
        header("Location: main.php?mr=3");
    }

}
function autorizarUsuario($email){
    $con = conecta_DB();
    $sql= "UPDATE usuarios SET ID_ESTADO_USUARIO=2 WHERE CORREO = '".decodifica($email)."'";
    $result = mysqli_query($con,$sql);

    //mysqli_close($con);
    if(!$result){
        echo "No se pudo validar el nuevo usuario favor de intentarlo mas tarde.";
    }else {
        $mensaje = "Tu cuenta en la plataforma de Patrimonio del Estado de México ha sido autorizada.";
        enviarEmailA(decodifica($email), "Cuenta Autorizada - Patrimonio del Estado de México", $mensaje);
        header("Location: main.php?mr=3");
    }
}

function restaurarRegistro($idRegistro){
    $con = conecta_DB();
    $sql= "UPDATE registros SET ID_ESTATUS_PAPELERA=1 WHERE ID = '".$idRegistro."'";
    $sqlPapelera = "DELETE FROM papelera WHERE FOLIO = '". $idRegistro ."';";
    $result = mysqli_query($con,$sql);
    $result = mysqli_query($con,$sqlPapelera);

    //mysqli_close($con);
    if(!$result){
        
        echo "No fue posible restaurar el registro seleccionado, favor de notificarlo a soporte técnico.";
    }else {
        header("Location: main.php?mr=6");
    }
}





function validarUsuario($email,$password)
{
    $con = conecta_DB();
    $sql= "SELECT usuarios.ID , usuarios.CORREO, usuarios.ID_ESTADO_USUARIO, usuarios.NOMBRE,usuarios.IMAGEN, usuarios.APELLIDOS, usuarios.ID_ROL, cat_roles.NOMBRE AS ROL  FROM usuarios INNER JOIN cat_roles ON usuarios.ID_ROL = cat_roles.ID WHERE usuarios.CORREO = '".$email."' AND usuarios.CONTRASENA = '".md5($password)."'";
    $result = mysqli_query($con,$sql);
    $estado=0;
    //mysqli_close($con);
    if(!$result){
        echo "No se pudo validar encontrar al usuario";
    }else{
        if ($read = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $estado = codifica($read["ID_ESTADO_USUARIO"]);
            session_start();
            $_SESSION['id'] = codifica($read["ID"]);
            $_SESSION['email'] = codifica($read["CORREO"]);
            $_SESSION['nombre'] = codifica($read["NOMBRE"]);
            $_SESSION['apellidos'] = codifica($read["APELLIDOS"]);
            $_SESSION['rol'] = codifica($read["ID_ROL"]);
            $_SESSION['rolNombre'] = codifica($read["ROL"]);
            $_SESSION['imagen']=codifica($read["IMAGEN"]);

        }else{
            echo "Datos no validos";
        }
        
        mysqli_free_result($result);

        if($estado==1)
            echo "Usuario registrado pero no validado.";
        else if($estado==2)
        {
            //header("Location: principal.php?mr=5");
            header("Location: main.php?mr=5");
            exit;
        }
    }
}


function eliminarUsuario($email){
    $con = conecta_DB();
    $sql= "DELETE FROM usuarios WHERE CORREO = '".decodifica($email)."'";
    $result = mysqli_query($con,$sql);

    //mysqli_close($con);
    if(!$result){
        echo "No se pudo eliminar al usuario.... ".decodifica($email);
    }else {
        header("Location: main.php?mr=3");
    }
}


function obtenerPermisos($rol)
{
    $con = conecta_DB();
    $sql= "SELECT * FROM cat_permisos_roles WHERE ID_ROL = ".$rol." ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);
    $global_permisos = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $global_permisos[] = $row['ID_PERMISO'];
    }
    mysqli_free_result($result);
    return $global_permisos;
}
function obtenerRoles()
{
    $con = conecta_DB();
    $sql= "SELECT * FROM cat_roles ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);
    $roles = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $object = (object) [
            'id' => $row['ID'],
            'rol' => codifica($row['NOMBRE']),
        ];
        $roles[] = $object;
    }
    mysqli_free_result($result);
    return $roles;
}

function nuevaSeccionCampo($idSeccion,$idCampo,$indice)
{
    $con = conecta_DB();
    $sql= "INSERT INTO secciones_campos (ID_SECCION,ID_CAMPO,INDICE)
            VALUES('".$idSeccion."',".$idCampo.",".$indice.")";
    $result = mysqli_query($con,$sql);

    //mysqli_close($con);
    if(!$result){
        echo "No se pudo registrar la nueva relación seccion-campo favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}

function nuevaOpcionCampo($ficha,$opcion,$campoOrigen,$campoDestino)
{
    $con = conecta_DB();
    $sql= "INSERT INTO opcion_catalago (ID_FICHA,ID_CAMPO_ORIGEN,ID_OPCION,ID_CAMPO)
            VALUES('".$ficha."',".$campoOrigen.",".$opcion.",".$campoDestino.")";
    $result = mysqli_query($con,$sql);

    //mysqli_close($con);
    if(!$result){
        echo "No se pudo registrar la nueva relación seccion-campo favor de intentarlo mas tarde.";
    }else
        echo "EXITOSO";
}
function nuevoCampo($nombre,$nota,$idTipoCampo,$obligatorio)
{
    echo $nombre."desde funciones";
    $con = conecta_DB();
    $sql= "INSERT INTO campos (NOMBRE,NOTA,ID_TIPO_CAMPO,OBLIGATORIO)
            VALUES('".decodifica($nombre)."','".decodifica($nota)."',".$idTipoCampo.",".$obligatorio.")";
    $result = mysqli_query($con,$sql);

   
   //$finalResult;
    if(!$result){
        $finalResult = "NO_EXITOSO";
    }else{
        $finalResult = $con->insert_id;
    }
    //mysqli_close($con);
    return $finalResult;
}

function obtenerIDCampo($nombre,$nota,$idTipoCampo,$obligatorio)
{
    $con = conecta_DB();
    $sql= "SELECT * FROM campos WHERE NOMBRE = '".decodifica($nombre)."' AND NOTA = '".decodifica($nota)."' AND ID_TIPO_CAMPO = ".$idTipoCampo." AND OBLIGATORIO = ".$obligatorio." ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);
    $idCampo="";
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $idCampo = $row['ID'];
    }
    mysqli_free_result($result);
    return $idCampo;
}

function nuevaOpcion($nombre,$idcampo,$indice)
{
    $con = conecta_DB();
    $sql= "INSERT INTO cat_opciones (NOMBRE,ID_CAMPO,INDICE)
            VALUES('".decodifica($nombre)."',".$idcampo.",".$indice.")";
    $result = mysqli_query($con,$sql);

    //mysqli_close($con);
    if(!$result){
        return "No se pudo registrar la nueva opción favor de intentarlo mas tarde.";
    }else
        return "TRUE";
}

function obtenerColeccion($tipo)
{
    $con = conecta_DB();
    if($tipo!=null)
        $sql= "SELECT * FROM periodos  WHERE ID_TIPO_PERIODO = ".$tipo." ORDER BY indice";
    else
        $sql= "SELECT * FROM periodos ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);

    $periodos = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

        $object = (object) [
            'id' => $row['ID'],
            'coleccion' => codifica($row['NOMBRE']),
            'tipo' => $row['ID_TIPO_PERIODO'],
            'fichas' => obtenerFichas($row['ID']),
            ];
        $periodos[] = $object;
    }
    mysqli_free_result($result);
    return $periodos;
}
function obtenerNombrePeriodo($idPeriodo)
{
    $con = conecta_DB();
    $sql= "SELECT * FROM periodos WHERE ID = ".$idPeriodo." ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);
    $nombre="";
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $nombre=codifica($row["NOMBRE"]);
    }
    mysqli_free_result($result);
    return $nombre;
}
function obtenerFichas($idPeriodo)
{
    $con = conecta_DB();
    $sql= "SELECT * FROM fichas WHERE ID_PERIODO = ".$idPeriodo." ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);

    $fichas = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

        $object = (object) [
            'id' => $row['ID'],
            'ficha' => codifica($row['NOMBRE']),
            'imagen'=> codifica($row['IMAGEN']),
        ];
        $fichas[] = $object;
    }
    mysqli_free_result($result);
    return $fichas;
}
function obtenerNombreFicha($idFicha)
{
    $con = conecta_DB();
    $sql= "SELECT * FROM fichas WHERE ID = ".$idFicha." ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);
    $nombre="";

    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

        $nombre=codifica($row["NOMBRE"]);
    }
    mysqli_free_result($result);
    return $nombre;
}
function obtenerSecciones($idFicha){
    $arraySecciones = array();

    $con = conecta_DB();
    $sql= "SELECT * FROM secciones WHERE ID_FICHA = ".$idFicha." AND ID_ESTATUS_SECCION = 1 ORDER BY INDICE ASC ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);

    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $seccion = (object)[
            'id' => $row['ID'],
            'nombre' => codifica($row['NOMBRE']),
            'bndFiles' => $row['ARCHIVOS'],
            'campos' => obtenerCampos($row['ID']),
            'indice' => $row['INDICE'],
        ];

        $arraySecciones[] = $seccion;
    }
    mysqli_free_result($result);
    return $arraySecciones;
}
function obtenerCampos($idSeccion){
    $arrayIdCampos = obtenerIDCamposSeccion($idSeccion);

    $arrayCampos = array();


    $con = conecta_DB();

    foreach ($arrayIdCampos as $idCampo) {
        $sql = "SELECT * FROM campos WHERE ID = " . $idCampo . " ";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            $campo = (object)[
                'id' => $row['ID'],
                'nombre' => codifica($row['NOMBRE']),
                'nota' => codifica($row['NOTA']),
                'tipo' => $row['ID_TIPO_CAMPO'],
                'obligatorio' => $row['OBLIGATORIO'],
                'opciones' => obtenerOpciones($idCampo,$con),
            ];
            $arrayCampos[] = $campo;
        }
        mysqli_free_result($result);
    }
    //mysqli_close($con);
    
    return $arrayCampos;
}
function obtenerOpciones($idCampo,$con)
{
    $arrayOpciones = array();
    $sql = "SELECT * FROM cat_opciones WHERE ID_CAMPO = " . $idCampo . " ORDER BY INDICE;";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $opcion = (object)[
            'id' => $row['ID'],
            'nombre' => codifica($row['NOMBRE']),
        ];
        $arrayOpciones[]=$opcion;
    }
    mysqli_free_result($result);
    return $arrayOpciones;
}
function obtenerCampo($idCampo)
{
    $con = conecta_DB();
    $campo=null;
    $sql = "SELECT * FROM campos WHERE ID = " . $idCampo . " ";
    $result = mysqli_query($con, $sql);
    //mysqli_close($con);
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $campo = (object)[
            'id' => $row['ID'],
            'nombre' => codifica($row['NOMBRE']),
            'nota' => codifica($row['NOTA']),
            'tipo' => $row['ID_TIPO_CAMPO'],
            'obligatorio' => $row['OBLIGATORIO'],
            'opciones' => obtenerOpciones($idCampo,$con),
        ];

    }
    mysqli_free_result($result);
    return $campo;
}

function obtenerTiposCampos()
{
    $arrayTiposCampos = array();

    $con = conecta_DB();
    $sql= "SELECT * FROM cat_tipos_campos WHERE ID  != 3  ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);


    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $tipoCampo = (object)[
            'id' => $row['ID'],
            'nombre' => codifica($row['NOMBRE']),
        ];
        
        $arrayTiposCampos[] = $tipoCampo;
    }
    mysqli_free_result($result);
    return $arrayTiposCampos;
}

function obtenerIDCamposSeccion($idSeccion)
{
    $arrayIdCampos = array();

    $con = conecta_DB();
    $sql= "SELECT ID_CAMPO FROM secciones_campos WHERE ID_SECCION = ".$idSeccion." ORDER BY INDICE ASC ";
    $result = mysqli_query($con,$sql);
    //mysqli_close($con);

    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $arrayIdCampos[] = $row['ID_CAMPO'];
    }
    mysqli_free_result($result);
    return $arrayIdCampos;
}
