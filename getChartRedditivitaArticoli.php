<?php
	
	include "Session.php";
	include "connessione.php";

	set_time_limit(3000);
	
	$filtersCodiceArticolo=json_decode($_REQUEST['JSONfiltersCodiceArticolo']);
	$filtersCodiceMadre=json_decode($_REQUEST['JSONfiltersCodiceMadre']);
	$filtersCodiceRadice=json_decode($_REQUEST['JSONfiltersCodiceRadice']);
	$raggruppamento=$_REQUEST['raggruppamento'];
	
	$dataPointsRedditivita = array();
	$dataPointsValore = array();
	$response=[];
	
	if($raggruppamento=="annoMese")
		$q="SELECT SUM(derivedtbl_1.redditivita) AS redditivita, SUM(derivedtbl_1.valore) AS valore, dbo.settimaneView.annoMese
			FROM (SELECT annoMese, SUM(redditivita) AS redditivita, SUM(valore) AS valore
			FROM dbo.redditivita";
	else
		$q="SELECT SUM(derivedtbl_1.redditivita) AS redditivita, SUM(derivedtbl_1.valore) AS valore, dbo.settimaneView.anno
			FROM (SELECT annoMese, SUM(redditivita) AS redditivita, SUM(valore) AS valore
			FROM dbo.redditivita";
	//$q="SELECT $raggruppamento, SUM(redditivita) AS redditivita, SUM(valore) AS valore FROM dbo.redditivita";
	
	$q.=" WHERE (Id_ARTICOLO ";
	foreach ($filtersCodiceArticolo as $CodiceArticolo)
	{
		$q.="LIKE '$CodiceArticolo') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceMadre as $CodiceMadre)
	{
		$q.="LIKE '$CodiceMadre%') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceRadice as $CodiceRadice)
	{
		$q.="LIKE '$CodiceRadice%') OR (Id_ARTICOLO ";
	}
	$q=substr($q, 0, -17);
	
	if($raggruppamento=="annoMese")
		$q.=" GROUP BY annoMese) AS derivedtbl_1 RIGHT OUTER JOIN
			dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
			GROUP BY dbo.settimaneView.annoMese
			ORDER BY dbo.settimaneView.annoMese OPTION ( QUERYTRACEON 9481 )";
	else
		$q.=" GROUP BY annoMese) AS derivedtbl_1 RIGHT OUTER JOIN
			dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
			GROUP BY dbo.settimaneView.anno
			ORDER BY dbo.settimaneView.anno OPTION ( QUERYTRACEON 9481 )";
	//$q.=" GROUP BY $raggruppamento ORDER BY $raggruppamento";
	
	//echo $q;

	$r=sqlsrv_query($conn,$q);
	if($r==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row=sqlsrv_fetch_array($r))
		{
			$dotRedditivita = array();
			$dotRedditivita['label']=$row[$raggruppamento];
			$dotRedditivita['y']=intval($row['redditivita']);
			array_push($dataPointsRedditivita,$dotRedditivita);
			
			$dotValore = array();
			$dotValore['label']=$row[$raggruppamento];
			$dotValore['y']=intval($row['valore']);
			array_push($dataPointsValore,$dotValore);
		}
	}
	
	array_push($response,json_encode($dataPointsRedditivita));
	array_push($response,json_encode($dataPointsValore));
	
	echo json_encode($response);

?>