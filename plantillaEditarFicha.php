<script type="text/javascript">
    var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;
</script>

 <?php
    $relacionesValoresCampos = obtenerRelacionesCampos($fichaSeleccionada);
    $microtime =microtime(true);//fecha y hora de microsegundos
    $now = DateTime::createFromFormat('U.u',$microtime );//fecha con formato
?>
 <div class="row">
	<div class="col-md-6"></div>
        <div class="col-md-6">
        	<div class="sub-heard-part">
		   <ol class="breadcrumb m-b-0">
				<li><a href="main.php?mr=1">Mis registros</a></li>
				<li class="active">Edición</li>
			</ol>
		   </div>
        </div>
</div>

<div class="row ">
    <div class="col-md-6">
       <h3 class="sub-tittle pro">Edición de registro</h3>
    </div>
     <div class="col-md-6">
     </div>
</div>

<div class="candile">
    <div class="candile-inner">

        <!--Inicia registro-->
        <div class="graph">
            <div id="cabecera">
                <h3 class="title">Ficha de registro</h3>

            </div>
            <div id="formulario">
                    <form id="ficha-form" role="form" action="main.php?op=2&p=<?php echo $periodo; ?>&f=<?php echo $fichaSeleccionada; ?>" method="post" enctype="multipart/form-data">
            
                        <!--<div class="fila">-->
                        <div class="col-md-12 campo-formulario">
                            <label><?php echo obtenerNombreFicha($fichaSeleccionada);?></label>
                        </div>
                        <div class="col-md-0 campo-formulario"></div>
                            <div class="col-md-6 campo-formulario">
                                <div class="col-md-4">
                                    <label for="fecha">Fecha </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" id="fecha" name="fecha" size="25" readonly class="x"  value="<?php echo $now->format("d-m-Y");?>" maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-6 campo-formulario" id="colFolio">
                                <div class="col-md-4">
                                    <label for="folio">Folio </label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" id="folio" name="folio" size="25" readonly class="x" value="<?php echo "REG".$fichaSeleccionada."-".$microtime;?>" maxlength="50" >
                                </div>
                            </div>
            
            
                        <?php
                            $arraySecciones = obtenerSecciones($fichaSeleccionada);
                            foreach ($arraySecciones as $seccion) {
                                $dosCampos = "";
                                $counterCampos = 0;
                        ?>
                            <div class="cabecera">
                            <h3 class="sub-title"><?php echo  $seccion->nombre;?></h3>
                            </div>
                    
                        <?php
                            $sizeFields = sizeof($seccion->campos );
                            
                            
                    
                            $camposTmp = $seccion->campos;
                            for($i = 0 ; $i < $sizeFields ; $i++){
                                $campo = $camposTmp[$i];
                                $obligatorio = "";
                                if($campo->obligatorio){
                                    $obligatorio = "required";
                                    $campo->nombre = "<span style='color: red; '>* </span>" . $campo->nombre;
                                }
                                if($campo->tipo == 1){
                                    //Es un campo de texto
                                    $nombrePeriodo="";
                                    if($campo->id==2)
                                        $nombrePeriodo= obtenerNombrePeriodo($periodo);
                                    $dosCampos.="<div class='col-md-6  campo-formulario' id='col".$campo->id."'><div class='col-md-4'><label for='".$campo->id."'>".$campo->nombre."</label></div><div class='col-md-8'><input id='".$campo->id."' name='".$campo->id."' value='".$nombrePeriodo."' type='text' class='x' ".$obligatorio."></div></div>";
                                    if(isset($camposTmp[$i + 1]) && $camposTmp[$i + 1]->tipo == 2 && $i % 2 != 1){
                                        $dosCampos .= "<div class='col-md-6  campo-formulario'></div>";
                                    }
                                    if(!isset($camposTmp[$i + 1]) && ($i % 2) != 1){
                                        $dosCampos .= "<div class='col-md-6  campo-formulario'></div>";
                                    }
                                } else if ($campo->tipo==2) {
                                    $dosCampos.="<div class='col-md-12  campo-formulario' id='col".$campo->id."'><div class='col-md-2'><label for='".$campo->id."'>".$campo->nombre."</label></div><div class='col-md-10'><textarea id='".$campo->id."' name='".$campo->id."'  rows='6' class='x' ".$obligatorio."></textarea></div></div><div class='col-md-0  campo-formulario'></div>";
                                } else if($campo->tipo == 3) {
                                    $opcionesString="";
                                    if($campo->id != 67 && $campo->id != 68 && $campo->id != 69)
                                        foreach ($campo->opciones as $opcion) {
                                            $opcionesString .=" <option value='". $opcion->id."'>".$opcion->nombre."</option>";
                                        }
                                    $bndSeleccion="";
                                    if($campo->id==4 || $campo->id== 5)
                                        $bndSeleccion="disabled";
                                    $dosCampos.="<div class='col-md-6 campo-formulario' id='col".$campo->id."'>
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
                                    if(isset($camposTmp[$i + 1]) && $camposTmp[$i + 1]->tipo == 2 && (($i % 2) != 1)){
                                        $dosCampos .= "<div class='col-md-6  campo-formulario'></div>";
                                    }
                                }
                                
                            }
                            
                            echo $dosCampos;
                            $dosCampos = "";
                            
                            
                            
                            if($seccion->bndFiles == 1) {
                                
                            ?>
                                                    <div class="col-md-12 campo-formulario">
                                                       <div class ="col-md-6">
                                                            <div class="col-md-4"><label >Objeto digital</label></div>
                                                            <div class="col-md-8">
                                                         <label style=" display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 200;
    font-size: 0.8em;
    color: #777;">Selecciona hasta 5 objetos a la vez</label>
                                                            </div>
                                                       </div>
                                                       <div class ="col-md-6">
                                                           <div class="col-md-8">
                                                                  <label id="objectsCounterSelected" for="archivos" class="a_demo_four">Cargar aquí (0 Objetos nuevos)</label>
                                                                <input style="display:none;"type="file" class="form-control" id="archivos" name="archivos[]" multiple="multiple" accept="application/pdf, image/jpeg, image/jpg"/>
                                                         
                                                           </div>
                                                            <div class="col-md-4"></div>
                                                       </div>
                                                           
                                                       
                                                
                                                    </div>
                                                    
                                                    <div class="col-md-12 campo-formulario">
                                                        <div class="galeria">
                                                            <ul id="archivosGuardados">
                                                            </ul>
                                                        </div>
                                                    </div>
                    
                                                <script>
                                                
                                                 var tamArchivosGuardados=0;
                    
                                                    $("#archivos").on("change", function() {
                    
                                                         var totalArchivos=$("#archivos")[0].files.length+ tamArchivosGuardados;
                                                        if (totalArchivos > 5) {
                    
                                                            alert("Solo puedes subir un máximo de 5 archivos");
                                                            $("#archivos").val('');
                                                             document.getElementById('objectsCounterSelected').innerHTML='Cargar aquí (0 Objetos nuevos)';
                                                        }else
                                                        {
                                                            var totalSize=0;
                                                            var files = $('#archivos')[0].files;
                                                            
                                                            for (var i = 0; i < files.length; i++) {
                                                                // calculate total size of all files
                                                                totalSize += files[i].size;
                                                            }
                                                            
                                                            document.getElementById('objectsCounterSelected').innerHTML='Cargar aquí ('+files.length+' Objetos nuevos)';
                                                            if(totalSize>104857600)
                                                            {
                                                                alert("El tamaño total es de 10MB");
                                                                $("#archivos").val('');
                                                            document.getElementById('objectsCounterSelected').innerHTML='Cargar aquí (0 Objetos nuevos)';
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
                                        
                    <button style="position: absolute; right: 0; bottom: -4.5em;"type="submit" name="btnActualizar" id="btnActualizar" class="btn guardar">GUARDAR</button>
                    </form>
                </div>
            <div class="clearfix"></div>
        </div>
        <div class="botones" style="height:6em;">
            <!--<button type="submit" class="btn guardar" onclick="sendForm()">GUARDAR</button>-->
        </div>
        <div class="clearfix"></div>
    </div>
</div>

  <script type="text/javascript">
                            var arrayRelaciones = <?php echo json_encode(obtenerRelacionesCampos($fichaSeleccionada)); ?>;
                            arrayRelaciones.forEach(function (relacion) {
                                document.getElementById("col"+relacion.id_campo).style.display = "none";
                            });
                            
                            colorearFormulario();
                            
                            function colorearFormulario(){
                                var visibles = new Array();
                                var visibleDivs = new Array();
                                
                                $(".fake-form-field").remove();
                                
                                $.each($("form > div"), function(index, value){
                                    if($(value).css("display") == "block"){
                                        console.log("added");
                                        visibleDivs.push(value);
                                    } 
                                });
                                var startIndex = 0;
                                var limitIndex;
                                $.each(visibleDivs, function(index, value){
                                    if($(value).hasClass("cabecera")){
                                        limitIndex = index;
                                        if((limitIndex - startIndex) % 2 == 0){
                                            console.log("correcto (" + limitIndex + " - " + startIndex + ")", value);
                                        } else {
                                            $('<div class="col-md-6 campo-formulario fake-form-field"><div>').insertBefore(value);
                                            console.log("incorrecto (" + limitIndex + " - " + startIndex + ")", value);
                                        }
                                        startIndex = limitIndex + 1 ;
                                    }
                                });
                                
                                
                                
                                $.each($(".campo-formulario"), function(index, value){
                                    
                                    if($(value).css("display") == "block"){
                                        console.log("added");
                                        visibles.push(value);
                                    }
                                });
                                
                                
                                for(i = 0 ; i < visibles.length ; i++){
                                    if(i % 4 == 0 || i % 4 == 1){
                                        $(visibles[i]).css("background", "white");
                                    } else 
                                        $(visibles[i]).css("background", "#f2f2f2");
                                }
                            }
                            </script>
                    
