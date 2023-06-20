<?php
$mysqli = new mysqli("localhost", "srvccm", "720./Srv.xi8", "patrimonio_dev");
$query = "SELECT * FROM secciones;";
if ($secciones = $mysqli->query($query)){
	while ($seccion = $secciones->fetch_object()){
        echo "<br>" . $seccion->{"ID"} . "<br>";
        $querySelector = "SELECT * FROM secciones_campos WHERE ID_SECCION = " . $seccion->{"ID"} . ";";
        $indexer = 1;
        if ($campos = $mysqli->query($querySelector)){
        	while($campo = $campos->fetch_object()){
        		echo "->* " . $campo->{"ID"} . " va a ser el (" . $indexer . ")";
        		$updater = "UPDATE secciones_campos SET INDICE = " . $indexer . " WHERE ID = " . $campo->{"ID"} . " ;";
        		$wasUpdated = $mysqli->query($updater);
        		echo " - " . $updater . " => " . $wasUpdated . "<br>";
        		$indexer += 2;
        	}
        	echo "-> * * * * * * * * * * * * * * * * * * * * * * * * * * ";
        }
    }
} else 
	echo "No hubo resultados :(";
$mysqli->close();
