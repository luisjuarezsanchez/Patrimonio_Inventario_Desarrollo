 <div class="row">
	<div class="col-md-6"></div>
    <div class="col-md-6">
    	<div class="sub-heard-part">
	   <ol class="breadcrumb m-b-0">
			<li><a href="main.php?mr=5">Dashboard</a></li>
            <li class="active">Nuevo Campo</li>
		</ol>
	   </div>
    </div>
	  
 </div>

<div class="row ">
                                    <div class="col-md-6">
                                           <h3 class="sub-tittle pro">Nuevo campo</h3>
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
            
            	<form action="registrarNuevoCampo.php" method="post">
            <div id="formulario">
                
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
                        <h3 class="sub-title">Tipo de campo</h3>
                
                    </div>
                    <div class="fila">
                        <div class="col-md-6">
                            <div class="col-md-4">
                              <label>Tipo de campo</label></div>
                            <div class="col-md-8">
                                <div class="caja">
                                  <select name="tipoCampo" size="1" id="tipoCampo" class="selector" required>
                                  <option value="0" selected>Seleccione una opción</option>
                                  
                                    <?php
                                    $tiposCampos = obtenerTiposCampos();
                                    foreach ($tiposCampos as $tipoCampo) {
                                    ?>
                                     <option value="<?php echo $tipoCampo->id; ?>" > <?php echo $tipoCampo->nombre; ?> </option>
                                    <?php
                                    
                                    }
                                    ?>
                                  
                                </select>
                                </div><!--Termina caja-->
                            </div>
                        </div><!--Termina columna 1-->
                        <div class="col-md-6">
                            <div class="col-md-4"> <label>Catálogo</label></div>
                            <div class="col-md-8">
                                 <div class="caja">
                                  <select name="otro" size="1" class="selector">
                                  <option value="0" selected>Seleccione una opción</option>
                                    <option value="1">Opción 1</option>
                                    <option value="2">Opción 2</option>
                                    <option value="3">Opción 3</option>
                                    <option value="4">Opción 4</option>
                                </select>
                                </div><!--Termina caja-->
                          	</div>
                        </div><!--Termina columna 2-->
                        
                    </div><!--Termina fila-->
                    <div class="clearfix"></div>
                    <div id="cabecera2">
                        <h3 class="sub-title">Nombre del campo</h3>
                
                    </div>
                  
                  <div class="clearfix"></div>
                    <div class="fila">
                    	
                        <div class="col-md-12">
                            <div class="col-md-2">
                              <label>Nombre</label></div>
                            <div class="col-md-10">
                                <input name="nombreCampo" type="text" id="nombreCampo" class="x">
                            </div>
                        </div><!--Termina columna 1-->
                    </div><!--Termina fila-->
               
            </div>
            
            
    		</div>
                <div class="botones_ficha">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                       <a class="btn guardar">GUARDAR</a>
                        <button type="submit" name="btnPublicar" id="btnPublicar" class="btn publicar">PUBLICAR</button>
                    </div>
                    
                </div>
                <div class="clearfix"></div>
			</div>
			</form>
</div><!--/candile-->
								
								
	<script type="text/javascript">
	
	
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