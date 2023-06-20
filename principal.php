<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 09/06/2019
 * Time: 10:54 PM
 */
include "lib/validarPermisos.php";
//selección de una opción del menu
$periodo = 0;
$path="reg/";

if (isset($_GET['p'])){
    $periodo = $_GET['p'];
}
$misRegistros = 0;
if (isset($_GET['mr'])){
    $misRegistros = $_GET['mr'];
}
$fichaSeleccionada=0;
if(isset($_GET['f'])){
    $fichaSeleccionada = $_GET['f'];
}

//En el caso de guardar el registro de una ficha
if (isset($_POST["btnGuardar"])){


    $registro =array();

    $Secciones = obtenerSecciones($fichaSeleccionada);
    foreach ($Secciones as $seccion)
    {

        $seccionObject = (object) [
            'nombreSeccion'=>$seccion->nombre,
            'campos'=>saveFields($seccion->campos),
            'archivos'=>($seccion->bndFiles)?saveFiles(decodifica($_POST['folio'])):"",
        ];

        if(sizeof($seccionObject->campos) > 0 || $seccionObject->archivos !=="" && sizeof($seccionObject->archivos) > 0)
            $registro[]=$seccionObject;
    }


    if(guardarRegistro( decodifica($_POST["folio"]),$idUsuario,decodifica($_POST["fecha"]),$periodo,$fichaSeleccionada,decodifica($_POST["4"]))==="EXITOSO")
    {
        $jsonValores = json_encode($registro,JSON_UNESCAPED_UNICODE);
        $fh = fopen( $path. $_POST['folio'].".json", 'w');
        fwrite($fh, $jsonValores);
        fclose($fh);
         $folio= decodifica($_POST['folio']);
        nuevaNotificacion($_POST['folio'],$idUsuario,$idUsuario,$periodo,2,"El borrador ".$folio." ha sido creado, puedes verlo en la sección Mis Registros","Borrador ".$folio." guardado");

        $periodo=0;
        $fichaSeleccionada=0;
        $misRegistros=1;

        header ("Location: principal.php?mr=1");
    }


}

//En el caso de guardar cambios de un registro
if (isset($_POST["btnActualizar"])){


    $registro =array();

    $Secciones = obtenerSecciones($fichaSeleccionada);
    foreach ($Secciones as $seccion)
    {

        $seccionObject = (object) [
            'nombreSeccion'=>$seccion->nombre,
            'campos'=>saveFields($seccion->campos),
            'archivos'=>($seccion->bndFiles)?saveFiles(decodifica($_POST['folio'])):"",
        ];

        if(sizeof($seccionObject->campos) > 0 || $seccionObject->archivos !=="" && sizeof($seccionObject->archivos) > 0)
            $registro[]=$seccionObject;
    }


    if(guardarCambiosRegistro( decodifica($_POST["folio"]),decodifica($_POST["4"]))==="EXITOSO")
    {
        unlink($path. $_POST['folio'].".json") or die("No se pudo borrar el archivo de registro");
        $jsonValores = json_encode($registro,JSON_UNESCAPED_UNICODE);
        $fh = fopen( $path. $_POST['folio'].".json", 'w');
        fwrite($fh, $jsonValores);
        fclose($fh);
         $folio= decodifica($_POST['folio']);
        nuevaNotificacion($_POST['folio'],$idUsuario,$idUsuario,$periodo,2,"Los cambios realizados en el registro ".$folio." han sido guardados con exito",$folio." cambios guardados");
      
        $periodo=0;
        $fichaSeleccionada=0;
        $misRegistros=1;

        header ("Location: principal.php?mr=1");
    }


}
function saveFields($campos)
{
    $arrayValores = array();
    foreach ($campos as $campo) {
        if($_POST[$campo->id] !== "0" && $_POST[$campo->id] !== "") {
            $object = (object)[
                'idCampo' => $campo->id,
                'valor' => $_POST[$campo->id],
            ];
            $arrayValores[] = $object;
        }
    }
    return $arrayValores;
}
function saveFiles($folio)
{
    $arrayFiles = array();
    $target_dir="uploads/regFiles/";
    // Count total files
    $countfiles = count($_FILES['archivos']['name']);


    // Looping all files
    for($i=0;$i<$countfiles;$i++){
        $target_file = $target_dir . basename($_FILES["archivos"]["name"][$i]);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $filename=$target_dir;
        $filetype="";


        if($fileType === "jpg" || $fileType === "jpeg") {
            $filename .= $folio . $i . ".jpeg";
            $filetype="image";
        }
        else if($fileType === "pdf") {
            $filename .= $folio . $i . ".pdf";
            $filetype="pdf";
        }

        // Upload file
        if(move_uploaded_file($_FILES['archivos']['tmp_name'][$i],$filename))
        {
            $arrayFiles[]=$filename;
        }

    }

    return $arrayFiles;
}




