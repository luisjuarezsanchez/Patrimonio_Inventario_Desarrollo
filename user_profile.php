<!--sub-heard-part-->
									  <?php
									  
									  if(isset($_GET["target"])){
									      $idUsuario = $_GET["target"];
									  }
									  
									  $perfilDeUsuario = obtenerPerfilDeUsuario($idUsuario);
									  
									  function obtenerPerfilDeUsuario($id){
									      include "utils/constants.php";
									      include "utils/database_utils.php";
									      $usuario =  getRowFromDatabase($TABLA_USUARIOS, $id, "ID");
									      $usuario->{"museo_dependencia"} = getRowFromDatabase($TABLA_CATALOGO_OPCIONES, $usuario->{"ID_OPCION_DEPENDENCIA"}, "ID");
									      return $usuario;
									  }
									  
									  function filtrarRol($key){
									      if($key == "1"){
									          return "Editor";
									      } else if($key == "2"){
									          return "Administrador";
									      } else if($key == "3"){
									          return "Super Administrador";
									      } else {
									          return "?";
									      }
									  }
									  
									  
									  
									  
									  
									  $registros = obtenerRegistrosDashboard();
									  $aprobados = 0;
									  $eliminados = 0;
									  $revision = 0;
									  
                                        foreach ($registros as $registro) {
                                            if ($registro->{"ID_ESTATUS_PAPELERA"} == "2") {
                                                $eliminados += 1;
                                            } else {
                                                if ($registro->{"ID_ESTATUS_REGISTRO"} == "2") {
                                                    $revision += 1;
                                                } else if ($registro->{"ID_ESTATUS_REGISTRO"} == "3") {
                                                    $aprobados += 1;
                                                }
                                            }
                                        }

									  
									  
									  function obtenerRegistrosDashboard(){
                                            require_once 'utils/database_utils.php';
                                            return getRowsFromDatabase("registros", $idUsuario, "ID_USUARIO");
                                        }
									  ?>
									  
									  
									  <div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="dashboard.html">Dashboard</a></li>
											<li class="active">Perfil</li>
										</ol>
									   </div>
								    <!--//sub-heard-part-->
                                    				<div class="row ">
                                                    	<div class="col-md-6">
                                                        	<h3 class="sub-tittle user"><?php echo $perfilDeUsuario->{"NOMBRE"}." ".$perfilDeUsuario->{"APELLIDOS"};?></h3>
                                                        </div>
                                                        <div class="col-md-6">
                                                        	<div class="botones">
                                                            	<button onclick="editUserProfile()" class="btn btn-editar"> <i class="fa fa-pencil"></i> Editar</button>
                                                            	<script>
                                                            	    function editUserProfile(){
                                                            	        var param = ($("#target-id").length == 0) ? "" : ("&target=" + $("#target-id").val());
                                                            	        window.location.href = 'main.php?mr=32' + param;
                                                            	    }
                                                            	</script>
                                                               <!-- <a class="btn btn-papelera"><i class="fa fa-trash-o"></i> Eliminar</a>-->
                                                            </div>
                                                            <!--Inicia MODAL-->
                                                            <div class="modal fade modal-dialog-centered" id="ModalComentar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
																<div class="modal-dialog ">
																	<div class="modal-content">
																		<div class="modal-header">
																			<button type="button" class="close second" data-dismiss="modal" aria-hidden="true">×</button>
																			<h2 class="modal-title">Agregar comentario</h2>
																		</div>
																		<div class="modal-body">
																		<textarea rows="10" class="x dscrp"></textarea>

																	  </div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
																			<button type="button" class="btn btn-primary">Enviar</button>
																		</div>
																	</div><!-- /.modal-content -->
																</div><!-- /.modal-dialog -->
															</div><!-- /Termina modal -->
                                                        </div>
                                                    </div>
                                                    
                                                    <?php
                                                    if(isset($_GET["target"])){
                									      echo '<input type="hidden" id="target-id" value="'.$_GET["target"].'">';
                									  }
                                                    ?>
                                                    
                                                    
                                                    <div class="candile"> 
                                                        	<div class="franja prehistorico"></div>
														<div class="candile-inner">
									       			<div class="row">
                                                    	<div class="info-first">
                                                            <div class="col-md-12">
                                                                <div class="col-md-2">
                                                                	<div class="foto">
                                                                        <img src="<?php echo $perfilDeUsuario->{"IMAGEN"};?>" alt=" " />
                                                                    </div>
                                                                </div>
                                                             	<div class="col-md-5">
                                                                	<div class="info-creacion">
                                                                            <div class="about-info-p">
                                                                                <strong>Fecha de registro</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php 
                                                                                
                                                                                
                                                                                $mil = intval($perfilDeUsuario->{"fecha_registro"});
                                                                                $seconds = $mil / 1000;
                                                                                echo date("d/m/Y", $seconds);
                                                                                ?></p>
																			</div>
                                                                            <div class="about-info-p">
                                                                                <strong>Correo</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo $perfilDeUsuario->{"CORREO"};?></p>
																			</div>
                                                                            
                                                                            
                                                                  </div><!--//info-creacion-->
                                                              </div>
																 <div class="col-md-5">
                                                                    <div class="info-creacion">
                                                                            <div class="about-info-p">
                                                                                <strong>Permisos</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo filtrarRol($perfilDeUsuario->{"ID_ROL"});?></p>
																			</div>
                                                                            <div class="about-info-p">
                                                                                <strong>Museo</strong>
                                                                                <br>
                                                                                <p class="text-muted"><?php echo $perfilDeUsuario->{"museo_dependencia"}->{"NOMBRE"}; ?></p>
																			</div>
                                                                        
                                                                            
                                                                   </div><!--//info-creacion-->
                                                                </div><!--//termina col-md-4-->
                                                        </div><!--//termina col-md-12-->
                                                   
                                                    </div><!--//termina info first-->
                                                    
                                                    </div><!--//termina row-->
                                                    <hr class="divider">
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                      <div class="custom-widgets">
                                                               <div class="row-one">
                                                                    <div class="col-md-3 widget">
                                                                        <div class="stats-left ">
                                                                            <h5>Mis</h5>
                                                                            <h4> Registros</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label> <?php echo sizeof($registros); ?></label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                                    <div class="col-md-3 widget states-mdl">
                                                                        <div class="stats-left">
                                                                            <h5>Fichas</h5>
                                                                            <h4>Aprobadas</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label> <?php echo $aprobados; ?></label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                                    <div class="col-md-3 widget states-thrd">
                                                                        <div class="stats-left-eliminar">
                                                                            <h5>Fichas</h5>
                                                                            <h4>Eliminadas</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label>  <?php echo $eliminados; ?></label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                                    <div class="col-md-3 widget states-last">
                                                                        <div class="stats-left-comentar">
                                                                            <h5>Fichas</h5>
                                                                            <h4>Revisión</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label>  <?php echo $revision; ?></label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                        </div>
                                                          </div>
                                                          

                                                    <div class="clearfix"></div>

                                                    	
                                                        <div class="row"><!--/col-md-4--><!--/col-md-8-->
                                                        </div>
                                                      </div> <!--/candile inner-->  
													</div>
													<!--/candile-->