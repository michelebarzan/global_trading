<?php
	
	include "Session.php";
	include "connessione.php";

    $id_codice_colore=$_REQUEST['id_codice_colore'];

    $q2="DELETE FROM [global_trading2].[dbo].[codici_colori] WHERE id_codice_colore=$id_codice_colore";
    $r2=sqlsrv_query($conn,$q2);
    if($r2==FALSE)
    {
        die("error");
    }
    else
    {
        echo "ok";
    }
?>