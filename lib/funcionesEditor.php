<?php
ini_set('memory_limit', '2056M');
set_time_limit(500);
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 04/06/2019
 * Time: 04:36 PM
 */


function guardarRegistro($folio,$idUsuario,$fecha,$idPeriodo,$idFicha,$museo, $autor, $numeroDeCat)
//function guardarRegistro($folio,$idUsuario,$fecha,$idPeriodo,$idFicha,$museo/*,$autor*/)
{
    $con = conecta_DB();
    $fecha = explode("-",$folio)[1];
    
    error_log("Autor a punto de ser guardado antiguo... => " . $autor);
    
    /*
    $sql= "INSERT INTO  registros (ID,FECHA,ID_USUARIO,ID_PERIODO,ID_FICHA,ID_ESTATUS_PAPELERA,ID_ESTATUS_REGISTRO,ID_MUSEO,AUTOR)
            VALUES('".decodifica($folio)."','".decodifica($fecha)."',".$idUsuario.",".$idPeriodo.",".$idFicha.",1,1,".$museo.",".$autor.")";
            */
            
    $autor = str_replace("\"","\\\"",$autor);
    $autor = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($autor));
    $sql= "INSERT INTO  registros (ID,FECHA,ID_USUARIO,ID_PERIODO,ID_FICHA,ID_ESTATUS_PAPELERA,ID_ESTATUS_REGISTRO,ID_MUSEO, AUTOR, NUMERODECAT)
            VALUES('".decodifica($folio)."','".decodifica($fecha)."',".$idUsuario.",".$idPeriodo.",".$idFicha.",1,1,".$museo.",'".utf8_decode($autor)."','".utf8_decode($numeroDeCat)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        return "FALLO";
    }else
        return "EXITOSO";
}
function guardarRegistroDash($folio,$idUsuario,$fecha,$idPeriodo,$idFicha,$museo, $autor, $numeroDeCat,$inventario,$nombreObra)
//function guardarRegistro($folio,$idUsuario,$fecha,$idPeriodo,$idFicha,$museo/*,$autor*/)
{
    $con = conecta_DB();
    $fecha = explode("-",$folio)[1];
    
    error_log("Autor a punto de ser guardado desde nueva funcion... => " . $autor);
    
    /*
    $sql= "INSERT INTO  registros (ID,FECHA,ID_USUARIO,ID_PERIODO,ID_FICHA,ID_ESTATUS_PAPELERA,ID_ESTATUS_REGISTRO,ID_MUSEO,AUTOR)
            VALUES('".decodifica($folio)."','".decodifica($fecha)."',".$idUsuario.",".$idPeriodo.",".$idFicha.",1,1,".$museo.",".$autor.")";
            */
            
    $autor = str_replace("\"","\\\"",$autor);
    $autor = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($autor));
    $sql= "INSERT INTO  registros_dash (ID,FECHA,ID_USUARIO,ID_PERIODO,ID_FICHA,ID_ESTATUS_PAPELERA,ID_ESTATUS_REGISTRO,ID_MUSEO, AUTOR, NUMERODECAT,IMAGEN,INVENTARIO,NOMBRE)
            VALUES('".decodifica($folio)."','".decodifica($fecha)."',".$idUsuario.",".$idPeriodo.",".$idFicha.",1,1,".$museo.",'".utf8_decode($autor)."','".utf8_decode($numeroDeCat)."', '0', '".utf8_decode($inventario)."','".utf8_decode($nombreObra)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        return "FALLO";
    }else
        return "EXITOSO";
}

 function extraerImagen ($folio){
    $con = conecta_DB();
    error_log("Entre a la funcion para extraer la imagen del JSON, recibi el folio =>".decodifica($folio));


    $sql = "SELECT ID FROM registros_dash LIMIT 10";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if (!$result){
        return "FALLO";
    }else
        return "EXITOSO"; 
 }
