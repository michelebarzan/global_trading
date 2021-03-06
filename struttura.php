	<?php $appName="gb_report"; ?>
	<div id="header" class="header" >
		<input type="button" id="nascondi" value="" onclick="nascondi()" data-toggle='tooltip' title='Apri menu' />
		<div id="pageName" class="pageName"><?php echo $pageName; ?></div>
		<div id="user" class="user">
			<div id="username"><?php echo $_SESSION['Username']; ?></div>
			<input type="button" value="" id="btnUser">
			<input type="button" value="" onclick="apriNotifiche()" id="btnNotifica">
			<input type="button" id="btnNuovaNotifica" value="" >
			<div id="notifichePadding"></div>
			<div id="notifiche">
				<script>
					document.getElementById("user").addEventListener("mouseover", function()
					{
						if(document.getElementById('notifiche').style.display=="inline-block")
							apriNotifiche();	
						if(document.getElementById('notifichePadding').style.display=="inline-block")
							apriNotifiche();	
					});
				
					document.getElementById("notifiche").addEventListener("mouseover", function()
					{
						apriNotifiche();							
					});
					document.getElementById("notifiche").addEventListener("mouseout", function()
					{
						chiudiNotifiche();							
					});
					document.getElementById("notifichePadding").addEventListener("mouseover", function()
					{
						apriNotifiche();							
					});
				</script>
				<div id="userSettingsRow1">
					<div id="titoloUserSettings">Notifiche</div>
					<input type="button" value="" id="btnChiudiUserSettings" onclick="chiudiNotifiche()">
				</div>
				<div id="containerNotifiche">
					<div id="nessunaNotifica">
						Nessuna notifica
					</div>
				</div>
			</div>
			
			<input type="button" value="" onclick="apriUserSettings()" id="btnUserSettings">
			<div id="userSettingsPadding"></div>
			<div id="userSettings">
				<script>
					document.getElementById("user").addEventListener("mouseover", function() 
					{
						if(document.getElementById('userSettings').style.display=="inline-block")
							apriUserSettings();	
						if(document.getElementById('userSettingsPadding').style.display=="inline-block")
							apriUserSettings();	
					});
				
					document.getElementById("userSettings").addEventListener("mouseover", function()
					{
						apriUserSettings();							
					});
					document.getElementById("userSettings").addEventListener("mouseout", function()
					{
						chiudiUserSettings();							
					});
					document.getElementById("userSettingsPadding").addEventListener("mouseover", function()
					{
						apriUserSettings();							
					});
					setInterval(function()
					{
						if(document.getElementById('btnUserSettings').offsetWidth!="24")
						{
							chiudiUserSettings();
							chiudiNotifiche();	
						}
					}, 100);
				</script>
				<div id="userSettingsRow1">
					<div id="titoloUserSettings">Impostazioni</div>
					<input type="button" value="" id="btnChiudiUserSettings" onclick="chiudiUserSettings()">
				</div>
				<div id="userSettingsRow2">
					<?php getNomeCognome($conn,$_SESSION['Username']); ?>
				</div>
				<div id="userSettingsRow2">
					<a id="userSettingsCambiaPassword" href="cambiaPassword.html">Cambia password</a>
				</div>
				<div id="userSettingsRow2">
					<button class="userSettingsImportaDati" onclick="importaDatiDialog()">Importa dati<i class="fal fa-download" style="margin-left:15px;"></i></button>
				</div>
				<!--<div id="userSettingsRow2">
					<button class="userSettingsImportaDati intro-tutorial-location"  data-intro="Troverai qua il pulsante per avviare il tutorial" onclick="">Tutorial<i class="far fa-question" style="margin-left:15px;"></i></button>
				</div>-->
				<div id="permessiUserSettings">
					<div id="userSettingsRow3">
						Hai accesso alle pagine:
					</div>
					<?php getPermessi($conn,$_SESSION['Username'],$appName); ?>
				</div>
			</div>
			<input type="button" value="Logout" id="btnLogout" onclick="logoutB()">
		</div>
	</div>

	<div id="navBar">
		<input type="button" id="nascondi2" value="ME" onclick="nascondi()" data-toggle='tooltip' title='Chiudi menu' />
		<input type="button" id="nascondi3" value="NU" onclick="nascondi()" data-toggle='tooltip' title='Chiudi menu' />
		<input type="hidden" id="stato" value="Chiuso" />
		<input type="button" value="Homepage" data-toggle='tooltip' title='Homepage' class="btnGoToPath" onclick="goToPath('index.php')" />
		<input type="button" value="Report redditivita" data-toggle='tooltip' title='Report redditivita' class="btnGoToPath" onclick="goToPath('reportRedditivita.php')" />
	</div>
	
	<?php 
		$id_utente=getIdUtente($conn,$_SESSION['Username']);
		if(!checkPermessi($conn,$id_utente,$pageName))
		{
			echo "<div style='width:100%;height:200px;line-height:200px;text-align:center;font-weight:bold;color:red;font-family:".htmlspecialchars(json_encode('Montserrat')).",sans-serif'>Accesso alla pagina non consentito</div>";
			echo '<div id="footer">
					<b>Oasis Group</b>  |  Via Favola 19 33070 San Giovanni PN  |  Tel. +39 0434654752
				</div>';
			echo '</body>';
			echo '</html>';
			die();
		}
	
		function checkPermessi($conn,$id_utente,$pageName) 
		{
			$q="SELECT permesso FROM permessi_pagine,elenco_pagine WHERE permessi_pagine.pagina=elenco_pagine.id_pagina AND utente=$id_utente AND nomePagina='$pageName'";
			$r=sqlsrv_query($conn,$q);
			if($r==FALSE)
			{
				echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
				die(print_r(sqlsrv_errors(),TRUE));
			}
			else
			{
				$rows = sqlsrv_has_rows( $r );
				if ($rows === true)
				{
					while($row=sqlsrv_fetch_array($r))
					{
						if($row['permesso']=='completo')
							return true;
						else
							return false;
					}
				}
				else
					return false;
			}
		}
		function getNomeCognome($conn,$username) 
		{
			$q="SELECT nome,cognome FROM utenti WHERE username='$username'";
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
					echo $row['nome']." ".$row['cognome'];
				}
			}
		}
		function getIdUtente($conn,$username) 
		{
			$q="SELECT id_utente FROM utenti WHERE username='$username'";
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
					return $row['id_utente'];
				}
			}
		}
		function getPermessi($conn,$username,$appName) 
		{
			$q="SELECT nomePagina FROM permessi_pagine,elenco_pagine WHERE permessi_pagine.pagina=elenco_pagine.id_pagina AND applicazione='$appName' AND permesso='completo' AND utente=".getIdUtente($conn,$username);
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
					echo $row['nomePagina']."<br>";
				}
			}
			/*$q="SELECT DISTINCT descrizione,pagina FROM accesso_pagine";
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
					$q2="SELECT accesso_pagine.accesso FROM accesso_pagine,utenti WHERE accesso_pagine.utente=utenti.id_utente AND utenti.username='$username' AND accesso_pagine.pagina='".$row['pagina']."'";
					$r2=sqlsrv_query($conn,$q2);
					if($r2==FALSE)
					{
						echo "<br><br>Errore esecuzione query<br>Query: ".$q2."<br>Errore: ";
						die(print_r(sqlsrv_errors(),TRUE));
					}
					else
					{
						$rows = sqlsrv_has_rows( $r2 );  
						if ($rows === true)  
						{						
							while($row2=sqlsrv_fetch_array($r2))
							{
								if($row2['accesso']!='negato')
									echo $row['descrizione']."<br>";
							}
						}
						else
							echo $row['descrizione']."<br>";
					}
				}
			}*/
		}
	?>