function obtenerDetalleDelRegistro($idRegistro){
    
    require "utils/constants.php";
    require "utils/database_utils.php";
    
    $registroDB = getRowFromDatabase($TABLA_REGISTROS, $idRegistro);
    
    if(isset($idRegistro) && $registroDB != "NOT_FOUND"){
        
        //Encontrar el usuario que hizo el registro
        $registrante = getRowFromDatabase($TABLA_USUARIOS, $registroDB->{"ID_USUARIO"}, "ID");
        
        //Encontrar el usuario que hizo el registro
        $museo = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $registroDB->{"ID_MUSEO"}, "ID");
        
		$registroJSON = file_get_contents($DIRECTORIO_REGISTROS . "/" . $idRegistro . ".json");
		if(!$registroJSON){
			return "JSON_FILE_NOT_FOUND";
		}
		
		$seccionProcedencia = null;
		$seccionMedidas = null;
		$seccionMedidasBase = null;
		$archivos = null;

		$registroTemp = json_decode($registroJSON);
		foreach ($registroTemp as $seccionTemp){
		    
		    if(isset($seccionTemp->archivos) && $seccionTemp->archivos != "")
		        $archivos = $seccionTemp->archivos;
		    if($seccionTemp->{"nombreSeccion"} == "Medidas"){
		        $seccionMedidas = $seccionTemp;
		    } else if($seccionTemp->{"nombreSeccion"} == "Medidas Base/Marco"){
		        $seccionMedidasBase = $seccionTemp;
		    } else if ($seccionTemp->{"nombreSeccion"} == "Procedencia"){
		        $seccionProcedencia = $seccionTemp;
		    } else
    			foreach ($seccionTemp->campos as $campoTemp) {
    			    
    			    $tmpValue = getRowFromDatabase($TABLA_CAMPOS, $campoTemp->idCampo);
    			    $campoTemp->nombre = $tmpValue->{"NOMBRE"};
    			    $tipoDeCampo = "texto";
    			    if($tmpValue->{"ID_TIPO_CAMPO"} == "3"){
    			        $tipoDeCampo = "catalogo";
    			        $campoTemp->valorReal = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campoTemp->{"valor"}, "ID")->{"NOMBRE"};
    			    }
    			    $campoTemp->tipo = $tipoDeCampo;
    			}
		}
		
		$registro = new stdClass();
		$registro->procedencia = $seccionProcedencia;
		$registro->medidas = $seccionMedidas;
		$registro->medidasBase = $seccionMedidasBase;
		$registro->archivos = $archivos;
		$registro->id = $idRegistro;
		$registro->id_registrante= $registrante->{"ID"};
		$registro->id_coleccion = $registroDB->{"ID_PERIODO"};
		$registro->status = filterRegisterStatus($registroDB->{"ID_ESTATUS_REGISTRO"});
		$registro->usuario = $registrante->{"NOMBRE"} . " " . $registrante->{"APELLIDOS"};
		$registro->museo = $museo->{"NOMBRE"};
		$registro->secciones = $registroTemp;
		return $registro;
	} else {
		return "NOT_FOUND";
	}
}


