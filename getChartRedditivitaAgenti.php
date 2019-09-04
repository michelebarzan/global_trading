<?php
	
	include "Session.php";
	include "connessione.php";

	set_time_limit(3000);
	
	$filtersCodiceArticolo=json_decode($_REQUEST['JSONfiltersCodiceArticolo']);
	$filtersCodiceMadre=json_decode($_REQUEST['JSONfiltersCodiceMadre']);
	$filtersCodiceRadice=json_decode($_REQUEST['JSONfiltersCodiceRadice']);
	$raggruppamento=$_REQUEST['raggruppamento'];
	
	$dataPointsContainer = array();
	$agenti=[];
	
	$q="SELECT DISTINCT AAG_DESCRIZIONE FROM dbo.redditivita OPTION ( QUERYTRACEON 9481 )";
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
			$agente=$row['AAG_DESCRIZIONE'];
			array_push($agenti,$agente);
			/*REDDITIVITA--------------------------------------------------------------------------*/
			$dataPoints = array();

			if($raggruppamento=="annoMese")
				$q1="SELECT annoMese, SUM(redditivita) AS redditivita, SUM(valore) AS fatturato FROM dbo.redditivita WHERE (AAG_DESCRIZIONE = '$agente') AND (";
			else
				$q1="SELECT annoMese, anno, SUM(redditivita) AS redditivita, SUM(valore) AS fatturato FROM dbo.redditivita WHERE (AAG_DESCRIZIONE = '$agente') AND (";

			$q1.=" (Id_ARTICOLO ";
			foreach ($filtersCodiceArticolo as $CodiceArticolo)
			{
				$q1.="LIKE '$CodiceArticolo') OR (Id_ARTICOLO ";
			}
			foreach ($filtersCodiceMadre as $CodiceMadre)
			{
				$q1.="LIKE '$CodiceMadre%') OR (Id_ARTICOLO ";
			}
			foreach ($filtersCodiceRadice as $CodiceRadice)
			{
				$q1.="LIKE '$CodiceRadice%') OR (Id_ARTICOLO ";
			}
			$q1=substr($q1, 0, -17);

			if($raggruppamento=="annoMese")
				$q1.=") GROUP BY $raggruppamento,AAG_DESCRIZIONE";
			else
				$q1.=") GROUP BY $raggruppamento,annoMese,AAG_DESCRIZIONE";
			
			if($raggruppamento=="annoMese")
			{
				$q2="SELECT SUM(ISNULL(derivedtbl_1.redditivita, 0)) AS redditivita, dbo.settimaneView.annoMese, SUM(ISNULL(derivedtbl_1.fatturato, 0)) AS fatturato
					FROM ($q1) AS derivedtbl_1 RIGHT OUTER JOIN
					dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
					GROUP BY dbo.settimaneView.annoMese
					ORDER BY dbo.settimaneView.annoMese OPTION ( QUERYTRACEON 9481 )";
			}
			else
			{
				$q2="SELECT SUM(ISNULL(derivedtbl_1.redditivita, 0)) AS redditivita, dbo.settimaneView.anno, SUM(ISNULL(derivedtbl_1.fatturato, 0)) AS fatturato
					FROM ($q1) AS derivedtbl_1 RIGHT OUTER JOIN
					dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
					GROUP BY dbo.settimaneView.anno OPTION ( QUERYTRACEON 9481 )";
			}

			//echo $q2;

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
			/*-----------------------------------------------------------------------------------*/
			/*FATTURATO--------------------------------------------------------------------------*/
			/*$dataPoints2 = array();

			if($raggruppamento=="annoMese")
				$q1="SELECT annoMese, SUM(valore) AS fatturato FROM dbo.redditivita WHERE (AAG_DESCRIZIONE = '$agente') AND (";
			else
				$q1="SELECT annoMese,anno SUM(valore) AS fatturato FROM dbo.redditivita WHERE (AAG_DESCRIZIONE = '$agente') AND (";

			$q1.=" (Id_ARTICOLO ";
			foreach ($filtersCodiceArticolo as $CodiceArticolo)
			{
				$q1.="LIKE '$CodiceArticolo') OR (Id_ARTICOLO ";
			}
			foreach ($filtersCodiceMadre as $CodiceMadre)
			{
				$q1.="LIKE '$CodiceMadre%') OR (Id_ARTICOLO ";
			}
			foreach ($filtersCodiceRadice as $CodiceRadice)
			{
				$q1.="LIKE '$CodiceRadice%') OR (Id_ARTICOLO ";
			}
			$q1=substr($q1, 0, -17);

			if($raggruppamento=="annoMese")
				$q1.=") GROUP BY $raggruppamento,AAG_DESCRIZIONE";
			else
				$q1.=") GROUP BY $raggruppamento,annoMese,AAG_DESCRIZIONE";
			
			if($raggruppamento=="annoMese")
			{
				$q2="SELECT TOP (100) PERCENT SUM(ISNULL(derivedtbl_1.fatturato, 0)) AS fatturato, dbo.settimaneView.annoMese
					FROM ($q1) AS derivedtbl_1 RIGHT OUTER JOIN
					dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
					GROUP BY dbo.settimaneView.annoMese
					ORDER BY dbo.settimaneView.annoMese";
			}
			else
			{
				$q2="SELECT TOP (100) PERCENT SUM(ISNULL(derivedtbl_1.fatturato, 0)) AS fatturato, dbo.settimaneView.anno
					FROM ($q1) AS derivedtbl_1 RIGHT OUTER JOIN
					dbo.settimaneView ON derivedtbl_1.annoMese = dbo.settimaneView.annoMese
					GROUP BY dbo.settimaneView.anno";
			}

			//echo $q2;

			$r3=sqlsrv_query($conn,$q2);
			if($r3==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q2."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				while($row3=sqlsrv_fetch_array($r3))
				{
					$dot2 = array();
					$dot2['label']=$row3[$raggruppamento];
					$dot2['y']=intval($row3['fatturato']);
					array_push($dataPoints2,$dot2);
				}
			}*/
			/*--------------------------------------------------------------------------------------------------*/
			array_push($dataPointsContainer,$dataPoints);
			//array_push($dataPointsContainer,$dataPoints2);
		}
	}
	
	$response=[];
	array_push($response,json_encode($agenti));
	array_push($response,json_encode($dataPointsContainer));
	
	echo json_encode($response);

?>