<?php
	include "Session.php";
	include "connessione.php";

	$pageName="Report redditivita";

	set_time_limit(3000);
?>
<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Nunito|Raleway" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand:300" rel="stylesheet">
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="js_libraries/canvasjs.min.js"></script>
		<title><?php echo $pageName; ?></title>
		<link rel="stylesheet" href="css/styleV4.css" />
		<script src="struttura.js"></script>
		<link rel="stylesheet" href="js_libraries/spinners/spinner.css" />
		<script src="js_libraries/spinners/spinner.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script src="js/reportRedditivita.js"></script>
		<link href="js_libraries/intro.js/introjs.css" rel="stylesheet">
		<script type="text/javascript" src="js_libraries/intro.js/intro.js"></script>
		<style>
			.swal2-title
			{
				font-family:'Montserrat',sans-serif;
				font-size:18px;
			}
			.swal2-content
			{
				font-family:'Montserrat',sans-serif;
				font-size:14px;
			}
			.swal2-confirm,.swal2-cancel
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
		</style>
	</head>
	<body onload="removeCircleSpinner();checkInto()">
		<?php include('struttura.php'); ?>
		<script>
			newCircleSpinner("Caricamento in corso...");
		</script>
		<div id="container">
			<div id="content">
				<div class="logoContainer" >
					<img src="images/logo3.png" alt="Logo" class="logo">
				</div>
				<div class="dataSourceChooserContainer">
					<div class="dataSourceChooserInnerContainer" id="dataSourceChooserInnerContainer">
						<button class="dataSourceChooserButton" onclick="callChart('redditivitaArticoli',this);">Redditivita articoli</button>
						<button class="dataSourceChooserButton" onclick="callChart('redditivitaAgenti',this);">Redditivita agenti</button>
						<button class="dataSourceChooserButton" onclick="callChart('redditivitaClienti',this);">Redditivita clienti</button>
						<button class="dataSourceChooserButton" onclick="callChart('redditivitaArticoliTotale',this);">Redditivita totale articoli</button>
					</div>
				</div>
				<div class="absoluteActionBar defaultHiddenElements" id="absoluteActionBar">
					<!--FILTRO CODICE ARTICOLO-->
					<div class="absoluteActionBarButton defaultHiddenElements" id="filterCodiceArticolo" onclick="closeContextMenu();openContextMenu(event,'CodiceArticolo')">
						Codice articolo
						<i class="far fa-filter absoluteActionBarFilterButton"></i>
					</div>
					<div id="absoluteActionBarFilterMenuCodiceArticolo" class="absoluteActionBarFilterMenu">
						<div class="absoluteActionBarFilterMenuRow" >
							<div>
								<label class="radio" data-tooltip="Nota: per la ricerca dall inizio del codice i due campi di ricerca devono avere la stessa lunghezza">
									<input type="radio" name="radioTipoRicercaCodiceArticolo" onchange="searcFilters('CodiceArticolo')" value="inizio" checked>
									<span>Cerca dall' inizio del codice<i class="far fa-info-circle" style="margin-left:10px" ></i></span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoRicercaCodiceArticolo" onchange="searcFilters('CodiceArticolo')" value="indexOf">
									<span>Cerca su tutto il codice</span>
								</label>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b>Filtra</b></div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCodiceArticolo" onkeyup="searcFilters('CodiceArticolo')" onsearch="searcFilters('CodiceArticolo')" onclick="searcFilters('CodiceArticolo')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<select class="editableTableSelect" id="selectAndOrCodiceArticolo" onchange="searcFilters('CodiceArticolo')">
								<option value="OR">OR</option>
								<option value="AND">AND</option>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCodiceArticolo2" onkeyup="searcFilters('CodiceArticolo')" onsearch="searcFilters('CodiceArticolo')" onclick="searcFilters('CodiceArticolo')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<div class="absoluteActionBarFilterMenuFilterContainer" id="absoluteActionBarFilterMenuFilterContainerCodiceArticolo">
								<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCodiceArticolo">Tutti
									<input type="checkbox" onclick="checkCheckboxes('CodiceArticolo')" id="absoluteActionBarFilterMenuFilterCheckboxAllCodiceArticolo" checked="checked">
									<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
								</label>
								<?php
								
									$query="SELECT DISTINCT [FAR_ID_ARTICOLO] FROM bi_fatart OPTION ( QUERYTRACEON 9481 )";
									$result=sqlsrv_query($conn,$query);
									if($result==FALSE)
									{
										//die(print_r(sqlsrv_errors(),TRUE));
										echo "error";
									}
									else
									{
										while($row=sqlsrv_fetch_array($result))
										{
											?>
											<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCodiceArticolo"><?php echo $row['FAR_ID_ARTICOLO']; ?>
												<input type="checkbox" id="checkboxFilterMenuCodiceArticolo<?php echo $row['FAR_ID_ARTICOLO']; ?>" onchange="checkCheckboxAll('CodiceArticolo')" fieldValue="<?php echo $row['FAR_ID_ARTICOLO']; ?>" class="absoluteActionBarFilterMenuCheckboxCodiceArticolo" checked="checked">
												<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
											</label>
											<?php
										}
									}
								
								?>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow"  style="margin-top:5px;margin-bottom:5px">
							<button style='float:left;' onclick='confermaFiltro()'>Conferma</button>
							<button style='float:right;' class="absoluteActionBarFilterMenuCancelButton" onclick='closeContextMenu()'>Annulla</button>
						</div>
					</div>
					
					<!--FILTRO CODICE MADRE-->
					<div class="absoluteActionBarButton defaultHiddenElements" id="filterCodiceMadre" onclick="closeContextMenu();openContextMenu(event,'CodiceMadre')">
						Codice madre
						<i class="far fa-filter absoluteActionBarFilterButton" ></i>
					</div>
					<div id="absoluteActionBarFilterMenuCodiceMadre" class="absoluteActionBarFilterMenu">
						<div class="absoluteActionBarFilterMenuRow" >
							<div>
								<label class="radio" data-tooltip="Nota: per la ricerca dall inizio del codice i due campi di ricerca devono avere la stessa lunghezza">
									<input type="radio" name="radioTipoRicercaCodiceMadre" onchange="searcFilters('CodiceMadre')" value="inizio" checked>
									<span>Cerca dall' inizio del codice<i class="far fa-info-circle" style="margin-left:10px" ></i></span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoRicercaCodiceMadre" onchange="searcFilters('CodiceMadre')" value="indexOf">
									<span>Cerca su tutto il codice</span>
								</label>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b>Filtra</b></div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCodiceMadre" onkeyup="searcFilters('CodiceMadre')" onsearch="searcFilters('CodiceMadre')" onclick="searcFilters('CodiceMadre')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<select class="editableTableSelect" id="selectAndOrCodiceMadre" onchange="searcFilters('CodiceMadre')">
								<option value="OR">OR</option>
								<option value="AND">AND</option>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCodiceMadre2" onkeyup="searcFilters('CodiceMadre')" onsearch="searcFilters('CodiceMadre')" onclick="searcFilters('CodiceMadre')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<div class="absoluteActionBarFilterMenuFilterContainer" id="absoluteActionBarFilterMenuFilterContainerCodiceMadre">
								<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCodiceMadre">Tutti
									<input type="checkbox" onclick="checkCheckboxes('CodiceMadre')" id="absoluteActionBarFilterMenuFilterCheckboxAllCodiceMadre" checked="checked">
									<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
								</label>
								<?php
								
									$query="SELECT DISTINCT [FAR_ID_ART-MADRE] FROM bi_fatart OPTION ( QUERYTRACEON 9481 )";
									$result=sqlsrv_query($conn,$query);
									if($result==FALSE)
									{
										//die(print_r(sqlsrv_errors(),TRUE));
										echo "error";
									}
									else
									{
										while($row=sqlsrv_fetch_array($result))
										{
											?>
											<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCodiceMadre"><?php echo $row['FAR_ID_ART-MADRE']; ?>
												<input type="checkbox" id="checkboxFilterMenuCodiceMadre<?php echo $row['FAR_ID_ART-MADRE']; ?>" onchange="checkCheckboxAll('CodiceMadre')" fieldValue="<?php echo $row['FAR_ID_ART-MADRE']; ?>" class="absoluteActionBarFilterMenuCheckboxCodiceMadre" checked="checked">
												<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
											</label>
											<?php
										}
									}
								
								?>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow"  style="margin-top:5px;margin-bottom:5px">
							<button style='float:left;' onclick='confermaFiltro()'>Conferma</button>
							<button style='float:right;' class="absoluteActionBarFilterMenuCancelButton" onclick='closeContextMenu()'>Annulla</button>
						</div>
					</div>
					
					<!--FILTRO CODICE RADICE-->
					<div class="absoluteActionBarButton defaultHiddenElements" id="filterCodiceRadice" onclick="closeContextMenu();openContextMenu(event,'CodiceRadice')">
						Codice radice
						<i class="far fa-filter absoluteActionBarFilterButton" ></i>
					</div>
					<div id="absoluteActionBarFilterMenuCodiceRadice" class="absoluteActionBarFilterMenu">
						<div class="absoluteActionBarFilterMenuRow" >
							<div>
								<label class="radio" data-tooltip="Nota: per la ricerca dall inizio del codice i due campi di ricerca devono avere la stessa lunghezza">
									<input type="radio" name="radioTipoRicercaCodiceRadice" onchange="searcFilters('CodiceRadice')" value="inizio" checked>
									<span>Cerca dall' inizio del codice<i class="far fa-info-circle" style="margin-left:10px" ></i></span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoRicercaCodiceRadice" onchange="searcFilters('CodiceRadice')" value="indexOf">
									<span>Cerca su tutto il codice</span>
								</label>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b>Filtra</b></div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCodiceRadice" onkeyup="searcFilters('CodiceRadice')" onsearch="searcFilters('CodiceRadice')" onclick="searcFilters('CodiceRadice')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<select class="editableTableSelect" id="selectAndOrCodiceRadice" onchange="searcFilters('CodiceRadice')">
								<option value="OR">OR</option>
								<option value="AND">AND</option>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCodiceRadice2" onkeyup="searcFilters('CodiceRadice')" onsearch="searcFilters('CodiceRadice')" onclick="searcFilters('CodiceRadice')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<div class="absoluteActionBarFilterMenuFilterContainer" id="absoluteActionBarFilterMenuFilterContainerCodiceRadice">
								<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCodiceRadice">Tutti
									<input type="checkbox" onclick="checkCheckboxes('CodiceRadice')" id="absoluteActionBarFilterMenuFilterCheckboxAllCodiceRadice" checked="checked">
									<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
								</label>
								<?php
								
									$query="SELECT DISTINCT [FAR_ID_ART-RADICE] FROM bi_fatart OPTION ( QUERYTRACEON 9481 )";
									$result=sqlsrv_query($conn,$query);
									if($result==FALSE)
									{
										//die(print_r(sqlsrv_errors(),TRUE));
										echo "error";
									}
									else
									{
										while($row=sqlsrv_fetch_array($result))
										{
											?>
											<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCodiceRadice"><?php echo $row['FAR_ID_ART-RADICE']; ?>
												<input type="checkbox" id="checkboxFilterMenuCodiceRadice<?php echo $row['FAR_ID_ART-RADICE']; ?>" onchange="checkCheckboxAll('CodiceRadice')" fieldValue="<?php echo $row['FAR_ID_ART-RADICE']; ?>" class="absoluteActionBarFilterMenuCheckboxCodiceRadice" checked="checked">
												<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
											</label>
											<?php
										}
									}
								
								?>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow"  style="margin-top:5px;margin-bottom:5px">
							<button style='float:left;' onclick='confermaFiltro()'>Conferma</button>
							<button style='float:right;' class="absoluteActionBarFilterMenuCancelButton" onclick='closeContextMenu()'>Annulla</button>
						</div>
					</div>
					
					<!--FILTRO PERIODO 1-->
					<div class="absoluteActionBarButton defaultHiddenElements" id="filterPeriodo1" onclick="closeContextMenu();openContextMenu(event,'Periodo1')">
						Periodo 1
						<i class="far fa-filter absoluteActionBarFilterButton" ></i>
					</div>
					<div id="absoluteActionBarFilterMenuPeriodo1" class="absoluteActionBarFilterMenu" style="height:100px;">
						<div class="absoluteActionBarFilterMenuRow">
							<b style="float:left">Imposta anno: </b>
							<select class="absoluteActionBarFilterMenuTransparentSelect" style="margin-left:5px" onchange="selectYearFilter('Periodo1',this.value)">
								<option value="" hidden disabled selected>Scegli</option>
								<?php
								
									$query="SELECT DISTINCT LEFT(annoMese, 4) AS anno FROM dbo.redditivita ORDER BY LEFT(annoMese, 4) DESC OPTION ( QUERYTRACEON 9481 )";
									$result=sqlsrv_query($conn,$query);
									if($result==FALSE)
									{
										//die(print_r(sqlsrv_errors(),TRUE));
										echo "error";
									}
									else
									{
										while($row=sqlsrv_fetch_array($result))
										{
											$anno = $row["anno"];
											echo '<option value="'.$anno.'">'.$anno.'</option>';
										}
									}
									
								?>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b style="float:left">Inizio</b><b style="float:right">Fine</b></div>
						<div class="absoluteActionBarFilterMenuRow">
							<select class="absoluteActionBarFilterMenuSelect" id="selectPeriodo1Inizio">
							<?php
								
								$query="SELECT DISTINCT [annoMese] FROM redditivita ORDER BY annoMese ASC OPTION ( QUERYTRACEON 9481 )";
								$result=sqlsrv_query($conn,$query);
								if($result==FALSE)
								{
									//die(print_r(sqlsrv_errors(),TRUE));
									echo "error";
								}
								else
								{
									while($row=sqlsrv_fetch_array($result))
									{
										$annoMese=$row["annoMese"];
										$anno = substr($annoMese, 0, 4);
										$mese = substr($annoMese, 4, 6);
										$meseString=getMeseString($mese);
										echo '<option value="'.$annoMese.'">'.$meseString.' '.$anno.'</option>';
									}
								}
								
							?>
							</select>
							<select class="absoluteActionBarFilterMenuSelect" id="selectPeriodo1Fine" style="margin-left:20px">
							<?php
								
								$query="SELECT DISTINCT [annoMese] FROM redditivita ORDER BY annoMese DESC OPTION ( QUERYTRACEON 9481 )";
								$result=sqlsrv_query($conn,$query);
								if($result==FALSE)
								{
									//die(print_r(sqlsrv_errors(),TRUE));
									echo "error";
								}
								else
								{
									while($row=sqlsrv_fetch_array($result))
									{
										$annoMese=$row["annoMese"];
										$anno = substr($annoMese, 0, 4);
										$mese = substr($annoMese, 4, 6);
										$meseString=getMeseString($mese);
										echo '<option value="'.$annoMese.'">'.$meseString.' '.$anno.'</option>';
									}
								}
								
							?>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow"  style="margin-top:5px;margin-bottom:5px">
							<button style='float:left;' onclick='confermaFiltro()'>Conferma</button>
							<button style='float:right;' class="absoluteActionBarFilterMenuCancelButton" onclick='closeContextMenu()'>Annulla</button>
						</div>
					</div>
					
					<!--FILTRO PERIODO 2-->
					<div class="absoluteActionBarButton defaultHiddenElements" id="filterPeriodo2" onclick="closeContextMenu();openContextMenu(event,'Periodo2')">
						Periodo 2
						<i class="far fa-filter absoluteActionBarFilterButton" ></i>
					</div>
					<div id="absoluteActionBarFilterMenuPeriodo2" class="absoluteActionBarFilterMenu" style="height:100px;">
						<div class="absoluteActionBarFilterMenuRow">
							<b style="float:left">Imposta anno: </b>
							<select class="absoluteActionBarFilterMenuTransparentSelect" style="margin-left:5px" onchange="selectYearFilter('Periodo2',this.value)">
								<option value="" hidden disabled selected>Scegli</option>
								<?php
								
									$query="SELECT DISTINCT LEFT(annoMese, 4) AS anno FROM dbo.redditivita ORDER BY LEFT(annoMese, 4) DESC OPTION ( QUERYTRACEON 9481 )";
									$result=sqlsrv_query($conn,$query);
									if($result==FALSE)
									{
										//die(print_r(sqlsrv_errors(),TRUE));
										echo "error";
									}
									else
									{
										while($row=sqlsrv_fetch_array($result))
										{
											$anno = $row["anno"];
											echo '<option value="'.$anno.'">'.$anno.'</option>';
										}
									}
									
								?>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b style="float:left">Inizio</b><b style="float:right">Fine</b></div>
						<div class="absoluteActionBarFilterMenuRow">
							<select class="absoluteActionBarFilterMenuSelect" id="selectPeriodo2Inizio">
							<?php
								
								$query="SELECT DISTINCT [annoMese] FROM redditivita ORDER BY annoMese ASC OPTION ( QUERYTRACEON 9481 )";
								$result=sqlsrv_query($conn,$query);
								if($result==FALSE)
								{
									//die(print_r(sqlsrv_errors(),TRUE));
									echo "error";
								}
								else
								{
									while($row=sqlsrv_fetch_array($result))
									{
										$annoMese=$row["annoMese"];
										$anno = substr($annoMese, 0, 4);
										$mese = substr($annoMese, 4, 6);
										$meseString=getMeseString($mese);
										echo '<option value="'.$annoMese.'">'.$meseString.' '.$anno.'</option>';
									}
								}
								
							?>
							</select>
							<select class="absoluteActionBarFilterMenuSelect" id="selectPeriodo2Fine" style="margin-left:20px">
							<?php
								
								$query="SELECT DISTINCT [annoMese] FROM redditivita ORDER BY annoMese DESC OPTION ( QUERYTRACEON 9481 )";
								$result=sqlsrv_query($conn,$query);
								if($result==FALSE)
								{
									//die(print_r(sqlsrv_errors(),TRUE));
									echo "error";
								}
								else
								{
									while($row=sqlsrv_fetch_array($result))
									{
										$annoMese=$row["annoMese"];
										$anno = substr($annoMese, 0, 4);
										$mese = substr($annoMese, 4, 6);
										$meseString=getMeseString($mese);
										echo '<option value="'.$annoMese.'">'.$meseString.' '.$anno.'</option>';
									}
								}
								
							?>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow"  style="margin-top:5px;margin-bottom:5px">
							<button style='float:left;' onclick='confermaFiltro()'>Conferma</button>
							<button style='float:right;' class="absoluteActionBarFilterMenuCancelButton" onclick='closeContextMenu()'>Annulla</button>
						</div>
					</div>
					<!--FILTRO CLIENTE-->
					<div class="absoluteActionBarButton defaultHiddenElements" id="filterCliente" onclick="getClienti();closeContextMenu();openContextMenu(event,'Cliente')">
						Cliente
						<i class="far fa-filter absoluteActionBarFilterButton" ></i>
					</div>
					<div id="absoluteActionBarFilterMenuCliente" class="absoluteActionBarFilterMenu">
						<div class="absoluteActionBarFilterMenuRow" >
							<div>
								<label class="radio" data-tooltip="Nota: per la ricerca dall inizio del codice i due campi di ricerca devono avere la stessa lunghezza">
									<input type="radio" name="radioTipoRicercaCliente" onchange="searcFilters('Cliente')" value="inizio" checked>
									<span>Cerca dall' inizio del codice<i class="far fa-info-circle" style="margin-left:10px" ></i></span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoRicercaCliente" onchange="searcFilters('Cliente')" value="indexOf">
									<span>Cerca su tutto il codice</span>
								</label>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b>Ordina per</b></div>
						<div class="absoluteActionBarFilterMenuRow" >
							<div>
								<label class="radio">
									<input type="radio" name="radioTipoOrdinamentoCliente" value="fatturato DESC" onclick="getClienti()" checked>
									<span>Fatturato decrescente</span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoOrdinamentoCliente" value="fatturato ASC" onclick="getClienti()">
									<span>Fatturato crescente</span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoOrdinamentoCliente" value="cliente DESC" onclick="getClienti()">
									<span>Cliente decrescente</span>
								</label>
								<label class="radio">
									<input type="radio" name="radioTipoOrdinamentoCliente" value="cliente ASC" onclick="getClienti()">
									<span>Cliente crescente</span>
								</label>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow" ><b>Filtra</b></div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCliente" onkeyup="searcFilters('Cliente')" onsearch="searcFilters('Cliente')" onclick="searcFilters('Cliente')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<select class="editableTableSelect" id="selectAndOrCliente" onchange="searcFilters('Cliente')">
								<option value="OR">OR</option>
								<option value="AND">AND</option>
							</select>
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<input type="search" class="editableTableSearcFilters" id="searcFiltersCliente2" onkeyup="searcFilters('Cliente')" onsearch="searcFilters('Cliente')" onclick="searcFilters('Cliente')" placeholder="Cerca..." >
						</div>
						<div class="absoluteActionBarFilterMenuRow">
							<div class="absoluteActionBarFilterMenuFilterContainer" id="absoluteActionBarFilterMenuFilterContainerCliente">
								<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCliente">Tutti
									<input type="checkbox" onclick="checkCheckboxes('Cliente')" id="absoluteActionBarFilterMenuFilterCheckboxAllCliente" checked="checked">
									<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
								</label>
								<?php
								
									$query="SELECT * FROM clienti_fatturato ORDER BY fatturato DESC OPTION ( QUERYTRACEON 9481 )";
									$result=sqlsrv_query($conn,$query);
									if($result==FALSE)
									{
										//die(print_r(sqlsrv_errors(),TRUE));
										echo "error";
									}
									else
									{
										while($row=sqlsrv_fetch_array($result))
										{
											?>
											<label class="absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCliente"><?php echo $row['cliente']; ?>
												<input type="checkbox" id="checkboxFilterMenuCliente<?php echo $row['cliente']; ?>" onchange="checkCheckboxAll('Cliente')" fieldValue="<?php echo $row['cliente']; ?>" class="absoluteActionBarFilterMenuCheckboxCliente" checked="checked">
												<span class="absoluteActionBarFilterMenuFilterCheckboxCheckmark"></span>
											</label>
											<?php
										}
									}
								
								?>
							</div>
						</div>
						<div class="absoluteActionBarFilterMenuRow"  style="margin-top:5px;margin-bottom:5px">
							<button style='float:left;' onclick='confermaFiltro()'>Conferma</button>
							<button style='float:right;' class="absoluteActionBarFilterMenuCancelButton" onclick='closeContextMenu()'>Annulla</button>
						</div>
					</div>
					
					<button class="absoluteActionBarButton defaultHiddenElements" id="btnExcelExport" onclick="esportaExcel()">Esporta <i style="margin-left:5px;color:green" class="far fa-file-excel"></i></button>
					<button class="absoluteActionBarButton defaultHiddenElements" id="btnChartExport" onclick='esportaGrafico()'>Esporta <i style="margin-left:5px;color:#f57f28" class="far fa-chart-line"></i></button>

					<div class="defaultHiddenElements" id="btnRaggruppamentoAsseX"  style="float:left">
						<button class="absoluteActionBarButton">
							<label class="radio" style="margin:0">
								<input type="radio" name="radioRaggruppamentoAsseX" value="annoMese" onchange="confermaFiltro()" checked>
								<span style="padding-left:20px">Valori per mese</span>
							</label>
						</button>
						<button class="absoluteActionBarButton">
							<label class="radio" style="margin:0">
								<input type="radio" name="radioRaggruppamentoAsseX" value="anno" onchange="confermaFiltro()">
								<span style="padding-left:20px">Valori per anno</span>
							</label>
						</button>
					</div>

					<button class="absoluteActionBarButton defaultHiddenElements" id="btnProprietaGrafico" onclick='apriProprietaGrafico()'>Proprieta grafico <i style="margin-left:5px" class="far fa-cog"></i></button>

					<button class="absoluteActionBarButton defaultHiddenElements" id="btnColoriArticoli" onclick='getColoriArticoli()'>Colori articoli <i style="margin-left:5px" class="far fa-paint-brush"></i></button>

					<button class="absoluteActionBarButton defaultHiddenElements" id="btnAggiungiTotale" onclick='aggiungiRigaTotale()'>Aggiungi totali <i style="margin-left:5px" class="far fa-sigma"></i></button>

					<select class="absoluteActionBarButton defaultHiddenElements" id="btnCodiceVisualizzato" onchange="newCircleSpinner('Caricamento in corso...');getChartRedditivitaTotaleArticoli()">
						<option value="Id_ARTICOLO" disabled hidden selected>Codice visualizzato</option>
						<option value="Id_ARTICOLO">Codice articolo</option>
						<option value="FAR_ID_ART-MADRE">Codice madre</option>
						<option value="FAR_ID_ART-RADICE">Codice radice</option>
					</select>
					
					<button class="absoluteActionBarButton defaultHiddenElements" id="btnZoomPiu" onclick='zoomPiu()'>Zoom <i style="margin-left:5px" class="far fa-search-plus"></i></button>
					<button class="absoluteActionBarButton defaultHiddenElements" id="btnZoomRipristina" onclick='zoomRipristina()'>Zoom <i style="margin-left:5px" class="far fa-redo-alt"></i></button>
					<button class="absoluteActionBarButton defaultHiddenElements" id="btnZoomMeno" onclick='zoomMeno()'>Zoom <i style="margin-left:5px" class="far fa-search-minus"></i></button>

				</div>
				<div id="containerReportRedditivita" class="absoluteContainer"></div>
			</div>
		</div>
		<div id="footer">
			<b>Global Trading Srl</b>  |  Via Roma, 2, 31020 Lancenigo TV  |  Tel. +39 0422 910243
		</div>
	</body>
</html>
<?php

	function getMeseString($mese)
	{
		switch ($mese) 
		{
			case "01":return "Gennaio";break;
			case "02":return "Febbraio";break;
			case "03":return "Marzo";break;
			case "04":return "Aprile";break;
			case "05":return "Maggio";break;
			case "06":return "Giugno";break;
			case "07":return "Luglio";break;
			case "08":return "Agosto";break;
			case "09":return "Settembre";break;
			case "10":return "Ottobre";break;
			case "11":return "Novembre";break;
			case "12":return "Dicembre";break;
			default:return "";
		}
	}

?>