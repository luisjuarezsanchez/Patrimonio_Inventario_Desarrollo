<?php

$registroActual = obtenerDetalleDelRegistro($_GET["idr"]);
//var_dump($registroActual->{"id_ficha"});

function obtenerNombreDeFicha($idDeFicha){
    include_once("utils/database_utils.php");
    return getRowFromDatabase("fichas", $idDeFicha, "ID")->{"NOMBRE"};
}

function filtrarColorDeColeccion($idColeccion){
    switch ($idColeccion){
        case "1":
            return "prehistorico";
        case "2":
            return "arqueologico";
        case "3":
            return "virreinal";
        case "4":
            return "artemoderno";
        case "5":
            return "culturaspopulares";
        case "6":
            return "numismatica";
        case "7":
            return "historianatural";
        case "8":
            return "objetohistorico";
            
    }
}
?>



<div class="loader-container"><p>Generando reporte PDF</p><div class="lds-dual-ring"></div></div>   



									<!--sub-heard-part-->
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="main.php?mr=5">Dashboard</a></li>
											<li class="active">Detalle</li>
										</ol>
									   </div>
								    <!--//sub-heard-part-->
                                    				<div class="row ">
                                                    	<div class="col-md-6">
                                                        	<h3 class="sub-tittle pro"><?php echo obtenerNombreDeFicha($registroActual->{"id_ficha"}); ?></h3>
                                                        </div>
                                                        <div class="col-md-6">
                                                        	<div class="botones">
                                                        	      <form role="form" action="accionRegistro.php" method="post">
                                                        	            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $registroActual->id; ?>" name="folio" readonly>
                                                                            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $idUsuario; ?>" name="usuario" readonly>
                                                                            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $registroActual->id_registrante; ?>" name="usuarioDestino" readonly>
                                                                            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $registroActual->id_coleccion; ?>" name="coleccion" readonly>
                                                            	<a class="btn btn-exportar" onclick="descargarReportePDF('<?php echo $registroActual->id; ?>')"><i class="fa fa-file-text-o"></i> PDF</a>
                                                            	<?php if (array_search(4, $global_permisos) !== false && $rol!=1 && $registroActual->status !== "Validado" || array_search(4, $global_permisos) !== false &&  $registroActual->status === "Guardado" ) {
                                                                    ?>
                                                            	<a class="btn btn-editar" href="main.php?mr=2&fi=<?php echo $registroActual->id;?>&p=<?php echo $registroActual->id_coleccion; ?>&f=<?php echo $registroActual->id_ficha; ?>"> <i class="fa fa-pencil"></i> Editar</a>
                                                            	  <?php
                                                                }
                                                               if (array_search(5, $global_permisos) !== false && $registroActual->status === "Revisión") {
                                                                ?>
                                                            	<a class="btn btn-comentar" data-toggle="modal" data-target="#ModalComentar"> <i class="fa fa-comment"></i> Comentar</a>
                                                                <?php
                                                                }
                                                                ?>
                                                              
                                                                <?php
                                                                if (array_search(13, $global_permisos) !== false && $registroActual->status === "Revisión") {
                                                                ?>
                                                                <button class="btn btn-success" name="btnValidar"><i class="fa fa-check"></i> Aprobar</button>
                                                                    <?php
                                                                }
                                                                
                                                                if(array_search(6, $global_permisos) !== false && $registroActual->status !== "Validado" || $registroActual->status === "Guardado")
                                                                {
                                                                ?>
                                                                <button class="btn btn-papelera" name="btnPapelera"><i class="fa fa-trash-o"></i> Eliminar</button>
                                                                <?php
                                                                }
                                                                ?>
                                                                </form>
                                                            </div>
                                                            <!--Inicia MODAL-->
                                                            <div class="modal fade modal-dialog-centered" id="ModalComentar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
																<div class="modal-dialog ">
																	<div class="modal-content">
                                                                        <form role="form" action="accionRegistro.php" method="post">
																		<div class="modal-header">
																			<button type="button" class="close second" data-dismiss="modal" aria-hidden="true">×</button>
																			<h2 class="modal-title">Agregar comentario</h2>
																		</div>
																		<div class="modal-body">
																	        <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $registroActual->id; ?>" name="folio" readonly>
                                                                            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $idUsuario; ?>" name="usuario" readonly>
                                                                            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $registroActual->id_registrante; ?>" name="usuarioDestino" readonly>
                                                                            <input style="visibility: hidden; width: 1%; height: 1%;"
                                                                                   value="<?php echo $registroActual->id_coleccion; ?>" name="coleccion" readonly>
																		    <textarea rows="10" class="x dscrp" name="mensaje"></textarea>
																	  </div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
																			<button type="submit" class="btn btn-primary" name="btnRechazar">Enviar</button>
																		</div>
																		</form>
																	</div><!-- /.modal-content -->
																</div><!-- /.modal-dialog -->
															</div><!-- /Termina modal -->
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="candile" id="detalle-de-ficha"> 
                                                    	<div class="franja <?php echo filtrarColorDeColeccion($registroActual->{"id_coleccion"}); ?>"></div>
														<div class="candile-inner">
									       			<div class="row">
                                                    	<div class="info-first">
                                                            <div class="col-md-12">
                                                                <div class="col-md-2">
                                                                    <?php
                                                                    if(isset($registroActual->archivos)){
                                                                        $mainImagen = null;
                                                                        foreach($registroActual->archivos as $archivo){
                                                                            if(substr_compare($archivo, ".jpeg", strlen($archivo)-strlen(".jpeg"), strlen(".jpeg")) === 0
                                                                            || substr_compare($archivo, ".jpg", strlen($archivo)-strlen(".jpg"), strlen(".jpg")) === 0
                                                                            || substr_compare($archivo, ".png", strlen($archivo)-strlen(".png"), strlen(".png")) === 0){
                                                                                $mainImagen = $archivo;
                                                                                break;
                                                                            }
                                                                        }
                                                                        if(isset($mainImagen))
                                                                            echo '<div class="foto"><img src="'.$mainImagen.'" alt=" " /></div>';
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
                                                                                <strong>Ubicación actual</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo $registroActual->ubicacion; ?></p>
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
                                                                    	    
                                                                    	    function endsWith($string, $endString) 
                                                                                    { 
                                                                                        $len = strlen($endString); 
                                                                                        if ($len == 0) { 
                                                                                            return true; 
                                                                                        } 
                                                                                        return (substr($string, -$len) === $endString); 
                                                                                    } 
                                                                    	    
                                                                    	    if(isset($registroActual->archivos)){
                                                                    	        foreach($registroActual->archivos as $archivo){
                                                                    	            $preview = "";
                                                                    	            if(endsWith($archivo, ".jpeg") || endsWith($archivo, ".jpg") || endsWith($archivo, ".png")){
                                                                    	                $preview = $archivo;
                                                                    	            } else if(endsWith($archivo, ".mp4") || endsWith($archivo, ".avi")){
                                                                    	                $preview = "images/video_icon.png";
                                                                    	            } else if(endsWith($archivo, ".mp3")){
                                                                    	                $preview = "images/audio_icon.png";
                                                                    	            } else if(endsWith($archivo, ".pdf")){
                                                                    	                $preview = "images/pdf_icon.png";
                                                                    	            }
                                                                    	            echo '<div class="col-md-3 grid">
                                                                            	<a href="'.$archivo.'"  target="_blank" rel="title" class="b-link-stripe b-animate-go  thickbox">
                                                                                    <figure class="effect-oscar">
                                                                                        
                                                                                    <img src="'.$preview.'" alt="" archivo="'.$archivo.'" onclick="openRelatedFile(this)"/>	
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
                                                                                        if(is_numeric($campo->valor))
                                                                                            echo '<strong>'. getRowFromDatabase($TABLA_CAMPOS, $campo->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $campo->valor, "ID")->{"NOMBRE"}.'</p>';
                                                                                        else 
                                                                                        echo '<strong>'. getRowFromDatabase($TABLA_CAMPOS, $campo->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. $campo->valor.'</p>';
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
                                                                                        echo '<strong>'. getRowFromDatabase($TABLA_CAMPOS, $campo->idCampo, "ID")->{"NOMBRE"}.'</strong><br><p class="text-muted">'. (($campo->tipo == "catalogo") ? $campo->valorReal : $campo->valor) .'</p>';
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
                                                                            if(isset($campo->valor) && $campo->valor != "" && $campo->idCampo != 8){
                                                                                
                                                                            
                                                                                echo '<div class="col-md-6"><div class="about-info-p m-b-0">';
                                                                                //echo '<strong>'. $campo->nombre.'</strong><br><p class="text-muted">'. (($campo->tipo == "catalogo") ? $campo->valorReal : $campo->valor) .'</p>';
										echo '<strong>' . $campo->nombre . '</strong><br><p class="text-muted">' . nl2br((($campo->tipo == "catalogo") ? $campo->valorReal : $campo->valor)) . '</p>';	
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
