<?php
	
	include "Session.php";
	include "connessione.php";
	
	set_time_limit(3000);

	$filtersCodiceArticolo=json_decode($_REQUEST['JSONfiltersCodiceArticolo']);
	$filtersCodiceMadre=json_decode($_REQUEST['JSONfiltersCodiceMadre']);
	$filtersCodiceRadice=json_decode($_REQUEST['JSONfiltersCodiceRadice']);
	$periodo1Inizio=$_REQUEST['periodo1Inizio'];
	$periodo1Fine=$_REQUEST['periodo1Fine'];
	$periodo2Inizio=$_REQUEST['periodo2Inizio'];
	$periodo2Fine=$_REQUEST['periodo2Fine'];
	$codiceVisualizzato=$_REQUEST['codiceVisualizzato'];
	
	$dataPointsPeriodo1 = array();
	$dataPointsPeriodo2 = array();
	$dataPointsFatturato1 = array();
	$dataPointsFatturato2 = array();
	$response=[];
	
	//REDDITIVITA-----------------------------------------------------------------------------------------------------------------------------------------
	//$q="SELECT SUM(redditivita) AS redditivita, ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO)) AS raggruppamento FROM redditivita WHERE (annoMese BETWEEN $periodo1Inizio AND $periodo1Fine)";

	/*$q="SELECT derivedtbl_1.redditivita, derivedtbl_1.raggruppamento, ISNULL(dbo.codici_colori.colore, 'gray') AS color
		FROM (SELECT SUM(redditivita) AS redditivita, ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO)) AS raggruppamento
		FROM dbo.redditivita
		WHERE (annoMese BETWEEN $periodo1Inizio AND $periodo1Fine)";*/

	$q="SELECT derivedtbl_1.redditivita, derivedtbl_1.raggruppamento, ISNULL(dbo.codici_colori.colore, 'gray') AS color
		FROM (SELECT SUM(redditivita) AS redditivita, [$codiceVisualizzato] AS raggruppamento
		FROM dbo.redditivita
		WHERE (annoMese BETWEEN $periodo1Inizio AND $periodo1Fine)";
	
	$q.=" AND ((Id_ARTICOLO ";
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
	
	$q.=" ) GROUP BY [$codiceVisualizzato]) AS derivedtbl_1 LEFT OUTER JOIN
		dbo.codici_colori ON derivedtbl_1.raggruppamento LIKE { fn CONCAT(dbo.codici_colori.codice, '%') }";
	
	/*$q.=" ) GROUP BY ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO))) AS derivedtbl_1 LEFT OUTER JOIN
			dbo.codici_colori ON derivedtbl_1.raggruppamento LIKE { fn CONCAT(dbo.codici_colori.codice, '%') }";*/
	
	//$q.=" ) GROUP BY ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO))";

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
			$dotPeriodo1 = array();
			$dotPeriodo1['label']=str_replace("  ","",$row['raggruppamento']);
			//$dotPeriodo1['label']=$row['raggruppamento'];
			$dotPeriodo1['y']=intval($row['redditivita']);
			$dotPeriodo1['color']=$row['color'];
			array_push($dataPointsPeriodo1,$dotPeriodo1);
		}
	}
	//$q2="SELECT SUM(redditivita) AS redditivita, ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO)) AS raggruppamento FROM redditivita WHERE (annoMese BETWEEN $periodo2Inizio AND $periodo2Fine)";
	
	/*$q2="SELECT derivedtbl_1.redditivita, derivedtbl_1.raggruppamento, ISNULL(dbo.codici_colori.colore, 'gray') AS color
		FROM (SELECT SUM(redditivita) AS redditivita, ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO)) AS raggruppamento
		FROM dbo.redditivita
		WHERE (annoMese BETWEEN $periodo2Inizio AND $periodo2Fine)";*/

	$q2="SELECT derivedtbl_1.redditivita, derivedtbl_1.raggruppamento, ISNULL(dbo.codici_colori.colore, 'gray') AS color
		FROM (SELECT SUM(redditivita) AS redditivita, [$codiceVisualizzato] AS raggruppamento
		FROM dbo.redditivita
		WHERE (annoMese BETWEEN $periodo2Inizio AND $periodo2Fine)";

	$q2.=" AND ((Id_ARTICOLO ";
	foreach ($filtersCodiceArticolo as $CodiceArticolo)
	{
		$q2.="LIKE '$CodiceArticolo') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceMadre as $CodiceMadre)
	{
		$q2.="LIKE '$CodiceMadre%') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceRadice as $CodiceRadice)
	{
		$q2.="LIKE '$CodiceRadice%') OR (Id_ARTICOLO ";
	}
	$q2=substr($q2, 0, -17);
	
	$q2.=" ) GROUP BY [$codiceVisualizzato]) AS derivedtbl_1 LEFT OUTER JOIN
		dbo.codici_colori ON derivedtbl_1.raggruppamento LIKE { fn CONCAT(dbo.codici_colori.codice, '%') } OPTION ( QUERYTRACEON 9481 )";

	/*$q2.=" ) GROUP BY ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO))) AS derivedtbl_1 LEFT OUTER JOIN
			dbo.codici_colori ON derivedtbl_1.raggruppamento LIKE { fn CONCAT(dbo.codici_colori.codice, '%') }";*/

	//$q2.=" ) GROUP BY ISNULL([FAR_ID_ART-RADICE], ISNULL([FAR_ID_ART-MADRE], Id_ARTICOLO))";
	
	$r2=sqlsrv_query($conn,$q2);
	if($r2==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$q2."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row2=sqlsrv_fetch_array($r2))
		{
			$dotPeriodo2 = array();
			$dotPeriodo2['label']=str_replace("  ","",$row2['raggruppamento']);
			//$dotPeriodo2['label']=$row2['raggruppamento'];
			$dotPeriodo2['y']=intval($row2['redditivita']);
			$dotPeriodo2['color']=$row2['color'];
			array_push($dataPointsPeriodo2,$dotPeriodo2);
		}
	}

	//FATTURATO------------------------------------------------------------------------------------------------------------------------------------

	$q3="SELECT derivedtbl_1.fatturato, derivedtbl_1.raggruppamento, ISNULL(dbo.codici_colori.colore, 'gray') AS color
		FROM (SELECT SUM(valore) AS fatturato, [$codiceVisualizzato] AS raggruppamento
		FROM dbo.redditivita
		WHERE (annoMese BETWEEN $periodo1Inizio AND $periodo1Fine)";
	
	$q3.=" AND ((Id_ARTICOLO ";
	foreach ($filtersCodiceArticolo as $CodiceArticolo)
	{
		$q3.="LIKE '$CodiceArticolo') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceMadre as $CodiceMadre)
	{
		$q3.="LIKE '$CodiceMadre%') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceRadice as $CodiceRadice)
	{
		$q3.="LIKE '$CodiceRadice%') OR (Id_ARTICOLO ";
	}
	$q3=substr($q3, 0, -17);
	
	$q3.=" ) GROUP BY [$codiceVisualizzato]) AS derivedtbl_1 LEFT OUTER JOIN
		dbo.codici_colori ON derivedtbl_1.raggruppamento LIKE { fn CONCAT(dbo.codici_colori.codice, '%') }";

	$r3=sqlsrv_query($conn,$q3);
	if($r3==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$q3."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row3=sqlsrv_fetch_array($r3))
		{
			$dotFatturatoPeriodo1 = array();
			$dotFatturatoPeriodo1['label']=str_replace("  ","",$row3['raggruppamento']);
			//$dotFatturatoPeriodo1['label']=$row3['raggruppamento'];
			$dotFatturatoPeriodo1['y']=intval($row3['fatturato']);
			$dotFatturatoPeriodo1['color']=$row3['color'];
			array_push($dataPointsFatturato1,$dotFatturatoPeriodo1);
		}
	}

	$q4="SELECT derivedtbl_1.fatturato, derivedtbl_1.raggruppamento, ISNULL(dbo.codici_colori.colore, 'gray') AS color
		FROM (SELECT SUM(valore) AS fatturato, [$codiceVisualizzato] AS raggruppamento
		FROM dbo.redditivita
		WHERE (annoMese BETWEEN $periodo2Inizio AND $periodo2Fine)";

	$q4.=" AND ((Id_ARTICOLO ";
	foreach ($filtersCodiceArticolo as $CodiceArticolo)
	{
		$q4.="LIKE '$CodiceArticolo') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceMadre as $CodiceMadre)
	{
		$q4.="LIKE '$CodiceMadre%') OR (Id_ARTICOLO ";
	}
	foreach ($filtersCodiceRadice as $CodiceRadice)
	{
		$q4.="LIKE '$CodiceRadice%') OR (Id_ARTICOLO ";
	}
	$q4=substr($q4, 0, -17);
	
	$q4.=" ) GROUP BY [$codiceVisualizzato]) AS derivedtbl_1 LEFT OUTER JOIN
		dbo.codici_colori ON derivedtbl_1.raggruppamento LIKE { fn CONCAT(dbo.codici_colori.codice, '%') }";
	
	$r4=sqlsrv_query($conn,$q4);
	if($r4==FALSE)
	{
		echo "<br><br>Errore esecuzione query<br>Query: ".$q4."<br>Errore: ";
		die(print_r(sqlsrv_errors(),TRUE));
	}
	else
	{
		while($row4=sqlsrv_fetch_array($r4))
		{
			$dotFatturatoPeriodo2 = array();
			$dotFatturatoPeriodo2['label']=str_replace("  ","",$row4['raggruppamento']);
			//$dotFatturatoPeriodo2['label']=$row4['raggruppamento'];
			$dotFatturatoPeriodo2['y']=intval($row4['fatturato']);
			$dotFatturatoPeriodo2['color']=$row4['color'];
			array_push($dataPointsFatturato2,$dotFatturatoPeriodo2);
		}
	}

	array_push($response,json_encode($dataPointsPeriodo1));
	array_push($response,json_encode($dataPointsPeriodo2));
	array_push($response,json_encode($dataPointsFatturato1));
	array_push($response,json_encode($dataPointsFatturato2));
	
	echo json_encode($response);

?>