function guardarCambiosRegistro($folio, $museo, $autor)
{
    
    $con = conecta_DB();
    $fecha = explode("-",$folio)[1];
    
    $autor = str_replace("\"","\\\"",$autor);
    $autor = preg_replace('/[ ]{2,}|[\t]/', ' ', trim($autor));
    
    $sql= "UPDATE registros SET ID_MUSEO = ".$museo.", ID_ESTATUS_REGISTRO = 1, AUTOR = \"" . $autor . "\" WHERE ID = '" . decodifica($folio) . "'";
    
    
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        return "FALLO";
    }else
        return "EXITOSO";
}
function obtenerRegistros($idUsuario,$estado)
{
    $con = conecta_DB();
    if($idUsuario!=null) {


        $sql = "SELECT registros.ID,registros.ID_USUARIO, registros.ID_PERIODO, usuarios.NOMBRE as nombre,registros.ID_ESTATUS_REGISTRO, usuarios.APELLIDOS as apellidos, cat_estatus_registros.nombre as estatus,registros.FECHA,periodos.NOMBRE as periodo,fichas.NOMBRE as ficha, registros.ID_FICHA
      FROM registros
      INNER JOIN usuarios ON registros.ID_USUARIO = usuarios.ID
      INNER JOIN cat_estatus_registros ON registros.ID_ESTATUS_REGISTRO = cat_estatus_registros.ID 
      INNER JOIN periodos ON registros.ID_PERIODO = periodos.ID 
      INNER JOIN fichas ON registros.ID_FICHA = fichas.ID
      WHERE registros.ID_USUARIO = " . $idUsuario . " AND registros.ID_ESTATUS_PAPELERA =".$estado." ORDER BY FECHA DESC";
    }
    else
    {
        $sql = "SELECT registros.ID,registros.ID_USUARIO, registros.ID_PERIODO, usuarios.NOMBRE as nombre, registros.ID_ESTATUS_REGISTRO , usuarios.APELLIDOS as apellidos, cat_estatus_registros.nombre as estatus,registros.FECHA,periodos.NOMBRE as periodo,fichas.NOMBRE as ficha, registros.ID_FICHA
      FROM registros
      INNER JOIN usuarios ON registros.ID_USUARIO = usuarios.ID
      INNER JOIN cat_estatus_registros ON registros.ID_ESTATUS_REGISTRO = cat_estatus_registros.ID 
      INNER JOIN periodos ON registros.ID_PERIODO = periodos.ID 
      INNER JOIN fichas ON registros.ID_FICHA = fichas.ID
      WHERE registros.ID_ESTATUS_PAPELERA =".$estado." ORDER BY FECHA DESC";
    }

    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $registros = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $floatFecha =floatval( $row['FECHA']);
        $fecha= gmdate("Y-m-d",$floatFecha);
        if($floatFecha==0)
            $fecha="";
        $object = (object) [
            'folio' => codifica($row['ID']),
            'fecha' => codifica($fecha),
            'periodo' => codifica($row['periodo']),
            'ficha' => codifica( $row['ficha']),
            'estatus' => codifica($row['estatus']),
            'id_estatus'=>codifica($row['ID_ESTATUS_REGISTRO']),
            'id_periodo'=>codifica($row['ID_PERIODO']),
            'id_ficha'=> $row['ID_FICHA'],
            'id_usuario'=>codifica($row['ID_USUARIO']),
            'nombre'=> codifica($row['nombre']),
            'apellidos' => codifica($row['apellidos']),
        ];
        $registros[] = $object;
    }
    mysqli_free_result($result);
    return $registros;
}

