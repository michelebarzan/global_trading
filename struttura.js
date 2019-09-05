	function topFunction() 
	{
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
	function apri()
	{
		topFunction();
		var body = document.body,html = document.documentElement;
		var offsetHeight = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );
		document.getElementById('stato').value="Aperto";
		document.getElementById('navBar').style.width="300px";
		document.getElementById('nascondi2').style.display="inline-block";
		document.getElementById('nascondi2').value="ME";
		document.getElementById('nascondi3').style.display="inline-block";
		document.getElementById('nascondi3').value="NU";
		document.getElementById('navBar').style.height = offsetHeight+"px";
		var all = document.getElementsByClassName("btnGoToPath");
		for (var i = 0; i < all.length; i++) 
		{
		all[i].style.width = '100%';
		all[i].style.height='50px';
		all[i].style.borderBottom='1px solid #ddd';
		}
	}
	function chiudi()
	{
		document.getElementById('navBar').style.width = "0px";
		document.getElementById('stato').value="Chiuso";
		document.getElementById('nascondi2').value="";
		document.getElementById('nascondi3').value="";
		setTimeout(function()
		{ 
		document.getElementById('navBar').style.height = "0px";
		document.getElementById('nascondi2').style.display="none";
		document.getElementById('nascondi3').style.display="none";
		var all = document.getElementsByClassName("btnGoToPath");
		for (var i = 0; i < all.length; i++) 
		{
		all[i].style.width = '0px';
		all[i].style.height='0px';
		all[i].style.borderBottom='';
		}
		}, 1000);
	}
	function logoutB()
	{
		window.location = 'logout.php';
	}
	function gotopath(path)
	{
		window.location = path;
	}
	function homepage()
	{
		window.location = 'index.php';
	}
	function nascondi()
	{
		var stato=document.getElementById('stato').value;
		if(stato=="Aperto")
		{
		chiudi();
		}
		else
		{
		apri();
		}
	}
	function goToPath(path)
	{
		window.location = path;
	}
	function apriUserSettings()
	{
		document.getElementById("userSettings").style.display="inline-block";
		document.getElementById("userSettingsPadding").style.display="inline-block";
		chiudiNotifiche();
	}
	function chiudiUserSettings()
	{
		document.getElementById("userSettings").style.display="none";
		document.getElementById("userSettingsPadding").style.display="none";
	}
	function apriNotifiche()
	{
		document.getElementById("notifiche").style.display="inline-block";
		document.getElementById("notifichePadding").style.display="inline-block";
		notificaVista();
		chiudiUserSettings();
	}
	function chiudiNotifiche()
	{
		document.getElementById("notifiche").style.display="none";
		document.getElementById("notifichePadding").style.display="none";
	}
	function eliminaNotifiche()
	{
		document.getElementById('containerNotifiche').innerHTML="";
	}
	function aggiungiNotifica(testo)
	{
		document.getElementById('containerNotifiche').innerHTML+="<div class='notificheRow'>"+testo+"</div>";
		document.getElementById("btnNuovaNotifica").style.visibility = "visible";
	}
	function notificaVista()
	{
		document.getElementById("btnNuovaNotifica").style.visibility = "hidden";
	}
	function importaDatiDialog()
	{
		chiudiUserSettings();
		Swal.fire
		({
			title: 'Importa dati',
			type: 'question',
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:'Usa file esistenti',
			cancelButtonText:'Carica nuovi file'
		}).then((result) => 
		{
			if (result.value)
			{
				triggerImportaDati();
			}
			else
			{
				var uploadFileContainer=document.createElement("div");
				uploadFileContainer.setAttribute("style","margin-top:10px;margin-bottom:10px;");
				uploadFileContainer.setAttribute("id","uploadFileContainer");

				var uploadFileInput=document.createElement("input");
				uploadFileInput.setAttribute("type","file");
				uploadFileInput.setAttribute("id","upload-file-input-importaDati");
				uploadFileInput.setAttribute("class","upload-file-input-importaDati");
				uploadFileInput.setAttribute("accept",".csv");
				uploadFileInput.setAttribute("multiple","multiple");
				uploadFileContainer.appendChild(uploadFileInput);

				//Bottone scegli file
				var uploadFileButton=document.createElement("button");
				uploadFileButton.setAttribute("class","upload-file-button-importaDati upload-file-button-importaDati-yellow");
				uploadFileButton.setAttribute("id","upload-file-button-choose-importaDati");
				uploadFileButton.innerHTML="Scegli file <i class='fal fa-file-medical' style='margin-left:10px'></i>";
				uploadFileContainer.appendChild(uploadFileButton);

				//Bottone upload file
				var uploadFileButton=document.createElement("button");
				uploadFileButton.setAttribute("class","upload-file-button-importaDati upload-file-button-importaDati-blue");
				uploadFileButton.setAttribute("id","upload-file-button-upload-importaDati");
				uploadFileButton.innerHTML="Upload file <i class='fal fa-cloud-upload' style='margin-left:10px'></i>";
				uploadFileContainer.appendChild(uploadFileButton);

				//Bottone elimina file
				var uploadFileButton=document.createElement("button");
				uploadFileButton.setAttribute("class","upload-file-button-importaDati upload-file-button-importaDati-red");
				uploadFileButton.setAttribute("id","upload-file-button-delete-importaDati");
				uploadFileButton.innerHTML="Rimuovi file <i class='fal fa-trash' style='margin-left:10px'></i>";
				uploadFileContainer.appendChild(uploadFileButton);

				var uploadFileTable=document.createElement("table");
				uploadFileTable.setAttribute("class","upload-file-table-importaDati");
				uploadFileTable.setAttribute("id","upload-file-table-importaDati");
				
				uploadFileContainer.appendChild(uploadFileTable);

				Swal.fire
				({
					title: 'Importa dati',
					html: uploadFileContainer.outerHTML,
					showConfirmButton:false,
					allowOutsideClick:false,
					allowEscapeKey:false,
					onOpen: setTimeout(function()
							{
								document.getElementById("upload-file-button-choose-importaDati").addEventListener("click", function(){document.getElementById("upload-file-input-importaDati").click();});
								document.getElementById("upload-file-button-upload-importaDati").addEventListener("click", function(){uploadSelectedFIles();});
								document.getElementById("upload-file-button-delete-importaDati").addEventListener("click", function(){removeFiles();});
								$("#upload-file-input-importaDati").change(function ()
								{
									document.getElementById("upload-file-button-choose-importaDati").disabled=true;
									var filesImportaDati = document.getElementById("upload-file-input-importaDati").files;
									for (var i = 0; i < filesImportaDati.length; i++)
									{
										var file=filesImportaDati[i];
										//console.log(file);
										var fileName= file.name;
										var fileSize_kb= file.size;
										var fileSize_mb=fileSize_kb/1000000;
										//console.log(fileName);
										var uploadFileTableRow=document.createElement("tr");

										var uploadFileTableColumn=document.createElement("td");
										uploadFileTableColumn.innerHTML=fileName;
										uploadFileTableRow.appendChild(uploadFileTableColumn);

										var uploadFileTableColumn=document.createElement("td");
										if(fileSize_mb<90)
											uploadFileTableColumn.setAttribute("style","text-align:right;color:green");
										else
										{
											uploadFileTableColumn.setAttribute("style","text-align:right;color:#d43f3a");
											uploadFileTableRow.setAttribute("title","Dimensione massima 100 MB");
										}
										uploadFileTableColumn.innerHTML=fileSize_mb+" MB";
										uploadFileTableRow.appendChild(uploadFileTableColumn);

										var uploadFileTableColumn=document.createElement("td");
										uploadFileTableColumn.setAttribute("style","text-align:left;width:25px");
										uploadFileTableColumn.setAttribute("id","statusUpload"+fileName);
										if(fileSize_mb<90)
											uploadFileTableColumn.innerHTML='<i style="color:#2196F3" class="far fa-pause-circle"></i>';
										else
											uploadFileTableColumn.innerHTML='<i style="color:#d43f3a" class="far fa-exclamation-circle"></i>';
										uploadFileTableRow.appendChild(uploadFileTableColumn);

										document.getElementById("upload-file-table-importaDati").appendChild(uploadFileTableRow);
									}
								});
							}, 100)
				})
			}
		});
	}
	function triggerImportaDati()
	{
		var spinnerContainer=document.createElement("div");
		spinnerContainer.setAttribute("class","toastSpinnerContainer");
		
		var spinWrapper=document.createElement("div");
		spinWrapper.setAttribute("class","toastSpin-wrapper");
		
		var spinner=document.createElement("div");
		spinner.setAttribute("class","toastSpinner");
		
		var spinnerLabel=document.createElement("div");
		spinnerLabel.setAttribute("class","toastSpinnerLabel");
		spinnerLabel.innerHTML="Importazione dati in corso...";
		
		spinWrapper.appendChild(spinner);
		
		spinnerContainer.appendChild(spinWrapper);
		spinnerContainer.appendChild(spinnerLabel);

		importaDati();
		Swal.fire
		({
			title: 'Importa dati',
			html: spinnerContainer.outerHTML,
			showConfirmButton: false,
			allowOutsideClick:false,
			allowEscapeKey:false
		});
	}
	function uploadSelectedFIles()
	{
		var filesImportaDati = document.getElementById("upload-file-input-importaDati").files;
		if(filesImportaDati.length>0)
		{
			var uploadedFiles=0;
			document.getElementById("upload-file-button-upload-importaDati").disabled=true;
			document.getElementById("upload-file-button-choose-importaDati").disabled=true;
			document.getElementById("upload-file-button-delete-importaDati").disabled=true;
			
			for (var i = 0; i < filesImportaDati.length; i++)
			{
				var file=filesImportaDati[i];
				var fileName=file.name;
				var fileSize_kb= file.size;
				var fileSize_mb=fileSize_kb/1000000;
				if(fileSize_mb<90)
				{
					//console.log(fileName);
					document.getElementById("statusUpload"+fileName).innerHTML='<i style="color:#2196F3" class="far fa-spinner-third fa-spin loadingIcon"></i>';
					
					var data= new FormData();
					data.append('file',file);
					data.append('fileNameResponse',fileName);
					$.ajax(
					{
						url:'uploadFileImportaDati.php',
						data:data,
						processData:false,
						contentType:false,
						type:'POST',
						success:function(response)
							{
								//console.log(response)
								if(response.indexOf("ok")>-1)
								{
									var fileNameResponse=response.split("|")[0];
									document.getElementById("statusUpload"+fileNameResponse).innerHTML='<i style="color:green" class="far fa-check-circle"></i>';
									uploadedFiles++;
									
									
										if(uploadedFiles>0 && document.getElementById("upload-file-success-button-importaDati")==null && document.getElementsByClassName("loadingIcon").length==0)
										{
											var successButton=document.createElement("button");
											successButton.setAttribute("class","upload-file-button-importaDati upload-file-success-button-importaDati");
											successButton.setAttribute("id","upload-file-success-button-importaDati");
											successButton.innerHTML="Prosegui con l' importazione dei file caricati<i style='margin-left:10px' class='fal fa-upload'></i>";
											successButton.setAttribute("onclick","triggerImportaDati()");
											document.getElementById("uploadFileContainer").appendChild(successButton);
											console.log("finish");
										}
									
									
								}
								else
								{
									var fileNameResponse=response.split("|")[0];
									document.getElementById("statusUpload"+fileNameResponse).innerHTML='<i style="color:#d43f3a" class="far fa-exclamation-circle"></i>';
								}
							}
					});
				}
			}
		}
	}
	function removeFiles()
	{
		var filesImportaDati = document.getElementById("upload-file-input-importaDati").files;
		if(filesImportaDati.length>0)
		{
			for (var i = 0; i < filesImportaDati.length; i++)
			{
				var fileName=filesImportaDati[i].name;
				var row=document.getElementById("statusUpload"+fileName).parentElement;
				row.remove();
			}
			document.getElementById('upload-file-input-importaDati').value = "";
		}
		document.getElementById("upload-file-button-choose-importaDati").disabled=false;
	}
	function importaDati()
	{
		$.post("importaDati.php",
		function(response, status)
		{
			if(status=="success")
			{
				console.log(response);
				if(response.indexOf("ok")>-1)
				{
					Swal.fire
					({
						type: 'success',
						title: "Dati importati",
						html : "<div style='margin-top:10px;margin-bottom:10px;font-size:15px'>Aggiorna la pagina per rendere effettive le modifiche</div>"
					})
				}
				if(response.indexOf("3rr0rdata")>-1)
				{
					var errors=JSON.parse(response.split("|")[1]);
					var errorMessages=document.createElement("div");
					
					title="Elenco errori";

					errorMessages.setAttribute("style","margin-top:10px;margin-bottom:10px;font-size:15px");

					var notaErrori=document.createElement("div");
					notaErrori.setAttribute("class","error-nota-importaDati");
					notaErrori.innerHTML='<i class="far fa-exclamation-circle" style="margin-right:15px"></i>Ricontrolla i file e prendi nota degli <u>id_log</u>';

					errorMessages.appendChild(notaErrori);

					var errorTable=document.createElement("table");
					errorTable.setAttribute("class","error-table-importaDati");

					var errorTableRow=document.createElement("tr");

					var errorTableColumn=document.createElement("th");
					errorTableColumn.innerHTML="Id log";
					errorTableRow.appendChild(errorTableColumn);

					var errorTableColumn=document.createElement("th");
					errorTableColumn.innerHTML="File (tabella)";
					errorTableRow.appendChild(errorTableColumn);

					errorTable.appendChild(errorTableRow);

					for (var id_log in errors)
					{
						if (errors.hasOwnProperty(id_log))
						{
							var tabella=errors[id_log];

							var errorTableRow=document.createElement("tr");

							var errorTableColumn=document.createElement("td");
							errorTableColumn.innerHTML=id_log;
							errorTableRow.appendChild(errorTableColumn);

							var errorTableColumn=document.createElement("td");
							errorTableColumn.innerHTML=tabella;
							errorTableRow.appendChild(errorTableColumn);

							errorTable.appendChild(errorTableRow);
						}
					}
					
					errorMessages.appendChild(errorTable);
					
					Swal.fire
					({
						type: 'error',
						title: title,
						html : errorMessages.outerHTML
					});
				}
				if(response.indexOf("error")>-1 || response.indexOf("notice")>-1)
				{
					var errorMessages=document.createElement("div");
					title="Errore generale";
					errorMessages.setAttribute("style","margin-top:10px;margin-bottom:10px;font-size:15px");
					errorMessages.innerHTML="Se il problema persiste contatta l' amministratore";

					Swal.fire
					({
						type: 'error',
						title: title,
						html : errorMessages.outerHTML
					});
				}
			}
			else
				console.log("Errore cazzo "+status);
		});
	}
	function setCookie(name,value)
    {
        $.post("setCookie.php",{name,value},
		function(response, status)
		{
            if(status!="success")
				console.log(status);
		});
    }
    function getCookie(name)
    {
        return new Promise(function (resolve, reject) 
        {
            $.get("getCookie.php",{name},
            function(response, status)
            {
                if(status=="success")
                {
                    resolve(response);
                }
                else
                    reject({status});
            });
        });
    }