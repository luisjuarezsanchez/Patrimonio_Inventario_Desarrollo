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
                                                        	<h3 class="sub-tittle user">Actualizar datos del usuario</h3>
                                                        </div>
                                                        <div class="col-md-6">
                                                        	<div class="botones">
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
                                                                
                                                                <form action="update_user_information.php" method="post">
                                                                    
                                                                    
                                                                    
                                                                    <div class="col-md-5">
                                                                    	<div class="info-creacion">
                                                                    	        <input type="hidden" id="user_id" name="user_id" value="<?php echo $perfilDeUsuario->{"ID"};?>"/>
                                                                    	        <div class="about-info-p">
                                                                                    <strong>Nombre(s)</strong>
                                                                                    <br>
                                                                                    <input type="text" name="new_user_name" placeholder="<?php echo $perfilDeUsuario->{"NOMBRE"};?>"/>
    																			</div>
                                                                                <div class="about-info-p">
                                                                                    <strong>Correo</strong>
                                                                                    <br>
                                                                                    <input type="email" name="new_user_email" placeholder="<?php echo $perfilDeUsuario->{"CORREO"};?>"/>
    																			</div>
    																			<div class="about-info-p">
                                                                                    <strong>Contraseña</strong>
                                                                                    <br>
                                                                                    <input type="password" name="new_user_password" placeholder="* * * * * * * *"/>
    																			</div>
                                                                                
                                                                      </div><!--//info-creacion-->
                                                                  </div>
    																 <div class="col-md-5">
                                                                        <div class="info-creacion">
                                                                                <div class="about-info-p">
                                                                                    <strong>Apellido(s)</strong>
                                                                                    <br>
                                                                                    <input type="text" name="new_user_lastname" placeholder="<?php echo $perfilDeUsuario->{"APELLIDOS"};?>"/>
    																			</div>
                                                                                <div class="about-info-p">
                                                                                    <strong>Dependencia</strong>
                                                                                    <br>
                                                                                    <select name="new_user_dependency" size="1" class="selector" id="editorDependencia">
                                                                            <option value="1">Museo del Centro Cultural Sor Juana Inés de la Cruz</option>
                                                                                <option value="19">Museo Gonzalo Carrasco</option>
                                                                                <option value="20">Museo del Retrato Felipe Santiago Gutiérrez</option>
                                                                                <option value="21">Museo Casa Toluca 1920</option>
                                                                                <option value="22">Museo de Sitio de San Miguel Ixtapan</option>
                                                                                <option value="23">Museo Arqueológico del Estado de México Dr. Román Piña Chan</option>
                                                                                <option value="24">Museo Antonio Ruiz Pérez</option>
                                                                                <option value="25">Museo de Antropología e Historia del Estado de México</option>
                                                                                <option value="26">Museo Joaquín Arcadio Pagaza</option>
                                                                                <option value="27">Museo Arqueológico de Valle de Bravo</option>
                                                                                <option value="28">Museo Virreinal de Zinacantepec</option>
                                                                                <option value="152306">Museo Hacienda La Pila</option>
                                                                                <option value="152307">Plaza Estado de México</option>
                                                                                <option value="152308">Museo Arqueológico de Nextlalpan</option>
                                                                                <option value="152309">Subdirección de Acervo Cultural</option>
                                                                                <option value="152310">Dirección de Patrimonio Cultural</option>
                                                                                <option value="18">Museo de Minería, del Oro</option>
                                                                                <option value="17">Museo Arqueológico Colonial de Ocuilan</option>
                                                                                <option value="2">Museo Arqueológico de Apaxco</option>
                                                                                <option value="3">Museo Lic. Adolfo López Mateos</option>
                                                                                <option value="4">Museo del Centro Cultural Mexiquense Bicentenario</option>
                                                                                <option value="5">Museo Lic. Isidro Fabela</option>
                                                                                <option value="6">Museo Taller Luis Nishizawa</option>
                                                                                <option value="7">Museo de Bellas Artes</option>
                                                                                <option value="8">Museo de Arte Moderno del Estado de México</option>
                                                                                <option value="9">Museo de Ciencias Naturales</option>
                                                                                <option value="10">Museo de la Acuarela del Estado de México</option>
                                                                                <option value="11">Museo de la Estampa del Estado de México</option>
                                                                                <option value="12">Museo del Paisaje José María Velasco</option>
                                                                                <option value="13">Cosmovitral, Jardín Botánico</option>
                                                                                <option value="14">Museo Torres Bicentenario, Galería de Arte Mexiquense</option>
                                                                                <option value="15">Museo Dr. José María Luis Mora</option>
                                                                                <option value="16">Museo de Numismática del Estado de México</option>
                                                                                <option value="152330">Museo del Centro Regional de Cultura de Chalco</option>
                                        
                                </select>
                                
                                <script type="text/javascript">
	function setDependencia(id){
	    
		let dependencias = $("#editorDependencia").children("option");
		$.each(dependencias, function (index, value){
		    console.log(value, value.value);
		    
		    if (value.value == id){
		        $(value).attr('selected','selected');
		        console.log(value.value == id, value);
		    }
		});
	}
	
	setDependencia('<?php echo $perfilDeUsuario->{"ID_OPCION_DEPENDENCIA"}; ?>'); 
</script>
    																			</div>
                                                                                
                                                                       </div><!--//info-creacion-->
                                                                    </div><!--//termina col-md-4-->
                                                                    
                                                                    
                                                                    <input type="submit" name="updateUserData" id="updateUserData" value="Guardar cambios"/>
                                                                </form>
                                                             	
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
                                                                            <label>90</label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                                    <div class="col-md-3 widget states-mdl">
                                                                        <div class="stats-left">
                                                                            <h5>Fichas</h5>
                                                                            <h4>Aprobadas</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label> 85</label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                                    <div class="col-md-3 widget states-thrd">
                                                                        <div class="stats-left-eliminar">
                                                                            <h5>Fichas</h5>
                                                                            <h4>Eliminadas</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label>51</label>
                                                                        </div>
                                                                        <div class="clearfix"> </div>	
                                                                    </div>
                                                                    <div class="col-md-3 widget states-last">
                                                                        <div class="stats-left-comentar">
                                                                            <h5>Fichas</h5>
                                                                            <h4>Comentadas</h4>
                                                                        </div>
                                                                        <div class="stats-right">
                                                                            <label>30</label>
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