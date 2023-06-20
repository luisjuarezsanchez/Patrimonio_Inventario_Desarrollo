<?php 

$registroActual = obtenerDetalleDelRegistro($_GET["idr"]);

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

















     
<!DOCTYPE HTML>
<html>
<head>
<title>Patrimonio Cultural Estado de México</title>
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
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
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
<!--clock init-->
<script src="js/css3clock.js"></script>
<!--Easy Pie Chart-->
<!--skycons-icons-->
<script src="js/skycons.js"></script>

<script src="js/jquery.easydropdown.js"></script>
<!--//skycons-icons-->
<script src="js/jquery.chocolat.js"></script>
		<link rel="stylesheet" href="css/chocolat.css" type="text/css" media="screen" charset="utf-8">
		<!--light-box-files -->
		<script type="text/javascript">
		$(function() {
			$('.gallery-bottom a').Chocolat();
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
										
							<li class="dropdown note">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell-o"></i> <span class="badge">5</span></a>

									<ul class="dropdown-menu two">
										<li>
											<div class="notification_header">
												<h3>5 notificaciones nuevas</h3>
											</div>
										</li>
										<li><a href="#">
											<div class="user_img"><img src="images/in.jpg" alt=""></div>
										   <div class="notification_desc">
											<p>Lorem ipsum dolor sit amet</p>
											<p><span>Hace 1 hora</span></p>
											</div>
										  <div class="clearfix"></div>	
										 </a></li>
										 <li class="odd"><a href="#">
											<div class="user_img"><img src="images/in5.jpg" alt=""></div>
										   <div class="notification_desc">
											<p>Lorem ipsum dolor sit amet </p>
											<p><span>Hace 1 hora</span></p>
											</div>
										   <div class="clearfix"></div>	
										 </a></li>
										 <li><a href="#">
											<div class="user_img"><img src="images/in8.jpg" alt=""></div>
										   <div class="notification_desc">
											<p>Lorem ipsum dolor sit amet </p>
											<p><span>Hace 1 hora</span></p>
											</div>
										   <div class="clearfix"></div>	
										 </a></li>
										 <li>
											<div class="notification_bottom">
												<a href="#">Ver todas</a>
											</div> 
										</li>
									</ul>
							</li>	
							<li class="dropdown note">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="lnr lnr-power-switch"></i></a>
										
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
									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="dashboard.html">Dashboard</a></li>
											<li class="active">Detalle</li>
										</ol>
									   </div>
								    <!--//sub-heard-part-->
                                    				<div class="row ">
                                                    	<div class="col-md-6">
                                                        	<h3 class="sub-tittle pro">Prehistórico</h3>
                                                        </div>
                                                        <div class="col-md-6">
                                                        	<div class="botones">
                                                            	<a class="btn btn-exportar" onclick="generarDetallePDF()"><i class="fa fa-file-text-o"></i> PDF</a>
                                                            	<a class="btn btn-editar"> <i class="fa fa-pencil"></i> Editar</a>
                                                                <a class="btn btn-success"><i class="fa fa-check"></i> Aprobar</a>
                                                                <a class="btn btn-rechazar"><i class="fa fa-times"></i> Rechazar</a>
                                                                <a class="btn btn-papelera"><i class="fa fa-trash-o"></i> Eliminar</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="candile" id="detalle-de-ficha"> 
                                                    	<div class="franja prehistorico"></div>
														<div class="candile-inner">
									       			<div class="row">
                                                    	<div class="info-first">
                                                            <div class="col-md-12">
                                                                <div class="col-md-2">
                                                                    <?php
                                                                    if(isset($registroActual->archivos)){
                                                                        $imagen = null;
                                                                        foreach($registroActual->archivos as $archivo){
                                                                            if(substr_compare($archivo, ".jpeg", strlen($archivo)-strlen(".jpeg"), strlen(".jpeg")) === 0
                                                                            || substr_compare($archivo, ".jpg", strlen($archivo)-strlen(".jpg"), strlen(".jpg")) === 0
                                                                            || substr_compare($archivo, ".png", strlen($archivo)-strlen(".png"), strlen(".png")) === 0){
                                                                                $imagen = $archivo;
                                                                                break;
                                                                            }
                                                                        }
                                                                        if(isset($imagen))
                                                                            echo '<div class="foto"><img src="'.$imagen.'" alt=" " /></div>';
                                                                    }
                                                                    
                                                                    ?>
                                                                	
                                                                </div>
                                                             	<div class="col-md-5">
                                                                	<div class="info-creacion">
                                                                            <div class="about-info-p">
                                                                                <strong>Museo</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo $registroActual->museo; ?></p>
																			</div>
                                                                            <div class="about-info-p">
                                                                                <strong>Ubicación del museo</strong>
                                                                                <br>
                                                                                <p class="text-muted">Pendiente</p>
																			</div>
                                                                            <div class="about-info-p">
                                                                                <strong>ID de registro</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo $registroActual->id; ?></p>
																			</div>
                                                                     </div><!--//info-creacion-->
                                                                 </div>
																 <div class="col-md-5">
                                                                    <div class="info-creacion">
                                                                            <div class="about-info-p">
                                                                                <strong>Estatus</strong>
                                                                                <br>
                                                                                <p class="text-muted"><span class="label label-sm label-success"><?php echo $registroActual->status; ?></span></p>
																			</div>
                                                                            <div class="about-info-p">
                                                                                <strong>Fecha de creación</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php
                                                                                
                                                                                echo date("Y-m-d",explode("-",$registroActual->id)[1]);
                                                                                
                                                                                ?></p>
																			</div>
                                                                            <div class="about-info-p">
                                                                                <strong>Usuario de registro</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo $registroActual->usuario; ?></p>
																			</div>
                                                                            
                                                                     </div><!--//info-creacion-->
                                                                </div><!--//termina col-md-4-->
                                                        </div><!--//termina col-md-12-->
                                                   
                                                    </div><!--//termina info first-->
                                                    
                                                    </div><!--//termina row-->
                                                    <hr class="divider">

                                                    	
                                                        	<div class="row">
                                                        	    
                                                        	    
                                                        	    
                                                                <div class="col-md-4">
                                                                	<h3 class="title-inner">Galería</h3>
																	<div class="main-grid3">
                                                                    	<div class="gallery separacion">
                                                                    	<div class="gallery-bottom grid">
                                                                    	    
                                                                    	    <?php
                                                                    	    if(isset($registroActual->archivos)){
                                                                    	        foreach($registroActual->archivos as $archivo){
                                                                    	            echo '<div class="col-md-3 grid">
                                                                            	<a href="'.$archivo.'" rel="title" class="b-link-stripe b-animate-go  thickbox">
                                                                                    <figure class="effect-oscar">
                                                                                    <img src="'.$archivo.'" alt=""/>	
                                                                                    </figure>
																				</a>
                                                                            </div>';
                                                                    	        }    
                                                                    	    }
                                                                    	    
                                                                    	    ?>
                                                                    	    
                                                                        </div>
                                                                    </div>
                                                                    <div style="clear:both;"></div>
                                                                    </div>
                                                                	
                                                                    <div style="margin-bottom:15px; display:block;"></div>
                                                                	<h3 class="title-inner">Procedencia</h3>
																	<div class="main-grid3">
																     	<div class="p-20">
																     	    
																     	    <?php
																     	        if(isset($registroActual->procedencia)){
																     	            require "utils/constants.php";
																     	            $campos = $registroActual->procedencia->campos;
																     	            foreach($campos as $campo){
                                                                                        echo '<div class="about-info-p">';
                                                                                        echo '<strong>'. getRowFromDatabase($TABLA_CAMPOS, $campo->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campo->valor, "ID")->{"NOMBRE"}.'</p>';
                                                                                        echo '</div>';
                                                                                    }
																     	        }
																     	    ?>
																     	    
																     	    <!--
                                                                            <div class="about-info-p">
                                                                                <strong>País</strong>
                                                                                <br>
                                                                                <p class="text-muted">México</p>
                                                                            </div>
                                                                            -->
																		</div>
																 	</div><!--/main-grid-->
                                                                    <h3 class="title-inner">Medidas</h3>
																	<div class="main-grid3">
                                                                    	<div class="col-md-6">
                                                                        	<div class="p-20">
                                                                        	    <?php
            																	    if(isset($registroActual->medidas)){
            																	        require "utils/constants.php";
            																	        $campos = $registroActual->medidas->campos;
            																	        for($i = 0 ; $i < count($campos) ; $i++){
            																	            if($i % 2 == 0){
                                                                                    	            echo '<div class="about-info-p"><strong>'. getRowFromDatabase($TABLA_CAMPOS, $campos[$i]->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. $campos[$i]->valor .'</p></div>';
                                                                                    	    }
            																	        }
            																	    }
            																	?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                        	<div class="p-20">
                                                                        	    <?php
            																	    if(isset($registroActual->medidas)){
            																	        $campos = $registroActual->medidas->campos;
            																	        for($i = 0 ; $i < count($campos) ; $i++){
            																	            if($i % 2 != 0){
                                                                                    	            echo '<div class="about-info-p"><strong>'. getRowFromDatabase($TABLA_CAMPOS, $campos[$i]->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. $campos[$i]->valor .'</p></div>';
                                                                                    	    }
            																	        }
            																	    }
            																	?>
                                                                        	    <!--
                                                                                <div class="about-info-p m-b-0">
                                                                                    <strong>Ancho</strong>
                                                                                    <br>
                                                                                    <p class="text-muted">1234</p>
                                                                                </div>   
                                                                                -->
                                                                            </div>
                                                                        </div>   
                                                                        <div style="clear:both"></div>    
																 	</div><!--/main-grid-->
																 	<h3 class="title-inner">Medidas Base/Marco</h3>
																	<div class="main-grid3">
																     	<div class="p-20">
																     	    
																     	    <?php
																     	        if(isset($registroActual->medidasBase)){
																     	            $campos = $registroActual->medidasBase->campos;
																     	            foreach($campos as $campo){
                                                                                        echo '<div class="about-info-p">';
                                                                                        echo '<strong>'. getRowFromDatabase($TABLA_CAMPOS, $campos[$i]->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. (($campo->tipo == "catalogo") ? $campo->valorReal : $campo->valor) .'</p>';
                                                                                        echo '</div>';
                                                                                    }
																     	        }
																     	    ?>
																     	    
																     	    <!--
                                                                            <div class="about-info-p">
                                                                                <strong>País</strong>
                                                                                <br>
                                                                                <p class="text-muted">México</p>
                                                                            </div>
                                                                            -->
																		</div>
																 	</div><!--/main-grid-->
                                                                </div><!--/col-md-4-->
                                                                
                                                                
                                                                
                                                                
                                                                <div class="col-md-8">
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    <?php
                                                                    $seccionesDeRegistro = $registroActual->secciones;
                                                                    foreach($seccionesDeRegistro as $seccion){
                                                                        if($seccion->nombreSeccion != "Medidas" && $seccion->nombreSeccion != "Procedencia" && $seccion->nombreSeccion != "Medidas Base/Marco"){
                                                                        echo '<div class="row inside"><h3 class="title-inner-v2">'. $seccion->nombreSeccion .'</h3><div class="row">';
                                                                        $camposDeSeccion = $seccion->campos;
                                                                        foreach($camposDeSeccion as $campo){
                                                                            if(isset($campo->valor) && $campo->valor != ""){
                                                                                
                                                                            
                                                                                echo '<div class="col-md-6"><div class="about-info-p m-b-0">';
                                                                                echo '<strong>'. $campo->nombre.'</strong><br><p class="text-muted">'. (($campo->tipo == "catalogo") ? $campo->valorReal : $campo->valor) .'</p>';
                                                                                echo '</div></div>';
                                                                            }
                                                                        }
                                                                        echo '</div></div><hr class="divider">';
                                                                        }
                                                                        
                                                                    }
                                                                    ?>
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                    
                                                                   
                                                                    
                                                                    
                                                                
                                                                
                                                                </div><!--/col-md-8-->
                                                            </div>
                                                         </div> <!--/candile inner-->  
													</div>
													<!--/candile-->
													
												</div>
										<!--//outer-wp-->
									</div>
									 <!--footer section start-->
										<footer>
									    <img class="centrar" src="images/logos_foot.png" width="450" height="51"></footer>
									<!--footer section end-->
								</div>
							
				<!--//content-inner-->
			<!--/sidebar-menu-->
				<div class="sidebar-menu">
					<header class="logo">
					<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="index.html"> <span id="logo"> <img src="images/logo_patrimonio.png"></span> 
					<!--<img id="logo" src="" alt="Logo"/>--> 
				  </a> 
					</header>
					<div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
			<!--/down-->
							<div class="down">	
									  <a href="index.html"><img src="images/admin.jpg"></a>
									  <a href="index.html"><span class=" name-caret">Vania Ramírez</span></a>
									 <p>Administrador</p>
									<ul>
									<li><a class="tooltips" href="index.html"><span>Perfil</span><i class="lnr lnr-user"></i></a></li>
										<li><a class="tooltips" href="index.html"><span>Salir</span><i class="lnr lnr-power-switch"></i></a></li>
										</ul>
							</div>
							   <!--//down-->
                               <div class="menu">
                                        <ul id="menu" >
                                            <li><a href="index.html"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
                                             <li id="menu-academico" ><a href="#"><i class="fa fa-th-large"></i> <span> Colecciones</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                                               <ul id="menu-academico-sub" >
                                                    <li id="menu-academico-avaliacoes" ><a href="#"> Lorem impsum</a></li>
                                                    <li id="menu-academico-boletim" ><a href="#">Lorem ipsum</a></li>
                                                    <li id="menu-academico-avaliacoes" ><a href="#">Calendar</a></li>
                                                    
                                              </ul><!--/Termina submenú-->
                                             </li>
                                             <li id="menu-comunicacao" ><a href="#">
                                             	<i class="fa fa-pencil-square-o"></i> <span>Mis registros</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                                                <ul id="menu-comunicacao-sub" >
                                                    <li id="menu-mensagens"  ><a href="#">Nuevo <i class="fa fa-angle-right" style="float: right; margin-right: -8px; margin-top: 2px;"></i></a>
                                                        <ul id="menu-mensagens-sub" >
                                                            <li id="menu-mensagens-enviadas" style="width:180px;" ><a href="#">Prehistórico</a></li>
                                                            <li id="menu-mensagens-recebidas" style="width:180px;" ><a href="#">Arqueológico</a></li>
                                                            <li id="menu-mensagens-recebidas" style="width:180px;" ><a href="#">Virreinato</a></li>
                                                            <li id="menu-mensagens-recebidas" style="width:180px;" ><a href="#">Arte moderno</a></li>
                                                            <li id="menu-mensagens" style="min-width:180px;"><a href="#">Colecciones específicas <i class="fa fa-angle-right" style="float: right; margin-right: -8px; margin-top: 2px;"></i></a>
                                                                <ul id="menu-mensagens-sub-sub" >
                                                                    <li id="menu-mensagens-enviadas" style="width:180px;"><a href="#">Artes populares</a></li>
                                                                    <li id="menu-mensagens-recebidas" style="width:180px;" ><a href="#">Numismática</a></li>
                                                                    <li id="menu-mensagens-recebidas" style="width:180px;" ><a href="#">Historia natural</a></li>
                                                                </ul>
                                                            </li>
                                                   
                                                        </ul>
                                           			</li>
                                            		<li id="menu-arquivos" ><a href="#l">Mis registros</a></li>
                                          		</ul><!--/Termina submenú-->
                                                
                                                
                                             </li>
                                        <li><a href="#"><i class="fa fa-users"></i> <span>Usuarios</span></a></li>
                                        <li id="menu-academico" ><a href="#"><i class="fa fa-check-square-o"></i> <span>Campos</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                                              <ul id="menu-academico-sub" >
                                                <li id="menu-academico-avaliacoes" ><a href="#">Nuevo campo</a></li>
                                                <li id="menu-academico-boletim" ><a href="#">Mis campos</a></li>
                                              </ul>
                                         </li>
                                         <li id="menu-academico"><a href="#"><i class="fa fa-files-o"></i><span>Catálogos</span><span class="fa fa-angle-right" style="float: right"></span></a>
                                           <ul>
                                            <li><a href="#"> Lorem ipsum</a></li>
                                            <li><a href="#"> lorem ipsum</a></li>
                                            <li><a href="#"> Lorem ipsum</a></li>
                                        
                                          </ul>
                                        </li>
                                        <li id="menu-academico" ><a href="#"><i class="fa fa-trash-o"></i> <span>Papelera</span> </a>
                                             
                                         </li>
                                        
                                        
                                            
                                      </ul><!--/Termina menú-->
                               
							  	
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
</body>
</html>