function obtenerRegistrosPapelera($idUsuario)
{

    $con = conecta_DB();

        $sql = "SELECT registros.ID,registros.ID_USUARIO, registros.ID_PERIODO,papelera.FECHA_BORRADO as fechaBorrado, usuarios.NOMBRE as nombre,registros.ID_ESTATUS_REGISTRO, usuarios.APELLIDOS as apellidos, cat_estatus_registros.nombre as estatus,registros.FECHA,periodos.NOMBRE as periodo,fichas.NOMBRE as ficha, registros.ID_FICHA
      FROM registros
      INNER JOIN usuarios ON registros.ID_USUARIO = usuarios.ID
      INNER JOIN cat_estatus_registros ON registros.ID_ESTATUS_REGISTRO = cat_estatus_registros.ID 
      INNER JOIN periodos ON registros.ID_PERIODO = periodos.ID 
      INNER JOIN fichas ON registros.ID_FICHA = fichas.ID
      INNER JOIN papelera ON registros.ID = papelera.FOLIO
      WHERE papelera.ID_USUARIO = " . $idUsuario . " ORDER BY papelera.FECHA_BORRADO DESC";


    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $registros = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $floatFecha =floatval( $row['FECHA']);
        $fecha= gmdate("Y-m-d",$floatFecha);

        $floatFechaBorrado =floatval( $row['fechaBorrado']);
        $fechaBorrado= gmdate("Y-m-d",$floatFechaBorrado);
        if($floatFecha==0)
            $fecha="";
        $object = (object) [
            'folio' => codifica($row['ID']),
            'fecha' => codifica($fecha),
            'periodo' => codifica($row['periodo']),
            'ficha' => codifica( $row['ficha']),
            'estatus' => codifica($row['estatus']),
            'id_estatus'=>codifica($row['ID_ESTATUS_REGISTRO']),
            'id_periodo'=>codifica($row['ID_PERIODO']),
            'id_ficha'=> $row['ID_FICHA'],
            'id_usuario'=>codifica($row['ID_USUARIO']),
            'nombre'=> codifica($row['nombre']),
            'apellidos' => codifica($row['apellidos']),
            'fecha_borrado' => codifica($fechaBorrado),
        ];
        $registros[] = $object;
    }
    mysqli_free_result($result);
    return $registros;
}
function obtenerRegistrosFiltros($consulta,$idUsuario,$estado)
{
    $con = conecta_DB();

    if($idUsuario!=null) {
        $sql = "SELECT registros.ID,registros.ID_USUARIO, registros.ID_PERIODO, usuarios.NOMBRE as nombre, registros.ID_ESTATUS_REGISTRO , usuarios.APELLIDOS as apellidos, cat_estatus_registros.nombre as estatus,registros.FECHA,periodos.NOMBRE as periodo,fichas.NOMBRE as ficha, registros.ID_FICHA
      FROM registros
      INNER JOIN usuarios ON registros.ID_USUARIO = usuarios.ID
      INNER JOIN cat_estatus_registros ON registros.ID_ESTATUS_REGISTRO = cat_estatus_registros.ID 
      INNER JOIN periodos ON registros.ID_PERIODO = periodos.ID 
      INNER JOIN fichas ON registros.ID_FICHA = fichas.ID
      WHERE registros.ID_USUARIO = " . $idUsuario . " AND registros.ID_ESTATUS_PAPELERA =" . $estado . " " . $consulta . " ORDER BY FECHA DESC";
    }else{
        $sql = "SELECT registros.ID,registros.ID_USUARIO,registros.ID_PERIODO, usuarios.NOMBRE as nombre, registros.ID_ESTATUS_REGISTRO , usuarios.APELLIDOS as apellidos, cat_estatus_registros.nombre as estatus,registros.FECHA,periodos.NOMBRE as periodo,fichas.NOMBRE as ficha, registros.ID_FICHA
      FROM registros
      INNER JOIN usuarios ON registros.ID_USUARIO = usuarios.ID
      INNER JOIN cat_estatus_registros ON registros.ID_ESTATUS_REGISTRO = cat_estatus_registros.ID 
      INNER JOIN periodos ON registros.ID_PERIODO = periodos.ID 
      INNER JOIN fichas ON registros.ID_FICHA = fichas.ID
      WHERE registros.ID_ESTATUS_PAPELERA =" . $estado . " " . $consulta . " ORDER BY FECHA DESC";

    }

    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $registros = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $floatFecha =floatval( $row['FECHA']);
        $fecha= gmdate("Y-m-d",$floatFecha);
        if($floatFecha==0)
            $fecha="";
        $object = (object) [
            'folio' => codifica($row['ID']),
            'fecha' => codifica($fecha),
            'periodo' => codifica($row['periodo']),
            'ficha' => codifica( $row['ficha']),
            'estatus' => codifica($row['estatus']),
            'id_estatus'=>codifica($row['ID_ESTATUS_REGISTRO']),
            'id_periodo'=>codifica($row['ID_PERIODO']),
            'id_ficha'=> $row['ID_FICHA'],
            'id_usuario'=>codifica($row['ID_USUARIO']),
            'nombre'=> codifica($row['nombre']),
            'apellidos' => codifica($row['apellidos']),
        ];
        $registros[] = $object;
    }
    mysqli_free_result($result);
    return $registros;
}

