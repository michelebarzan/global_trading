<?php
	
	include "Session.php";
	include "connessione.php";

	set_time_limit(3000);
	
	$filtersCodiceArticolo=json_decode($_REQUEST['JSONfiltersCodiceArticolo']);
	$filtersCodiceMadre=json_decode($_REQUEST['JSONfiltersCodiceMadre']);
	$filtersCodiceRadice=json_decode($_REQUEST['JSONfiltersCodiceRadice']);
	$filtersClienti=json_decode($_REQUEST['JSONfiltersClienti']);
	$raggruppamento=$_REQUEST['raggruppamento'];
	
	$dataPointsContainer = array();
	$clienti=[];
	
	foreach ($filtersClienti as $cliente)
	{
		array_push($clienti,$cliente);
		$dataPoints = array();

		if($raggruppamento=="annoMese")
			$q2="SELECT SUM(ISNULL(derivedtbl_1.redditivita, 0)) AS redditivita, dbo.settimaneView.annoMese, SUM(ISNULL(derivedtbl_1.fatturato, 0)) AS fatturato
				FROM (SELECT annoMese, SUM(redditivita) AS redditivita, SUM(valore) AS fatturato
				FROM dbo.redditivita
				WHERE (ACF_RAGSOC = '$cliente') AND (";
		else
			$q2="SELECT SUM(ISNULL(derivedtbl_1.redditivita, 0)) AS redditivita, dbo.settimaneView.anno, SUM(ISNULL(derivedtbl_1.fatturato, 0)) AS fatturato
				FROM (SELECT annoMese, SUM(redditivita) AS redditivita, SUM(valore) AS fatturato
				FROM dbo.redditivita
				WHERE (ACF_RAGSOC = '$cliente') AND (";
		//$q2="SELECT $raggruppamento, SUM(redditivita) AS redditivita FROM dbo.redditivita WHERE ACF_RAGSOC='$cliente' AND (";

		$q2.=" (Id_ARTICOLO ";
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

		if($raggruppamento=="annoMese")
			$q2.=") GROUP BY annoMese, ACF_RAGSOC) AS derivedtbl_1 RIGHT OUTER JOIN
					dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
					GROUP BY dbo.settimaneView.annoMese
					ORDER BY dbo.settimaneView.annoMese OPTION ( QUERYTRACEON 9481 )";
		else
			$q2.=") GROUP BY annoMese, ACF_RAGSOC) AS derivedtbl_1 RIGHT OUTER JOIN
					dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
					GROUP BY dbo.settimaneView.anno
					ORDER BY dbo.settimaneView.anno OPTION ( QUERYTRACEON 9481 )";
		//$q2.=") GROUP BY $raggruppamento,ACF_RAGSOC ORDER BY $raggruppamento";
		
		//echo $q2."\n\n";
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
				$dot = array();
				$dot['label']=$row2[$raggruppamento];
				$dot['y']=[intval($row2['redditivita']),intval($row2['fatturato'])];
				array_push($dataPoints,$dot);
			}
		}
		array_push($dataPointsContainer,$dataPoints);
		//$dataPointsContainer["dataPoints"]=$dataPoints;
		//$dataPointsContainer["type"]="column";
	}
	
	$response=[];
	array_push($response,json_encode($clienti));
	array_push($response,json_encode($dataPointsContainer));
	
	echo json_encode($response);

?>