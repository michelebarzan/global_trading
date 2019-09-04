<?php
	
	include "Session.php";
	include "connessione.php";
	
    $orderBy=$_REQUEST['orderBy'];

    $clienti=[];

    $q2="SELECT * FROM clienti_fatturato ORDER BY $orderBy";
    $r2=sqlsrv_query($conn,$q2);
    if($r2==FALSE)
    {
        die("error");
    }
    else
    {
        while($row2=sqlsrv_fetch_array($r2))
        {
            array_push($clienti,$row2['cliente']);
        }
        echo json_encode($clienti);
    }
?>