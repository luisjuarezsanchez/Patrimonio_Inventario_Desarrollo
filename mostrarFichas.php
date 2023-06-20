<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 14/06/2019
 * Time: 07:12 PM
 */
$fichaSeleccionada=0;
if(isset($_GET['ficha'])){
    $fichaSeleccionada = $_GET['ficha'];
}


?>
                            <!--sub-heard-part-->
                                <div class="row">
                                	<div class="col-md-6"></div>
                                    <div class="col-md-6">
                                    	<div class="sub-heard-part">
									   <ol class="breadcrumb m-b-0">
											<li><a href="main.php?mr=5">Dashboard</a></li>
											<li class="active">Nuevo registro</li>
										</ol>
									   </div>
                                    </div>
                                 </div>
							<!--//sub-heard-part-->
								
								
                            <!--Título sección-->
                        		<div class="row ">
                                    <div class="col-md-6">
                                           <h3 class="sub-tittle pro">Nuevo registro</h3>
                                    </div>
                                     <div class="col-md-6">
                                           <div class="botones">
                                               
                                           </div>             	
                                     </div>
                        		</div>
							<!--//Título sección-->	
							
							
							
							<div class="candile"> 
															<div class="candile-inner">
                                                            	<div class="row">
                                                                	<h3 class="colecciones">Colecciones</h3>
                                                                </div>

                                                                   	<div class="gallery">
                                                                	<div class="gallery-bottom grid">
<?php

    if ($periodo !== 0) {
        $fichas = obtenerFichas($periodo);

            foreach ($fichas as $ficha) {
                ?>
                
                <div class="col-md-6 g-left">
                                                                            <a href="main.php?p=<?php echo $periodo; ?>&f=<?php echo $ficha->id; ?>" rel="title" class="b-link-stripe b-animate-go  thickbox">
                                                                                <figure class="effect-oscar">
                                                                                    <img src="<?php echo $ficha->imagen; ?>" alt=""/>
                                                                                    <figcaption>
                                                                                        <h4><?php echo $ficha->ficha; ?></h4>	
                                                                                    </figcaption>			
                                                                                </figure>
                                                                            </a>
                                                                        </div>

                <?php
            }



    }
    ?>
    
    
    </div>
                                                               </div> 
                                                                                                                            
                                                                <div class="clearfix"></div>
																				
															</div>
															
													</div>
													<!--/candile-->
    
    <!--/candile-->
													
                                                                
