<?php
	
	include "Session.php";
	include "connessione.php";

    $colori_articoli=[];

    $q2="SELECT [id_codice_colore],[codice],[colore] FROM [global_trading2].[dbo].[codici_colori]";
    $r2=sqlsrv_query($conn,$q2);
    if($r2==FALSE)
    {
        die("error");
    }
    else
    {
        while($row2=sqlsrv_fetch_array($r2))
        {
            $row = [];
            $row['id_codice_colore']=$row2['id_codice_colore'];
            $row['codice']=$row2['codice'];
            $row['colore']=$row2['colore'];

            array_push($colori_articoli,$row);
        }
        echo json_encode($colori_articoli);
    }
?>