?>
<!DOCTYPE html>
<html lang="es-MX">
<head>


    <title>Patrimonio Cultural Estado de México</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta http-equiv="cache-control" content="max-age-0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Augment Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <!-- Graph CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
    <!-- lined-icons -->
    <link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/amcharts.js"></script>
    <script src="js/serial.js"></script>
    <script src="js/light.js"></script>
    <script src="js/radar.js"></script>
    
    <script src="js/html2canvas.js"></script>	
    
    
    <link href="css/barChart.css" rel='stylesheet' type='text/css' />
    <link href="css/fabochart.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/ficha.css" type='text/css' />

    <!--clock init-->
    <script src="js/css3clock.js"></script>
    <!--Easy Pie Chart-->
    <!--skycons-icons-->
    <script src="js/skycons.js"></script>

    <script src="js/jquery.easydropdown.js"></script>
    <script type="text/javascript">
        $(document).on("keypress", 'form', function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                e.preventDefault();
                return false;
            }
        });


    </script>
</head>

<body>



<div class="page-container">
    <!--/content-inner-->
    <div class="left-content">
        <div class="inner-content">
            <!-- header-starts -->
            <div class="header-section">
                <!--menu-right-->
                <div class="top_menu">
                    <div class="main-search">
                        <form>
                            <input type="text" value="Buscar" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'Search';}" class="text"/>
                            <input type="submit" value="">
                        </form>
                        <div class="close"><img src="images/cross.png" /></div>
                    </div>
                    <div class="srch"><button></button></div>
                    <script type="text/javascript">
                        $('.main-search').hide();
                        $('button').click(function (){
                                $('.main-search').show();
                                $('.main-search text').focus();
                            }
                        );
                        $('.close').click(function(){
                            $('.main-search').hide();
                        });
                    </script>
                    <!--/profile_details-->
                    <div class="profile_details_left">
                        <ul class="nofitications-dropdown">
                            <li class="dropdown note dra-down">

                            </li>
                            <li class="dropdown note">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope-o"></i> <span class="badge">3</span></a>


                                <ul class="dropdown-menu two first">
                                    <li>
                                        <div class="notification_header">
                                            <h3>3 mensajes nuevos  </h3>
                                        </div>
                                    </li>
                                    <li><a href="#">
                                            <div class="user_img"><img src="images/1.jpg" alt=""></div>
                                            <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet</p>
                                                <p><span>Hace 1 hora</span></p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a></li>
                                    <li class="odd"><a href="#">
                                            <div class="user_img"><img src="images/in.jpg" alt=""></div>
                                            <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet </p>
                                                <p><span>Hace 1 hora</span></p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a></li>
                                    <li><a href="#">
                                            <div class="user_img"><img src="images/in1.jpg" alt=""></div>
                                            <div class="notification_desc">
                                                <p>Lorem ipsum dolor sit amet </p>
                                                <p><span>Hace 1 hora</span></p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a></li>
                                    <li>
                                        <div class="notification_bottom">
                                            <a href="#">See all messages</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <?php $notificacionesPendientes=  obtenerNotificacionesPendientes($idUsuario);?>
                            <li class="dropdown note">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell-o"></i> <span class="badge"><?php echo sizeof($notificacionesPendientes);?></span></a>

                                <ul class="dropdown-menu two">
                                    <li>
                                        <div class="notification_header">
                                            <h3><?php echo sizeof($notificacionesPendientes);?> notificaciones nuevas</h3>
                                        </div>
                                    </li>
                                      <?php
                                    foreach ($notificacionesPendientes as $notificacion){
                                        ?>
                                        <li><a href="#">
                                                <div class="user_img"><img src="images/in.jpg" alt=""></div>
                                                <div class="notification_desc">
                                                    <p><?php echo $notificacion->titulo;?></p>
                                                    <p><span><?php echo $notificacion->fecha;?></span></p>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                        ?>
                                    <li>
                                        <div class="notification_bottom">
                                            <a href="principal.php?mr=7">Ver todas</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown note">
                                <a href="salir.php" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="lnr lnr-power-switch"></i></a>

                            </li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <!--//profile_details-->
                </div>
                <!--//menu-right-->
                <div class="clearfix"></div>
            </div>
            <!-- //header-ends -->
            <div class="outter-wp">
                
                
                
                
                
                
                
                
                
                <?php
                if($misRegistros==5)
                {
                ?>
                <!--custom-widgets-->
                <div class="custom-widgets">
                    <div class="row-one">
                        <div class="col-md-3 widget">
                            <div class="stats-left ">
                                <h5>Colección</h5>
                                <h4> Prehistórico</h4>
                            </div>
                            <div class="stats-right">
                                <label>90</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-mdl">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Arqueológico</h4>
                            </div>
                            <div class="stats-right">
                                <label> 85</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-thrd">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Virreinal</h4>
                            </div>
                            <div class="stats-right">
                                <label>51</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-last">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Arte moderno</h4>
                            </div>
                            <div class="stats-right">
                                <label>30</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                </div>
                <br>
                <div class="custom-widgets">

                    <div class="row-one">
                        <div class="col-md-3 widget states-five">
                            <div class="stats-left ">
                                <h5>Específicas</h5>
                                <h4>Culturas populares</h4>
                            </div>
                            <div class="stats-right">
                                <label>90</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-six">
                            <div class="stats-left">
                                <h5>Específicas</h5>
                                <h4>Numismática</h4>
                            </div>
                            <div class="stats-right">
                                <label> 85</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-seven">
                            <div class="stats-left">
                                <h5>Específicas</h5>
                                <h4>Historia natural</h4>
                            </div>
                            <div class="stats-right">
                                <label>51</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>
                        <div class="col-md-3 widget states-eight">
                            <div class="stats-left">
                                <h5>Colección</h5>
                                <h4>Objeto histórico</h4>
                            </div>
                            <div class="stats-right">
                                <label>30</label>
                            </div>
                            <div class="clearfix"> </div>
                        </div>

                        <div class="clearfix"> </div>
                    </div>
                </div>
                <!--//custom-widgets-->
                <?php
                } else if($misRegistros==25){
                    include "detalleDeFicha.php";
                }
                ?>
                <!--/candile-->
                <div class="candile">
                    <div class="candile-inner">
                        <?php
                        if($misRegistros==1)
                        {
                            include "misRegistros.php";
                        }else if($misRegistros==2)
                        {
                            include "mostrarRegistro.php";
                        }else if($misRegistros==3)
                        {
                            include "mostrarUsuarios.php";
                        }else if($misRegistros==4)
                        {
                            include "nuevoUsuario.php";
                        }else if($misRegistros==5)
                        {
                            include "mostrarRegistrosCompletos.php";
                        }else if($misRegistros==6)
                        {
                            include "misRegistrosPapelera.php";
                        }else if($misRegistros==7)
                            include "notificaciones.php";




                        if($periodo!==0 && $fichaSeleccionada==0)
                        {
                            include "mostrarFichas.php";
                        }else if ($fichaSeleccionada!==0 && $misRegistros == 0)
                        {
                            ?>
                            <script type="text/javascript">
                                var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;
                            </script>
                            <?php
                        $relacionesValoresCampos = obtenerRelacionesCampos($fichaSeleccionada);

                        $microtime =microtime(true);//fecha y hora de microsegundos
                        $now = DateTime::createFromFormat('U.u',$microtime );//fecha con formato

                        ?>

                            <!--Inicia registro-->
                            <div class="graph">
                                <div id="cabecera">
                                    <h3 class="title">Ficha de registro</h3>

                                </div>
                                <div id="formulario">
                                    <form role="form" action="principal.php?op=2&p=<?php echo $periodo; ?>&f=<?php echo $fichaSeleccionada; ?>" method="post" enctype="multipart/form-data">

                                        <div class="fila">
                                        <div class="col-md-12"><label><?php echo obtenerNombreFicha($fichaSeleccionada);?></label></div>
                                        <div class="clearfix"></div>
                                        </div>


                                        <div class="fila gris">
                                            <div class="col-md-6">
                                                <label for="fecha">Fecha </label>
                                                <input type="text" id="fecha" name="fecha" size="25" readonly class="x"  value="<?php echo $now->format("d-m-Y");?>"
                                                       maxlength="50">
                                            </div>
                                            <div class="col-md-6" id="colFolio">
                                                <label for="folio">Folio </label>
                                                <input type="text" id="folio" name="folio" size="25" readonly class="x" value="<?php echo "REG".$fichaSeleccionada."-".$microtime;?>"
                                                       maxlength="50" >
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>


                                        <?php
                                        $arraySecciones = obtenerSecciones($fichaSeleccionada);
                                        foreach ($arraySecciones as $seccion)
                                        {
                                            $dosCampos="";
                                            $counterCampos=0;
                                            ?>
                                            <div id="cabecera2">

                                                <h3 class="sub-title"><?php echo  $seccion->nombre;?></h3>
                                            </div>

                                            <?php
                                            $sizeFields = sizeof($seccion->campos );
                                            $counterFields=0;
                                            $counterRows=0;
                                            foreach ($seccion->campos as $campo) {
                                                if($campo->obligatorio)
                                                $campo->nombre = "<span style='color: red; '>* </span>".$campo->nombre;
                                                if($campo->tipo==1)
                                                {
                                                    $nombrePeriodo="";
                                                    if($campo->id==2)
                                                        $nombrePeriodo= obtenerNombrePeriodo($periodo);
                                                    $obligatorio= ($campo->obligatorio)?"required": '';
                                                    $dosCampos.="<div class='col-md-6' id='col".$campo->id."'>
                                                            <div class='col-md-4'><label for='".$campo->id."'>".$campo->nombre."</label></div>
                                                            <div class='col-md-8'><input id='".$campo->id."' name='".$campo->id."' value='".$nombrePeriodo."' type='text' class='x' ".$obligatorio."></div>
                                                        </div>";
                                                    $counterCampos++;
                                                }else if ($campo->tipo==2)
                                                {
                                                    $obligatorio= ($campo->obligatorio)?"required": '';
                                                    $dosCampos.="<div class='col-md-12' id='col".$campo->id."'>
                                                            <div class='col-md-2'><label for='".$campo->id."'>".$campo->nombre."</label></div>
                                                            <div class='col-md-10'><textarea id='".$campo->id."' name='".$campo->id."'  rows='6' class='x' ".$obligatorio."></textarea></div>
                                                        </div>";

                                                        $counterCampos = 2;
                                                }else if ($campo->tipo==3)
                                                {
                                                     $opcionesString="";
                                                    if($campo->id != 67 && $campo->id != 68 && $campo->id != 69)
                                                    foreach ($campo->opciones as $opcion) {
                                                        $opcionesString .=" <option value='". $opcion->id."'>".$opcion->nombre."</option>";
                                                    }
                                                    $obligatorio= ($campo->obligatorio)?"required": '';
                                                    $bndSeleccion="";
                                                    if($campo->id==4 || $campo->id== 5)
                                                        $bndSeleccion="disabled";
                                                    $dosCampos.="<div class='col-md-6' id='col".$campo->id."'>
                                                            <div class='col-md-4'><label for='".$campo->id."'>".$campo->nombre."</label></div>
                                                            <div class='col-md-8'>
                                                              <div class='caja'>
                                                                    <select id='".$campo->id."' name='".$campo->id."' size='1' class='selector' onchange='camposValor(".$campo->id.",".$seccion->id.")' ".$obligatorio.">
                                                                        <option value='' selected ".$bndSeleccion.">Selecciona una opci&oacuten</option>
                                                                        ".$opcionesString."
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>";
                                                    $opcionesString="";
                                                    $counterCampos++;
                                                }

                                                $filaColor="";
                                                if($counterCampos==2)
                                                {

                                                    if($counterRows%2!=0)
                                                        $filaColor="gris";
                                                    ?>

                                                    <div class="fila <?php echo $filaColor;?>">
                                                        <?php
                                                        echo $dosCampos;
                                                        $counterCampos=0;
                                                        $dosCampos="";
                                                        ?>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <?php
                                                    $counterRows++;
                                                }

                                                $counterFields++;
                                                if($counterFields == $sizeFields && $dosCampos!=="")
                                                {
                                                    if($counterRows%2!=0)
                                                        $filaColor="gris";
                                                    ?>
                                                    <div class="fila <?php echo $filaColor;?>">
                                                        <?php
                                                        echo $dosCampos;
                                                        $counterCampos=0;
                                                        $dosCampos="";
                                                        ?>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <?php
                                                    $counterFields=0;
                                                }
                                            }

                                            if($seccion->bndFiles == 1)
                                            {
                                                ?>
                                                <div class="fila gris">
                                                    <div class="col-md-6">
                                                        <div class="col-md-4"><label>Objeto digital</label></div>
                                                        <div class="col-md-8">
                                                            <a class="btn gris one">Cargue aquí</a>
                                                        </div>
                                                    </div><!--Termina columna 1-->
                                                    <div class="col-md-6">
                                                        <div class="col-md-4"><label></label></div>
                                                        <div class="col-md-8">
                                                            <input type="file" class="form-control a_demo_four" id="archivos" name="archivos[]" multiple="multiple" accept="application/pdf, image/jpeg, image/jpg" >
                                                        </div><!--Termina columna 2-->
                                                    </div>

                                                </div><!--Termina fila gris-->
                                                <script>
                                                    $("#archivos").on("change", function() {

                                                        if ($("#archivos")[0].files.length > 5) {

                                                            alert("Solo puedes subir un máximo de 5 archivos");
                                                            $("#archivos").val('');
                                                        }else
                                                        {
                                                            var totalSize=0;
                                                            var files = $('#archivos')[0].files;
                                                            for (var i = 0; i < files.length; i++) {
                                                                // calculate total size of all files
                                                                totalSize += files[i].size;
                                                            }
                                                            if(totalSize>104857600)
                                                            {
                                                                alert("El tamaño total es de 10MB");
                                                                $("#archivos").val('');
                                                            }
                                                        }
                                                    });
                                                </script>
                                                <?php
                                            }
                                            ?>
                                            <?php

                                        }
                                        ?>
                                        
                                    </form>
                                </div>
                            </div>
                            <div class="botones">
                                <button type="submit" name="btnGuardar" id="btnGuardar" class="btn guardar">GUARDAR</button>
                            </div>
                            <div class="clearfix"></div>
                        <script type="text/javascript">
                            var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;
                            arrayRelaciones.forEach(function (relacion) {
                                document.getElementById("col"+relacion.id_campo).style.display = "none";
                            });
                            </script>
                        <?php
                        }
                        ?>


                    </div>

                </div>
                <!--/candile-->

            </div>
            <!--//outer-wp-->
        </div>


        <!--footer section start-->
        <footer>
            <img class="centrar" src="images/logos_foot.png" width="450" height="51">
        </footer>
        <!--footer section end-->
    </div>

    <!--//content-inner-->
    <!--/sidebar-menu-->
    <div class="sidebar-menu">
        <header class="logo">
            <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="#"> <span id="logo"> <img src="images/logo_patrimonio.png"></span>
                <!--<img id="logo" src="" alt="Logo"/>-->
            </a>
        </header>
        <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
        <!--/down-->
        <div class="down">
            <a href="#"><img src="<?php echo $imagen;?>" style="width: 40%;"></a>
            <a href="#"><span class=" name-caret"><?php echo $nombre." ".$apellidos;?></span></a>
            <p><?php echo $rolNombre;?></p>
            <ul>
                <li><a class="tooltips" href="#"><span>Perfil</span><i class="lnr lnr-user"></i></a></li>
                <li><a class="tooltips" href="salir.php"><span>Salir</span><i class="lnr lnr-power-switch"></i></a></li>
            </ul>
        </div>
        <!--//down-->
        <div class="menu">
            <ul id="menu" >
                <li><a href="principal.php?mr=5"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>

<?php
if (! empty($global_permisos)) {
    if (array_search(3, $global_permisos) !== false) {
        ?>
        <li id="menu-academico"><a href="#"><i class="fa fa-pencil-square-o"></i> <span>Mis registros</span> <span
                        class="fa fa-angle-right" style="float: right"></span></a>
            <ul id="menu-comunicacao-sub">
                <li id="menu-mensagens" style="width:120px"><a href="#">Nuevo <i class="fa fa-angle-right"
                                                                                            style="float: right; margin-right: -8px; margin-top: 2px;"></i></a>
                    <ul id="menu-mensagens-sub">
                    <?php
                    $colecciones = obtenerColeccion(1);
                    foreach ($colecciones as $coleccion) {
                        if(sizeof($coleccion->fichas)>1) {
                            ?>
                            <li id="menu-mensagens-enviadas" style="width:240px"><a
                                        href="principal.php?p=<?php echo $coleccion->id; ?>"><?php echo $coleccion->coleccion; ?></a>
                            </li>
                            <?php
                        }else{
                            ?>
                            <li id="menu-mensagens-enviadas" style="width:240px"><a
                                        href="principal.php?p=<?php echo $coleccion->id; ?>&f=<?php echo $coleccion->fichas[0]->id;?>"><?php echo $coleccion->coleccion; ?></a>
                            </li>
                            <?php
                        }

                    }
                    ?>
                        <li id="menu-mensagens" style="min-width:180px;"><a href="#">Colecciones específicas <i class="fa fa-angle-right" style="float: right; margin-right: -8px; margin-top: 2px;"></i></a>
                            <ul id="menu-mensagens-sub-sub" >
                                <?php
                                $colecciones = obtenerColeccion(2);
                                foreach ($colecciones as $coleccion) {
                                    if(sizeof($coleccion->fichas)>1) {
                                        ?>
                                        <li id="menu-mensagens-enviadas" style="width:240px"><a
                                                    href="principal.php?p=<?php echo $coleccion->id; ?>"><?php echo $coleccion->coleccion; ?></a>
                                        </li>
                                        <?php
                                    }else{
                                        ?>
                                        <li id="menu-mensagens-enviadas" style="width:240px"><a
                                                    href="principal.php?p=<?php echo $coleccion->id; ?>&f=<?php echo $coleccion->fichas[0]->id;?>"><?php echo $coleccion->coleccion; ?></a>
                                        </li>
                                        <?php
                                    }

                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li id="menu-arquivos"><a href="principal.php?mr=1">Mis registros</a></li>
            </ul>
        </li>
        <?php
    }
    if (array_search(7, $global_permisos) !== false) {

        ?>
        <li><a href="principal.php?mr=3"><i class="fa fa-users"></i> <span>Usuarios</span></a></li>
        <?php
    }
    if (array_search(10, $global_permisos) !== false) {
        ?>

        <li id="menu-academico"><a href="#"><i class="fa fa-check-square-o"></i> <span>Campos</span> <span
                        class="fa fa-angle-right" style="float: right"></span></a>
            <ul id="menu-academico-sub">
                <li id="menu-academico-avaliacoes"><a href="#">Nuevo campo</a></li>
                <li id="menu-academico-boletim"><a href="#">Mis campos</a></li>
            </ul>
        </li>
        <?php
    }

    if (array_search(11, $global_permisos) !== false) {
        ?>
        <li id="menu-academico"><a href="#"><i class="fa fa-files-o"></i><span>Catálogos</span><span
                        class="fa fa-angle-right" style="float: right"></span></a>
            <ul>
                <li><a href="#"> Lorem ipsum</a></li>
                <li><a href="#"> lorem ipsum</a></li>
                <li><a href="#"> Lorem ipsum</a></li>

            </ul>
        </li>
        <?php
    }
}
                ?>
                <li id="menu-academico" ><a href="principal.php?mr=6"><i class="fa fa-trash-o"></i> <span>Papelera</span> </a>

                </li>



            </ul>


        </div>
    </div>
    <div class="clearfix"></div>
</div>

<script>
    var toggle = true;

    $(".sidebar-icon").click(function() {
        if (toggle)
        {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position":"absolute"});
        }
        else
        {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function() {
                $("#menu span").css({"position":"relative"});
            }, 400);
        }

        toggle = !toggle;
    });
</script>
<!--js -->
<link rel="stylesheet" href="css/vroom.css">
<script type="text/javascript" src="js/vroom.js"></script>
<script type="text/javascript" src="js/TweenLite.min.js"></script>
<script type="text/javascript" src="js/CSSPlugin.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>

<script src="js/jspdf.js"></script>

<script type="text/javascript">
    
    function generarDetallePDF(){
        html2canvas($('#detalle-de-ficha')[0]).then(function(canvas) {
            var img = canvas.toDataURL("image/jpg");
            var doc = new jsPDF();
            var aspectRatio = $('#detalle-de-ficha').height() / $('#detalle-de-ficha').width();
            var width = doc.internal.pageSize.getWidth();
            var height = doc.internal.pageSize.getHeight();
            height = aspectRatio * width;
            doc.addImage(img,'JPEG',0,15, width, height);
            doc.save('test.pdf');
        });
    }
    
    
</script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>


<script type="text/javascript">


    function  camposValor(id,nombreSeccion) {

        if(nombreSeccion == 6 || nombreSeccion == 13 || nombreSeccion ==20 || nombreSeccion ==39 || nombreSeccion ==48 || nombreSeccion ==57 )
            relacionOpcionOpcion(id);
        else
            relacionOpcionCampo(id);


    }

    function relacionOpcionOpcion(id) {
        var valorSeleccionado = document.getElementById(id).value;
        var paisSeleccionado = 0;
        var estadoSeleccionado = 0;
        var municipioSeleccionado = 0;
        var campoMunicipio= document.getElementById("68");
        var campoLocalidad= document.getElementById("69");

        if (id == 66) {
            paisSeleccionado = valorSeleccionado;
            campoMunicipio.innerHTML="<option value='' selected >Selecciona una opci&oacuten</option>";
            campoLocalidad.innerHTML="<option value='' selected >Selecciona una opci&oacuten</option>";
        }
        else if (id == 67) {

            estadoSeleccionado = valorSeleccionado;
            campoLocalidad.innerHTML="<option value='' selected >Selecciona una opci&oacuten</option>";
        }
    else
        {

        municipioSeleccionado = valorSeleccionado;
    }

    if(id!=69){
    
            $.get("lib/obtenerDatosdeOpciones.php", { campoOrigen:id,pais:paisSeleccionado, estado:estadoSeleccionado,municipio:municipioSeleccionado },function(data){
    
                var arrayValores= JSON.parse(data);
    
                var campoDestino= document.getElementById(arrayValores.campoDestino);
                campoDestino.innerHTML="<option value='' selected >Selecciona una opci&oacuten</option>";
                arrayValores.valores.forEach(function (valor) {
                    campoDestino.innerHTML+="<option value='"+valor.id_valor+"' >"+valor.nombre+"</option>"
                });
    
    
            });
    }

    }

    function relacionOpcionCampo(id) {
        var valorSeleccionado = document.getElementById(id).value;

        arrayRelaciones.forEach(function (relacion) {

            if(id == relacion.id_campo_origen)
            {
                if(valorSeleccionado == relacion.id_opcion)
                    document.getElementById("col"+relacion.id_campo).style.display = "block";
                else{
                    document.getElementById("col"+relacion.id_campo).style.display = "none";
                    limpiarRelacionesHijas(relacion.id_campo);
                    document.getElementById(relacion.id_campo).value="";

                }
            }
        });
    }

    function limpiarRelacionesHijas(id) {
        var valorSeleccionado = document.getElementById(id).value;

        arrayRelaciones.forEach(function (relacion) {
            if(id == relacion.id_campo_origen) {
                if (valorSeleccionado == relacion.id_opcion) {
                    document.getElementById("col" + relacion.id_campo).style.display = "none";
                    document.getElementById(relacion.id_campo).value = "";
                }
            }
        });
    }
    
    
    function openRelatedFile(nodo){
        console.log("abrir => ",$(nodo).attr("archivo"));
    }
</script>
</body>
</html>
