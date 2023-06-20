
 
 
 
 <div class="row">
    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                    	<div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="dashboard.html">Dashboard</a></li>
											<li><a href="#">Catálogos</a></li>
                                            <li class="active">Nuevo Catálogo</li>
										</ol>
									   </div>
                                    </div>
									  
                                 </div>

<div class="row ">
                                    <div class="col-md-6">
                                           <h3 class="sub-tittle pro">Nuevo catálogo</h3>
                                    </div>
                                     <div class="col-md-6">
                                                        	
                                     </div>
                        		</div>



<div class="candile"> 
                                	<div class="candile-inner">
                                    	<div class="graph">
                                            <div id="cabecera">
                <h3 class="title">Colección</h3>
            </div>
            <div id="formulario">
                <form action="registrarNuevoCampo.php" method="post" id="newCatalogForm">
                    <div class="fila">
                        <div class="col-md-6">
                            <div class="col-md-4">
                              <label>Colección</label></div>
                            <div class="col-md-8">
                                <div class="caja">
                                <select name="ficha" size="1" class="selector" id="ficha" onchange="obtenerColecciones()">
                                    <option value="0" selected>Seleccione una opción</option>
                                    
                                    <?php
                    $colecciones = obtenerColeccion(1);
                    
                    foreach (obtenerColeccion(2) as $coleccionAdicional) {
                        array_push($colecciones, $coleccionAdicional);
                     }
                     
                    foreach ($colecciones as $coleccion) {
                        if(sizeof($coleccion->fichas)>0) {
                            foreach ($coleccion->fichas as $ficha) {
                                ?>
                                 <option value="<?php echo $ficha->id; ?>" > <?php echo $ficha->ficha; ?> </option>
                                <?php
                            }
                        }

                    }
                    ?>
                                
                                    
                                </select>
                                </div><!--Termina caja-->
                            
                            </div>
                        </div><!--Termina columna 1-->
                        <div class="col-md-6">
                            <div class="col-md-4"><label></label></div>
                            <div class="col-md-8">
                                
                            </div>
                        </div><!--Termina columna 2-->
                          
                    </div><!--Termina fila-->             
                    <div class="clearfix"></div>
                    
                    <div class="clearfix"></div>
                    <div id="cabecera2">
                        <h3 class="sub-title">Sección</h3>
                
                    </div>
                    <div class="fila">
                        <div class="col-md-6">
                            <div class="col-md-4">
                              <label>Sección </label></div>
                            <div class="col-md-8">
                                <div class="caja">
                                  <select name="seccion" size="1" id = "seccion" class="selector">
                                  <option value="0" selected>Seleccione una opción</option>
                                  
                                </select>
                                </div><!--Termina caja-->
                            </div>
                        </div><!--Termina columna 1-->
                        <div class="col-md-6">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                
                          </div><!--Termina columna 2-->
                        </div>
                        
                    </div><!--Termina fila-->
                    <div class="clearfix"></div>
                    <div id="cabecera2">
                        <h3 class="sub-title">Nombre del catálogo</h3>
                
                    </div>
                  
                  <div class="clearfix"></div>
                    <div class="fila">
                    	
                        <div class="col-md-12">
                            <div class="col-md-2">
                              <label>Nombre</label></div>
                            <div class="col-md-10">
                                <input name="nombreCampo" id= "nombreCampo" type="text" class="x">
                            </div>
                        </div><!--Termina columna 1-->
                    </div><!--Termina fila-->
                    <div id="cabecera2">
                        <h3 class="sub-title">Opciones del catálogo</h3>
                
                    </div>
                    <div class="clearfix"></div>
                    
                    <input id="options" name="options" type="hidden" />
                    <input id="tipoCampo" name="tipoCampo" type="hidden" value="3"/>
                    
                    <div id="contenedorDeOpciones">
                        <div class="fila">
                      <div class="col-md-12">
                            <div class="col-md-2">
                              <label>Opción 1</label></div>
                            <div class="col-md-10">
                                <input name="option-1" type="text" class="x option-input" onkeyup="updateOptions()" required/>
                            </div>
                        </div><!--Termina columna 1-->  
                  </div>
                    </div>
                  
                  
                  
                  
                    
                    
                    <div class="fila">
                      <div class="col-md-12">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                            	<div class="botones">
                            	<a class="btn btn-exportar" onclick="agregarOpcion()"><i class="fa fa-plus"></i> Agregar opción</a>
                                </div>
                            </div>
                        </div><!--Termina columna 1-->
                    </div>
                    
                    
                    
                </form><!--Termina formulario-->
            </div>
    		</div>
                                        <div class="botones_ficha">
                  <div class="col-md-6"></div>
                       <div class="col-md-6">
                           <a href="#" class="btn guardar" onclick="document.getElementById('newCatalogForm').submit();">GUARDAR</a>
                           <a class="btn publicar">PUBLICAR</a>
                       </div>
                                            
            </div>
                                        <div class="clearfix"></div>
            						</div>		
								</div><!--/candile-->
								
								
	<script type="text/javascript">
	
	var optionsNumber = 1;
	function agregarOpcion(){
	    optionsNumber ++;
	    var optionHTML = '<div class="fila"><div class="col-md-12"> <div class="col-md-2"><label>Opción ' + optionsNumber + '</label></div><div class="col-md-10"><input name="option-' + optionsNumber + '" type="text" class="x option-input" onkeyup="updateOptions()" required/></div></div></div>';
	
	    $("#contenedorDeOpciones").append(optionHTML);
	}
	
	
	function updateOptions(){
	    var currentOptions = $("#contenedorDeOpciones").find(".option-input");
	    var options = [];
	    $.each(currentOptions, function(index, element){
	        options.push($(element).val());
	        console.log("(" + index + ") => ", $(element).val());
	    });
	    $("#options").val(JSON.stringify(options));
	}
	
	function obtenerColecciones()
	{
        var valorSeleccionado = document.getElementById('ficha').value;
        var campoDestino= document.getElementById('seccion');
        campoDestino.innerHTML="<option value='' selected >Selecciona una opci&oacuten</option>";

     $.get("lib/obtenerDatosdeFichas.php", { ficha:valorSeleccionado },function(data){
    
                var arrayValores= JSON.parse(data);
                

                
                campoDestino.innerHTML="<option value='' selected >Selecciona una opci&oacuten</option>";
                arrayValores.valores.forEach(function (valor) {
                    campoDestino.innerHTML+="<option value='"+valor.id+"' >"+valor.nombre+"</option>"
                });
    
    
            });
	}
	</script>

