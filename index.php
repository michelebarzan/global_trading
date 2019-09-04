<?php
	include "Session.php";
	include "connessione.php";

	$pageName="Homepage";
?>
<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Nunito|Raleway" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand:300" rel="stylesheet">
		<link rel="stylesheet" href="fontawesomepro/css/fontawesomepro.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<title><?php echo $pageName; ?></title>
		<link rel="stylesheet" href="css/styleV3.css" />
		<script src="struttura.js"></script>
		<style>
			.swal2-title
			{
				font-family:'Montserrat',sans-serif;
				font-size:18px;
			}
			.swal2-content
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
			.swal2-confirm,.swal2-cancel
			{
				font-family:'Montserrat',sans-serif;
				font-size:13px;
			}
		</style>
	</head>
	<body>
		<?php include('struttura.php'); ?>
		<div id="container">
			<div id="content">
				<div class="logoContainer" >
					<img src="images/logo3.png" alt="Logo" class="logo">
				</div>
				<div class="homepageLinkContainer">
					<div class="homepageLink" data-tooltip="Consulta i report della redditivita di articoli e agenti nel tempo" onclick="gotopath('reportRedditivita.php')">
						<i class="fal fa-chart-bar"></i>
						<div>Report redditivita</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<b>Global Trading Srl</b>  |  Via Roma, 2, 31020 Lancenigo TV  |  Tel. +39 0422 910243
		</div>
	</body>
</html>
