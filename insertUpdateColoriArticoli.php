<?php
	
	include "Session.php";
	include "connessione.php";
	
	$rowsToInsert=json_decode($_REQUEST['JSONrowsToInsert']);
	$rowsToUpdate=json_decode($_REQUEST['JSONrowsToUpdate']);
    
    for ($x = 0; $x < sizeof($rowsToUpdate); $x++)
    {
        $rowToUpdate=json_decode(json_encode($rowsToUpdate[$x]), True);

        $id_codice_colore=$rowToUpdate['id_codice_colore'];
        $codice=$rowToUpdate['codice'];
        $colore=$rowToUpdate['colore'];

        $q="UPDATE codici_colori SET codice='$codice', colore='$colore' WHERE id_codice_colore=$id_codice_colore";
        $r=sqlsrv_query($conn,$q);
        if(!$r)
            die(print_r(sqlsrv_errors(),TRUE));
    }

    for ($y = 0; $y < sizeof($rowsToInsert); $y++)
    {
        $rowToInsert=json_decode(json_encode($rowsToInsert[$y]), True);

        $codice=$rowToInsert['codice'];
        $colore=$rowToInsert['colore'];

        $q2="INSERT INTO codici_colori (codice,colore) VALUES ('$codice','$colore')";
        $r2=sqlsrv_query($conn,$q2);
        if(!$r2)
            die(print_r(sqlsrv_errors(),TRUE));
    }
    echo "ok";

?>