function registrarArchivoSeccion(){}
function registroRevision($folio)
{
    $con = conecta_DB();
    $sql= "UPDATE registros SET ID_ESTATUS_REGISTRO = 2 WHERE ID = '".decodifica($folio)."'";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        echo "No se puede mandar a revisión el registro.";
    }else
        header("Location: main.php?mr=1");
}

function registroValidado($folio)
{
    $con = conecta_DB();
    $sql= "UPDATE registros SET ID_ESTATUS_REGISTRO = 3 WHERE ID = '".decodifica($folio)."'";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        echo "No se puede mandar a revisión el registro.";
    }else
        header("Location: main.php?mr=5");
}

function registroRechazado($folio)
{
    $con = conecta_DB();
    $sql= "UPDATE registros SET ID_ESTATUS_REGISTRO = 5 WHERE ID = '".decodifica($folio)."'";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        echo "No se puede mandar a revisión el registro.";
    }else
        header("Location: main.php?mr=5");
}
function registroPonerPepelera($folio,$idUsuario)
{
    $con = conecta_DB();
    $fecha = microtime(true);//fecha y hora de microsegundos
    $sql= "INSERT INTO papelera (FOLIO,ID_TIPO,ID_USUARIO,FECHA_BORRADO)
          VALUES ('".decodifica($folio)."',1,".$idUsuario.",'".decodifica($fecha)."')";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        echo "No se puede mandar a papelera el registro.";
    }else {
        $con = conecta_DB();
        $sql = "UPDATE registros SET ID_ESTATUS_PAPELERA = 2 WHERE ID = '" . decodifica($folio) . "'";
        $result = mysqli_query($con, $sql);

        mysqli_close($con);
        if (!$result) {
            echo "No se puede mandar a papelera el registro.";
        } else
            header("Location: main.php?mr=1");
    }
}
function registroSacarPepelera($folio)
{
    $con = conecta_DB();
    $sql= "DELETE  FROM papelera WHERE FOLIO = '".decodifica($folio)."'";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        echo "No se puede borrar de papelera el registro.";
    }else {
        $con = conecta_DB();
        $sql = "UPDATE registros SET ID_ESTATUS_PAPELERA = 1 WHERE ID = '" . decodifica($folio) . "'";
        $result = mysqli_query($con, $sql);

        mysqli_close($con);
        if (!$result) {
            echo "No se puede sacar de papelera el registro.";
        } else
            header("Location: main.php?mr=5");
    }
}
function registroBorradoTotal($folio)
{

    borrarJSONRegistro($folio);

    $con = conecta_DB();
    $sql= "DELETE  FROM papelera WHERE FOLIO = '".decodifica($folio)."'";
    $result = mysqli_query($con,$sql);

    mysqli_close($con);
    if(!$result){
        echo "No se puede borrar de papelera el registro.";
    }else {
        $con = conecta_DB();
        $sql = "DELETE  FROM registros WHERE ID = '" . decodifica($folio) . "'";
        $result = mysqli_query($con, $sql);

        mysqli_close($con);
        if (!$result) {
            echo "No se pudo cambiar el estado del archivo";
        } else
            header("Location: main.php?mr=6");
    }

 }


function borrarJSONRegistro($folio)
{
    $path_reg_directory="reg/";

    $fh = fopen( $path_reg_directory.$folio.".json","r");


        $contenido=  fread($fh,filesize($path_reg_directory.$folio.".json"));
        $jsonRegistro = json_decode($contenido,true);
        fclose($fh);

        foreach ($jsonRegistro as $seccion) {
            if($seccion['archivos']!=="")
                foreach ($seccion['archivos'] as $archivo)
                    unlink($archivo) or die("No se puedo borrar el objeto digital");
        }

        unlink($path_reg_directory.$folio.".json") or die("No se pudo borrar el archivo de registro");

        return true;
}

function obtenerRelacionesCampos($idFicha)
{
    $con = conecta_DB();

    $sql = "SELECT * FROM opcion_catalago
      WHERE ID_FICHA = " . $idFicha . " ";


    $result = mysqli_query($con,$sql);
    mysqli_close($con);

    $relaciones = array();
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
        $object = (object) [
            'id_opcion'=> $row['ID_OPCION'],
            'id_campo' => $row['ID_CAMPO'],
            'id_campo_origen' => $row['ID_CAMPO_ORIGEN'],
        ];
        $relaciones[] = $object;
    }
    mysqli_free_result($result);
    return $relaciones;
}

?>
