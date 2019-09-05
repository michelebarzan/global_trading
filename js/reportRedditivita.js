	var inValues={};
	var inValuesAll={};
	var filters=["CodiceArticolo","CodiceMadre","CodiceRadice","Cliente"];
	var grafico="";
	var chart,chart1,chart3,chart4;

	function callChart(chart,button)
	{
		checkPin(button);
		newCircleSpinner("Caricamento in corso...");
		if(chart=="redditivitaArticoli")
		{
			setTimeout(function()
			{
				resetFilters();getChartRedditivitaArticoli();
			}, 300);
		}
		if(chart=="redditivitaAgenti")
		{
			setTimeout(function()
			{
				resetFilters();getChartRedditivitaAgenti();
			}, 300);
		}
		if(chart=="redditivitaArticoliTotale")
		{
			setTimeout(function()
			{
				resetFilters();getChartRedditivitaTotaleArticoli();
			}, 300);
		}
		if(chart=="redditivitaClienti")
		{
			setTimeout(function()
			{
				resetFilters();getChartRedditivitaClienti();
			}, 300);
		}
	}
	
	function checkPin(button)
	{
		var all = document.getElementsByClassName("dataSourceChooserButtonPin");
		for (var i = 0; i < all.length; i++) 
		{
			all[i].style.display="none";
		}
		button.innerHTML+='<i class="fas fa-thumbtack dataSourceChooserButtonPin" style="display:inline-block"></i>';
	}
	function hideFilters()
	{
		chart=null;
		chart1=null;
		var all = document.getElementsByClassName("defaultHiddenElements");
		for (var i = 0; i < all.length; i++) 
		{
			all[i].style.display="none";
		}
	}
	function useFilter(id)
	{
		document.getElementById(id).style.display="inline-block";
	}
	function getRaggruppamento()
	{
		var raggruppamento
		var radios = document.getElementsByName('radioRaggruppamentoAsseX');

		for (var i = 0, length = radios.length; i < length; i++)
		{
			if (radios[i].checked)
			{
				// do whatever you want with the checked radio
				raggruppamento=radios[i].value;

				// only one radio can be logically checked, don't check the rest
				break;
			}
		}
		return raggruppamento;
	}
	function zoomPiu()
	{
		newMouseSpinner(event);
		var height=parseInt(document.getElementById("containerReportRedditivita").offsetHeight);
		var newHeight=height+100;
		document.getElementById("containerReportRedditivita").style.height=newHeight+"px";
		setTimeout(function()
		{
			chart.render();
			try{
				chart1.render();
			}
			catch(e){}
			removeMouseSpinner();
		}, 500);
	}
	function zoomRipristina()
	{
		newMouseSpinner(event);
		var newHeight=500;
		document.getElementById("containerReportRedditivita").style.height=newHeight+"px";
		setTimeout(function()
		{
			chart.render();
			try{
				chart1.render();
			}
			catch(e){}
			removeMouseSpinner();
		}, 1000);
	}
	function zoomMeno()
	{
		newMouseSpinner(event);
		var height=parseInt(document.getElementById("containerReportRedditivita").offsetHeight);
		var newHeight=height-100;
		document.getElementById("containerReportRedditivita").style.height=newHeight+"px";
		setTimeout(function()
		{
			chart.render();
			try{
				chart1.render();
			}
			catch(e){}
			removeMouseSpinner();
		}, 500);
	}
	function uncheckAll(filter)
	{
		document.getElementById("absoluteActionBarFilterMenuFilterCheckboxAll"+filter).checked=false;
		checkCheckboxes(filter);
		var all = document.getElementsByClassName("absoluteActionBarFilterMenuCheckbox"+filter);
		for (var i = 0; i < all.length; i++) 
		{
			all[i].checked=false;
		}
	}
	function checkTop(filter,n)
	{
		document.getElementById("absoluteActionBarFilterMenuFilterCheckboxAll"+filter).checked=false;
		checkCheckboxes(filter);
		var all = document.getElementsByClassName("absoluteActionBarFilterMenuCheckbox"+filter);
		for (var i = 0; i < n; i++) 
		{
			all[i].checked=true;
		}
	}
	function getChartRedditivitaArticoli()
	{
		document.getElementById("containerReportRedditivita").innerHTML="";
		if(grafico!="redditivitaArticoli")
		{
			uncheckAll('CodiceRadice');
			uncheckAll('CodiceMadre');
		}
		var error=false;
		grafico="redditivitaArticoli";
		
		hideFilters();
		useFilter("filterCodiceArticolo");
		useFilter("filterCodiceMadre");
		useFilter("filterCodiceRadice");
		useFilter("btnExcelExport");
		useFilter("btnChartExport");
		useFilter("absoluteActionBar");
		useFilter("btnRaggruppamentoAsseX");
		useFilter("btnProprietaGrafico");
		useFilter("btnZoomPiu");
		useFilter("btnZoomRipristina");
		useFilter("btnZoomMeno");
		
		getFilters();

		var raggruppamento=getRaggruppamento();

		if(raggruppamento=="annoMese")
			var defaultInterval=50000;
		else
			var defaultInterval=1000000;
						
		if(inValues["CodiceArticolo"].length==inValuesAll["CodiceArticolo"].length)
		{
			var JSONfiltersCodiceArticolo=JSON.stringify(["%"]);
			var subtitleText="Articoli (Tutti)";
		}
		else
		{
			if(inValues["CodiceArticolo"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceArticolo=JSON.stringify(inValues["CodiceArticolo"]);
			if(inValues["CodiceArticolo"].length==0)
				var subtitleText="Articoli (Nessuno)";
			else
				var subtitleText="Articoli ("+inValues["CodiceArticolo"].toString()+")";
		}
		if(inValues["CodiceMadre"].length==inValuesAll["CodiceMadre"].length)
		{
			var JSONfiltersCodiceMadre=JSON.stringify(["%"]);
			var subtitleText2="Articoli madre (Tutti)";
		}
		else
		{
			if(inValues["CodiceMadre"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			
			var JSONfiltersCodiceMadre=JSON.stringify(inValues["CodiceMadre"]);
			if(inValues["CodiceMadre"].length==0)
				var subtitleText2="Articoli madre (Nessuno)";
			else
				var subtitleText2="Articoli madre ("+inValues["CodiceMadre"].toString()+")";
		}
		if(inValues["CodiceRadice"].length==inValuesAll["CodiceRadice"].length)
		{
			var JSONfiltersCodiceRadice=JSON.stringify(["%"]);
			var subtitleText3="Articoli radice (Tutti)";
		}
		else
		{
			if(inValues["CodiceRadice"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceRadice=JSON.stringify(inValues["CodiceRadice"]);
			if(inValues["CodiceRadice"].length==0)
				var subtitleText3="Articoli radice (Nessuno)";
			else
				var subtitleText3="Articoli radice ("+inValues["CodiceRadice"].toString()+")";
		}
		if(inValues["CodiceArticolo"].length==0 && inValues["CodiceMadre"].length==0 && inValues["CodiceRadice"].length==0)
		{
			Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'Nessun filtro selezionato'
				});
				error=true;
		}
		if(!error)
		{
			/*console.log(inValues["CodiceArticolo"]);
			console.log(inValues["CodiceMadre"]);
			console.log(inValues["CodiceRadice"]);*/
			
			//newCircleSpinner("Caricamento in corso...");
			//document.getElementById("containerCircleSpinner").style.display="table";
			
			$.post("getChartRedditivitaArticoli.php",
			{
				JSONfiltersCodiceArticolo,
				JSONfiltersCodiceMadre,
				JSONfiltersCodiceRadice,
				raggruppamento
			},
			function(response, status)
			{
				if(status=="success")
				{
					//console.log(response);
					var responseArray=[];
					var responseArrayObj = JSON.parse(response);
					for (var key in responseArrayObj)
					{
						responseArray.push(responseArrayObj[key]);							
					}
					var dataPointsRedditivita=[];
					var dataPointsRedditivitaObj = JSON.parse(responseArray[0]);
					for (var key in dataPointsRedditivitaObj)
					{
						dataPointsRedditivita.push(dataPointsRedditivitaObj[key]);							
					}
					var dataPointsValore=[];
					var dataPointsValoreObj = JSON.parse(responseArray[1]);
					for (var key in dataPointsValoreObj)
					{
						dataPointsValore.push(dataPointsValoreObj[key]);							
					}
					
					chart = new CanvasJS.Chart("containerReportRedditivita",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Redditivita articoli",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									var annomese=String(e.entries[i].dataPoint.label);
									var anno=annomese.substring(0, 4);
									var mese=annomese.substring(4, 6);
									annomese= getMese(mese)+" "+anno;
									var val=e.entries[i].dataPoint.y+" €";
									content="Mese: "+annomese+"<br>Valore: "+val;
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12,
							valueFormatString:"####### €",
							interval: defaultInterval
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}
						},
						legend:
						{
							fontSize: 12,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
							cursor: "pointer",
							dockInsidePlotArea: false,
							itemclick: toggleDataSeries
						},
						data: [{
							name: "Redditivita",
							type: "line",
							showInLegend: true,
							dataPoints: dataPointsRedditivita
						},
						{
							name: "Valore",
							type: "line",
							showInLegend: true,
							dataPoints: dataPointsValore
						}]
					});
						
					function toggleDataSeries(e)
					{
						if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) 
						{
							e.dataSeries.visible = false;
						}
						else
						{
							e.dataSeries.visible = true;
						}
						chart.render();
					}
					chart.render();
					removeCircleSpinner();
					//document.getElementById("containerCircleSpinner").style.display="none";
				}
				else
					console.log(status);
			});
		}
		else
			removeCircleSpinner();
	}
	function aggiungiRigaTotale()
	{
		var hide=false;
		for(var i=0;i<chart.options.data.length;i++)
		{
			if(chart.options.data[i].name=='Totale')
			{
				hide=true;
			}
		}
		if(!hide)
		{
			var newDataPoints=[];
			var xNewDataPoints=[];
			var labelNewDataPoints=[];
			var yNewDataPoints=[];

			for(var i=0;i<chart.options.data.length;i++)
			{
				var dataPoints=chart.options.data[i].dataPoints;
				var yDataPoints=[];
				for(var j=0;j<dataPoints.length;j++)
				{
					//console.log(dataPoints[j].y[0]+","+dataPoints[j].y[1]);
					if(yNewDataPoints[j]==undefined)
						yNewDataPoints[j]=[dataPoints[j].y[0],dataPoints[j].y[1]];
					else
						yNewDataPoints[j]=[yNewDataPoints[j][0]+dataPoints[j].y[0],yNewDataPoints[j][1]+dataPoints[j].y[1]];
					xNewDataPoints[j]=dataPoints[j].x;
					labelNewDataPoints[j]=dataPoints[j].label;

					yDataPoints.push(dataPoints[j].y);
				}
			}
			
			for(var j=0;j<xNewDataPoints.length;j++)
			{
				newDataPoints.push({x:xNewDataPoints[j],y:yNewDataPoints[j],label:labelNewDataPoints[j]});
			}
			//console.log(newDataPoints);

			var newDataSeries=
			{
				color:"red",
				fillOpacity:.3,
				lineDashType: "longDashDot",
				lineThickness: 2,
				type : "rangeArea",
				name : "Totale",
				showInLegend : true,
				dataPoints : newDataPoints
			};
			chart.options.data.push(newDataSeries);
			chart.render();
		}
		/*
		var hide=false;
		for(var i=0;i<chart.options.data.length;i++)
		{
			if(chart.options.data[i].name=='Totale')
			{
				hide=true;
			}
		}
		if(!hide)
		{
			var newDataPoints=[];
			var xNewDataPoints=[];
			var labelNewDataPoints=[];
			var yNewDataPoints=[];

			for(var i=0;i<chart.options.data.length;i++)
			{
				var dataPoints=chart.options.data[i].dataPoints;
				var yDataPoints=[];
				for(var j=0;j<dataPoints.length;j++)
				{
					if(yNewDataPoints[j]==undefined)
						yNewDataPoints[j]=dataPoints[j].y;
					else
						yNewDataPoints[j]=yNewDataPoints[j]+dataPoints[j].y;
					xNewDataPoints[j]=dataPoints[j].x;
					labelNewDataPoints[j]=dataPoints[j].label;

					yDataPoints.push(dataPoints[j].y);
				}
			}
			
			for(var j=0;j<xNewDataPoints.length;j++)
			{
				newDataPoints.push({x:xNewDataPoints[j],y:yNewDataPoints[j],label:labelNewDataPoints[j]});
			}

			var newDataSeries=
			{
				color:"red",
				lineDashType: "longDashDot",
				lineThickness: 2,
				type : "line",
				name : "Totale",
				showInLegend : true,
				dataPoints : newDataPoints
			};
			chart.options.data.push(newDataSeries);
			chart.render();
		}
		*/
	}
	function getChartRedditivitaAgenti()
	{
		document.getElementById("containerReportRedditivita").innerHTML="";
		if(grafico!="redditivitaAgenti")
		{
			uncheckAll('CodiceRadice');
			uncheckAll('CodiceMadre');
		}
		var error=false;
		grafico="redditivitaAgenti";
		
		hideFilters();
		useFilter("filterCodiceArticolo");
		useFilter("filterCodiceMadre");
		useFilter("filterCodiceRadice");
		useFilter("btnExcelExport");
		useFilter("btnChartExport");
		useFilter("absoluteActionBar");
		useFilter("btnRaggruppamentoAsseX");
		useFilter("btnProprietaGrafico");
		useFilter("btnZoomPiu");
		useFilter("btnZoomRipristina");
		useFilter("btnZoomMeno");
		useFilter("btnAggiungiTotale");
		
		getFilters();

		var raggruppamento=getRaggruppamento();

		if(raggruppamento=="annoMese")
			var defaultInterval=10000;
		else
			var defaultInterval=100000;
						
		if(inValues["CodiceArticolo"].length==inValuesAll["CodiceArticolo"].length)
		{
			var JSONfiltersCodiceArticolo=JSON.stringify(["%"]);
			var subtitleText="Articoli (Tutti)";
		}
		else
		{
			if(inValues["CodiceArticolo"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceArticolo=JSON.stringify(inValues["CodiceArticolo"]);
			if(inValues["CodiceArticolo"].length==0)
				var subtitleText="Articoli (Nessuno)";
			else
				var subtitleText="Articoli ("+inValues["CodiceArticolo"].toString()+")";
		}
		if(inValues["CodiceMadre"].length==inValuesAll["CodiceMadre"].length)
		{
			var JSONfiltersCodiceMadre=JSON.stringify(["%"]);
			var subtitleText2="Articoli madre (Tutti)";
		}
		else
		{
			if(inValues["CodiceMadre"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			
			var JSONfiltersCodiceMadre=JSON.stringify(inValues["CodiceMadre"]);
			if(inValues["CodiceMadre"].length==0)
				var subtitleText2="Articoli madre (Nessuno)";
			else
				var subtitleText2="Articoli madre ("+inValues["CodiceMadre"].toString()+")";
		}
		if(inValues["CodiceRadice"].length==inValuesAll["CodiceRadice"].length)
		{
			var JSONfiltersCodiceRadice=JSON.stringify(["%"]);
			var subtitleText3="Articoli radice (Tutti)";
		}
		else
		{
			if(inValues["CodiceRadice"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceRadice=JSON.stringify(inValues["CodiceRadice"]);
			if(inValues["CodiceRadice"].length==0)
				var subtitleText3="Articoli radice (Nessuno)";
			else
				var subtitleText3="Articoli radice ("+inValues["CodiceRadice"].toString()+")";
		}
		if(inValues["CodiceArticolo"].length==0 && inValues["CodiceMadre"].length==0 && inValues["CodiceRadice"].length==0)
		{
			Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'Nessun filtro selezionato'
				});
				error=true;
		}
		if(!error)
		{
			//document.getElementById("containerCircleSpinner").style.display="table";
			//newCircleSpinner("Caricamento in corso...");
			$.post("getChartRedditivitaAgenti.php",
			{
				JSONfiltersCodiceArticolo,
				JSONfiltersCodiceMadre,
				JSONfiltersCodiceRadice,
				raggruppamento
			},
			function(response, status)
			{
				if(status=="success")
				{
					//console.log(response);
					
					var responseArray=[];
					var responseArrayObj = JSON.parse(response);
					for (var key in responseArrayObj)
					{
						responseArray.push(responseArrayObj[key]);							
					}
					
					var agenti=[];
					var agentiObj = JSON.parse(responseArray[0]);
					for (var key in agentiObj)
					{
						agenti.push(agentiObj[key]);							
					}
					
					var dataPointsContainer=[];
					var dataPointsContainerObj = JSON.parse(responseArray[1]);
					for (var key in dataPointsContainerObj)
					{
						dataPointsContainer.push(dataPointsContainerObj[key]);							
					}
					
					var dataPoints=[];

					/*for (var i = 0; i < agenti.length; i++)
					{
						var agente=agenti[i];
						var dataPointsArray=dataPointsContainer[i];
						dataPoints.push
						({
							type: "line",
							name: agente,
							showInLegend: true,
							dataPoints:dataPointsArray
						});
					}

					var x=i;
					for (var j = 0; j < agenti.length; j++)
					{
						var agente=agenti[j];
						var dataPointsArray=dataPointsContainer[x];
						dataPoints.push
						({
							type: "area",
							name: agente,
							showInLegend: true,
							dataPoints:dataPointsArray
						});
						x++;
					}*/
					
					for (var i = 0; i < dataPointsContainer.length; i++)
					{
						var agente=agenti[i];
						var dataPointsArray=dataPointsContainer[i];
						dataPoints.push
						({
							type: "rangeArea",
							name: agente,
							showInLegend: true,
							dataPoints:dataPointsArray
						});
					}
					//console.log(dataPoints);
					chart = new CanvasJS.Chart("containerReportRedditivita",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Redditivita agenti",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									//console.log(e.entries[i].dataSeries.name);
									var agente=e.entries[i].dataSeries.name;
									var annomese=String(e.entries[i].dataPoint.label);
									var anno=annomese.substring(0, 4);
									var mese=annomese.substring(4, 6);//console.log(mese);
									annomese= getMese(mese)+" "+anno;
									var val=e.entries[i].dataPoint.y;
									//console.log(val);
									var yRedditivita=val[0];
									var yFatturato=val[1];
									content="Agente: "+agente+"<br>Mese: "+annomese+"<br>Redditivita: "+yRedditivita+" €<br>Fatturato: "+yFatturato+" €";
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12,
							valueFormatString:"####### €",
							interval: defaultInterval
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}
						},
						legend:
						{
							fontSize: 12,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
							cursor: "pointer",
							dockInsidePlotArea: false,
							itemclick: toggleDataSeries
						},
						data:dataPoints
					});
					function toggleDataSeries(e)
					{
						if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) 
						{
							e.dataSeries.visible = false;
						}
						else
						{
							e.dataSeries.visible = true;
						}
						chart.render();
					}
					chart.render();
					removeCircleSpinner();
					//document.getElementById("containerCircleSpinner").style.display="none";
				}
				else
					console.log(status);
			});
		}
		else
			removeCircleSpinner();
	}
	function getChartRedditivitaClienti()
	{
		document.getElementById("containerReportRedditivita").innerHTML="";
		if(grafico!="redditivitaClienti")
		{
			uncheckAll('CodiceRadice');
			uncheckAll('CodiceMadre');
		}
		//getClienti();
		if(grafico!="redditivitaClienti")
		{
			document.getElementById("absoluteActionBarFilterMenuFilterCheckboxAllCliente").checked=false;
			checkCheckboxes('Cliente');
			var all = document.getElementsByClassName("absoluteActionBarFilterMenuCheckboxCliente");
			for (var i = 0; i < all.length; i++) 
			{
				if(i<20)
				{
					all[i].checked=true;
				}
			}
		}
		var error=false;
		grafico="redditivitaClienti";
		
		hideFilters();
		useFilter("filterCodiceArticolo");
		useFilter("filterCodiceMadre");
		useFilter("filterCodiceRadice");
		useFilter("btnExcelExport");
		useFilter("btnChartExport");
		useFilter("absoluteActionBar");
		useFilter("filterCliente");
		useFilter("btnRaggruppamentoAsseX");
		useFilter("btnProprietaGrafico");
		useFilter("btnZoomPiu");
		useFilter("btnZoomRipristina");
		useFilter("btnZoomMeno");
		useFilter("btnAggiungiTotale");
		
		getFilters();

		var raggruppamento=getRaggruppamento();

		if(raggruppamento=="annoMese")
			var defaultInterval=4000;
		else
			var defaultInterval=20000;
		
		var filtersClienti=inValues["Cliente"];
		var JSONfiltersClienti=JSON.stringify(filtersClienti);
		if(filtersClienti.length==0)
		{
			Swal.fire
			({
				type: 'error',
				title: 'Errore',
				text: 'Nessun cliente selezionato'
			});
			error=true;
		}
		if(filtersClienti.length>20)
		{
			Swal.fire
			({
				type: 'error',
				title: 'Errore',
				text: 'Numero massimo di clienti (20) superato'
			});
			error=true;
		}
						
		if(inValues["CodiceArticolo"].length==inValuesAll["CodiceArticolo"].length)
		{
			var JSONfiltersCodiceArticolo=JSON.stringify(["%"]);
			var subtitleText="Articoli (Tutti)";
		}
		else
		{
			if(inValues["CodiceArticolo"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceArticolo=JSON.stringify(inValues["CodiceArticolo"]);
			if(inValues["CodiceArticolo"].length==0)
				var subtitleText="Articoli (Nessuno)";
			else
				var subtitleText="Articoli ("+inValues["CodiceArticolo"].toString()+")";
		}
		if(inValues["CodiceMadre"].length==inValuesAll["CodiceMadre"].length)
		{
			var JSONfiltersCodiceMadre=JSON.stringify(["%"]);
			var subtitleText2="Articoli madre (Tutti)";
		}
		else
		{
			if(inValues["CodiceMadre"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			
			var JSONfiltersCodiceMadre=JSON.stringify(inValues["CodiceMadre"]);
			if(inValues["CodiceMadre"].length==0)
				var subtitleText2="Articoli madre (Nessuno)";
			else
				var subtitleText2="Articoli madre ("+inValues["CodiceMadre"].toString()+")";
		}
		if(inValues["CodiceRadice"].length==inValuesAll["CodiceRadice"].length)
		{
			var JSONfiltersCodiceRadice=JSON.stringify(["%"]);
			var subtitleText3="Articoli radice (Tutti)";
		}
		else
		{
			if(inValues["CodiceRadice"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceRadice=JSON.stringify(inValues["CodiceRadice"]);
			if(inValues["CodiceRadice"].length==0)
				var subtitleText3="Articoli radice (Nessuno)";
			else
				var subtitleText3="Articoli radice ("+inValues["CodiceRadice"].toString()+")";
		}
		if(inValues["CodiceArticolo"].length==0 && inValues["CodiceMadre"].length==0 && inValues["CodiceRadice"].length==0)
		{
			Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'Nessun filtro selezionato'
				});
				error=true;
		}
		if(!error)
		{
			//document.getElementById("containerCircleSpinner").style.display="table";
			//newCircleSpinner("Caricamento in corso...");
			$.post("getChartRedditivitaClienti.php",
			{
				JSONfiltersCodiceArticolo,
				JSONfiltersCodiceMadre,
				JSONfiltersCodiceRadice,
				JSONfiltersClienti,
				raggruppamento
			},
			function(response, status)
			{
				if(status=="success")
				{
					//console.log(response);
					
					var responseArray=[];
					var responseArrayObj = JSON.parse(response);
					for (var key in responseArrayObj)
					{
						responseArray.push(responseArrayObj[key]);							
					}
					
					var clienti=[];
					var clientiObj = JSON.parse(responseArray[0]);
					for (var key in clientiObj)
					{
						clienti.push(clientiObj[key]);							
					}
					
					var dataPointsContainer=[];
					var dataPointsContainerObj = JSON.parse(responseArray[1]);
					for (var key in dataPointsContainerObj)
					{
						dataPointsContainer.push(dataPointsContainerObj[key]);							
					}
					
					//console.log(clienti);
					//console.log(dataPointsContainer);
					
					var dataPoints=[];
					
					for (var i = 0; i < dataPointsContainer.length; i++)
					{
						var cliente=clienti[i];
						var dataPointsArray=dataPointsContainer[i];
						dataPoints.push
						({
							type: "rangeArea",
							name: cliente,
							showInLegend: true,
							dataPoints:dataPointsArray
						});
					}
					//console.log(dataPoints);
					chart = new CanvasJS.Chart("containerReportRedditivita",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Redditivita clienti",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									//console.log(e.entries[i].dataSeries.name);
									var cliente=e.entries[i].dataSeries.name;
									var annomese=String(e.entries[i].dataPoint.label);
									var anno=annomese.substring(0, 4);
									var mese=annomese.substring(4, 6);//console.log(mese);
									annomese= getMese(mese)+" "+anno;
									var val=e.entries[i].dataPoint.y;
									//console.log(val);
									var yRedditivita=val[0];
									var yFatturato=val[1];
									content="Cliente: "+cliente+"<br>Mese: "+annomese+"<br>Redditivita: "+yRedditivita+" €<br>Fatturato: "+yFatturato+" €";
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12,
							valueFormatString:"####### €",
							interval: defaultInterval
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}/*,
							scaleBreaks:
							{
								customBreaks: 
								[{
									spacing: 3,
									type: "straight",
									startValue:201512,
									endValue: 201600,
									lineColor: "#2B586F",
								},{
									spacing: 3,
									type: "straight",
									startValue:201612,
									endValue: 201700,
									lineColor: "#2B586F",
								},{
									spacing: 3,
									type: "straight",
									startValue:201712,
									endValue: 201800,
									lineColor: "#2B586F",
								},{
									spacing: 3,
									type: "straight",
									startValue:201812,
									endValue: 201900,
									lineColor: "#2B586F",
								},{
									spacing: 3,
									type: "straight",
									startValue:201912,
									endValue: 202000,
									lineColor: "#2B586F",
								},
								{
									spacing: 3,
									type: "straight",
									startValue:202012,
									endValue: 202100,
									lineColor: "#2B586F",
								},
								{
									spacing: 3,
									type: "straight",
									startValue:202112,
									endValue: 202200,
									lineColor: "#2B586F",
								}]
							},*/
						},
						legend:
						{
							fontSize: 12,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
							cursor: "pointer",
							dockInsidePlotArea: false,
							itemclick: toggleDataSeries
						},
						data:dataPoints
					});
					function toggleDataSeries(e)
					{
						if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) 
						{
							e.dataSeries.visible = false;
						}
						else
						{
							e.dataSeries.visible = true;
						}
						chart.render();
					}
					chart.render();
					removeCircleSpinner();
					//document.getElementById("containerCircleSpinner").style.display="none";
				}
				else
					console.log(status);
			});
		}
		else
			removeCircleSpinner();
	}
	function getChartRedditivitaTotaleArticoli()
	{
		document.getElementById("containerReportRedditivita").innerHTML="";
		var container1=document.createElement("div");
		container1.setAttribute("id","containerRedditivitaTotale1");
		container1.setAttribute("class","innerContainerReportRedditivita");
		container1.setAttribute("style","float:left");

		var container2=document.createElement("div");
		container2.setAttribute("id","containerRedditivitaTotale2");
		container2.setAttribute("class","innerContainerReportRedditivita");
		container2.setAttribute("style","float:right");

		var container3=document.createElement("div");
		container3.setAttribute("id","containerRedditivitaTotale3");
		container3.setAttribute("class","innerContainerReportRedditivita");
		container3.setAttribute("style","float:left;");

		var container4=document.createElement("div");
		container4.setAttribute("id","containerRedditivitaTotale4");
		container4.setAttribute("class","innerContainerReportRedditivita");
		container4.setAttribute("style","float:right;");

		document.getElementById("containerReportRedditivita").appendChild(container1);
		document.getElementById("containerReportRedditivita").appendChild(container2);
		document.getElementById("containerReportRedditivita").appendChild(container3);
		document.getElementById("containerReportRedditivita").appendChild(container4);

		if(grafico!="redditivitaArticoliTotale")
		{
			uncheckAll('CodiceArticolo');
			checkTop('CodiceArticolo',10);
			uncheckAll('CodiceRadice');
			uncheckAll('CodiceMadre');
		}

		var error=false;
		grafico="redditivitaArticoliTotale";
		
		hideFilters();
		useFilter("filterPeriodo1");
		useFilter("filterPeriodo2");
		useFilter("filterCodiceArticolo");
		useFilter("filterCodiceMadre");
		useFilter("filterCodiceRadice");
		useFilter("btnExcelExport");
		useFilter("btnChartExport");
		useFilter("absoluteActionBar");
		useFilter("btnProprietaGrafico");
		/*useFilter("btnZoomPiu");
		useFilter("btnZoomRipristina");
		useFilter("btnZoomMeno");*/
		useFilter("btnColoriArticoli");
		useFilter("btnCodiceVisualizzato");
		
		getFilters();

		if(inValues["CodiceArticolo"].length==inValuesAll["CodiceArticolo"].length)
		{
			var JSONfiltersCodiceArticolo=JSON.stringify(["%"]);
			var subtitleText="Articoli (Tutti)";
		}
		else
		{
			if(inValues["CodiceArticolo"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceArticolo=JSON.stringify(inValues["CodiceArticolo"]);
			if(inValues["CodiceArticolo"].length==0)
				var subtitleText="Articoli (Nessuno)";
			else
				var subtitleText="Articoli ("+inValues["CodiceArticolo"].toString()+")";
		}
		if(inValues["CodiceMadre"].length==inValuesAll["CodiceMadre"].length)
		{
			var JSONfiltersCodiceMadre=JSON.stringify(["%"]);
			var subtitleText2="Articoli madre (Tutti)";
		}
		else
		{
			if(inValues["CodiceMadre"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			
			var JSONfiltersCodiceMadre=JSON.stringify(inValues["CodiceMadre"]);
			if(inValues["CodiceMadre"].length==0)
				var subtitleText2="Articoli madre (Nessuno)";
			else
				var subtitleText2="Articoli madre ("+inValues["CodiceMadre"].toString()+")";
		}
		if(inValues["CodiceRadice"].length==inValuesAll["CodiceRadice"].length)
		{
			var JSONfiltersCodiceRadice=JSON.stringify(["%"]);
			var subtitleText3="Articoli radice (Tutti)";
		}
		else
		{
			if(inValues["CodiceRadice"].length>100)
			{
				Swal.fire
				({
					type: 'error',
					title: 'Errore',
					text: 'E possibile selezionare tutti gli articoli, o fino a 100 articoli'
				});
				error=true;
			}
			var JSONfiltersCodiceRadice=JSON.stringify(inValues["CodiceRadice"]);
			if(inValues["CodiceRadice"].length==0)
				var subtitleText3="Articoli radice (Nessuno)";
			else
				var subtitleText3="Articoli radice ("+inValues["CodiceRadice"].toString()+")";
		}
		if(inValues["CodiceArticolo"].length==0 && inValues["CodiceMadre"].length==0 && inValues["CodiceRadice"].length==0)
		{
			Swal.fire
			({
				type: 'error',
				title: 'Errore',
				text: 'Nessun filtro selezionato'
			});
			error=true;
		}
		var periodo1Inizio=document.getElementById("selectPeriodo1Inizio").value;
		var periodo1Fine=document.getElementById("selectPeriodo1Fine").value;
		var periodo2Inizio=document.getElementById("selectPeriodo2Inizio").value;
		var periodo2Fine=document.getElementById("selectPeriodo2Fine").value;
		
		if(periodo1Inizio>periodo1Fine || periodo2Inizio>periodo2Fine)
		{
			Swal.fire
			({
				type: 'error',
				title: 'Errore',
				text: 'Periodi inseriti non validi'
			});
			error=true;
		}
		else
		{
			var periodo1InizioAnno=periodo1Inizio.substring(0, 4);
			var periodo1InizioMese=getMese(periodo1Inizio.substring(4, 6));
			var periodo1FineAnno=periodo1Fine.substring(0, 4);
			var periodo1FineMese=getMese(periodo1Fine.substring(4, 6));
			var subtitleTextPeriodo1=periodo1InizioMese+" "+periodo1InizioAnno+" - "+periodo1FineMese+" "+periodo1FineAnno;
			
			var periodo2InizioAnno=periodo2Inizio.substring(0, 4);
			var periodo2InizioMese=getMese(periodo2Inizio.substring(4, 6));
			var periodo2FineAnno=periodo2Fine.substring(0, 4);
			var periodo2FineMese=getMese(periodo2Fine.substring(4, 6));
			var subtitleTextPeriodo2=periodo2InizioMese+" "+periodo2InizioAnno+" - "+periodo2FineMese+" "+periodo2FineAnno;
		}

		try
		{
			var indexLabel=document.getElementById("propertieIndexLabel").value;
		}
		catch(e)
		{
			var indexLabel="{label} : {y} €";
		}
		
		var codiceVisualizzato=document.getElementById("btnCodiceVisualizzato").value;
		
		if(!error)
		{
			//document.getElementById("containerCircleSpinner").style.display="table";
			//newCircleSpinner("Caricamento in corso...");
			$.post("getChartRedditivitaArticoliTotale.php",
			{
				periodo1Inizio,
				periodo1Fine,
				periodo2Inizio,
				periodo2Fine,
				JSONfiltersCodiceArticolo,
				JSONfiltersCodiceMadre,
				JSONfiltersCodiceRadice,
				codiceVisualizzato
			},
			function(response, status)
			{
				if(status=="success")
				{
					//console.log(response);
					var responseArray=[];
					var responseArrayObj = JSON.parse(response);
					for (var key in responseArrayObj)
					{
						responseArray.push(responseArrayObj[key]);							
					}
					
					var dataPointsPeriodo1=[];
					var dataPointsPeriodo1Obj = JSON.parse(responseArray[0]);
					for (var key in dataPointsPeriodo1Obj)
					{
						dataPointsPeriodo1.push(dataPointsPeriodo1Obj[key]);							
					}
					
					var dataPointsPeriodo2=[];
					var dataPointsPeriodo2Obj = JSON.parse(responseArray[1]);
					for (var key in dataPointsPeriodo2Obj)
					{
						dataPointsPeriodo2.push(dataPointsPeriodo2Obj[key]);							
					}
					var dataPointsFatturato1=[];
					var dataPointsFatturato1Obj = JSON.parse(responseArray[2]);
					for (var key in dataPointsFatturato1Obj)
					{
						dataPointsFatturato1.push(dataPointsFatturato1Obj[key]);							
					}
					
					var dataPointsFatturato2=[];
					var dataPointsFatturato2Obj = JSON.parse(responseArray[3]);
					for (var key in dataPointsFatturato2Obj)
					{
						dataPointsFatturato2.push(dataPointsFatturato2Obj[key]);							
					}
					/*console.log(dataPointsFatturato1Obj);
					console.log(dataPointsFatturato2Obj);*/

					chart = new CanvasJS.Chart("containerRedditivitaTotale1",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Redditivita totale articoli periodo 1",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									//console.log(e.entries);
									var val=e.entries[i].dataPoint.y;
									content="Articolo: "+e.entries[i].dataPoint.label+"<br>Valore: "+val+" €";
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif",
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: "Periodo ("+subtitleTextPeriodo1+")",
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}
						},
						data: 
						[
							{
								type: "pie",
								dataPoints:dataPointsPeriodo1,
								indexLabel
							}
						]
					});
					chart.render();
					chart1 = new CanvasJS.Chart("containerRedditivitaTotale2",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Redditivita totale articoli periodo 2",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									//console.log(e.entries);
									var val=e.entries[i].dataPoint.y;
									content="Articolo: "+e.entries[i].dataPoint.label+"<br>Valore: "+val+" €";
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: "Periodo ("+subtitleTextPeriodo2+")",
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}
						},
						data: 
						[
							{
								type: "pie",
								dataPoints:dataPointsPeriodo2,
								indexLabel
							}
						]
					});
					chart1.render();
					chart3 = new CanvasJS.Chart("containerRedditivitaTotale3",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Fatturato totale articoli periodo 1",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									//console.log(e.entries);
									var val=e.entries[i].dataPoint.y;
									content="Articolo: "+e.entries[i].dataPoint.label+"<br>Valore: "+val+" €";
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: "Periodo ("+subtitleTextPeriodo1+")",
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}
						},
						data: 
						[
							{
								type: "pie",
								dataPoints:dataPointsFatturato1,
								indexLabel
							}
						]
					});
					chart3.render();
					chart4 = new CanvasJS.Chart("containerRedditivitaTotale4",
					{
						theme: "light2",
						animationEnabled: true,
						zoomEnabled: true,
						zoomType: "x",
						title:
						{
							text: "Fatturato totale articoli periodo 2",
							fontSize: 18,
							fontWeight:"bold",
							fontFamily:"'Montserrat',sans-serif",
						},
						toolTip: {
							contentFormatter: function (e)
							{
								var content = " ";
								for (var i = 0; i < e.entries.length; i++)
								{
									//console.log(e.entries);
									var val=e.entries[i].dataPoint.y;
									content="Articolo: "+e.entries[i].dataPoint.label+"<br>Valore: "+val+" €";
								}
								return content;
							}
						},
						subtitles:
						[
							{
								padding:
								{
									top:5,
								},
								text: subtitleText,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: subtitleText2,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								text: "Periodo ("+subtitleTextPeriodo2+")",
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							},
							{
								padding:
								{
									bottom:5,
								},
								text: subtitleText3,
								fontSize: 12,
								fontWeight:"normal",
								fontFamily:"'Montserrat',sans-serif"
							}
						],
						axisY:
						{
							labelFontSize: 12
						},
						axisX:
						{
							labelFontSize: 12,
							labelFormatter: function(e)
							{
								var annomese=String(e.label);
								var anno=annomese.substring(0, 4);
								var mese=annomese.substring(4, 6);
								return getMese(mese)+" "+anno;
							}
						},
						data: 
						[
							{
								type: "pie",
								dataPoints:dataPointsFatturato2,
								indexLabel
							}
						]
					});
					chart4.render();
					removeCircleSpinner();
					//document.getElementById("containerCircleSpinner").style.display="none";
				}
				else
					console.log(status);
			});
		}
		else
			removeCircleSpinner();
	}
	/*function apriCodiceVisualizzato()
	{
		document.getElementsByClassName("canvasjs-chart-container")[1].style.zIndex = '-1';
		document.getElementById("btnCodiceVisualizzato").style.height="120px";
	}*/
	function getMese(mese)
	{
		switch(mese)
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
	function searcFilters(filter)
	{
		var tipoRicerca;
		var radios = document.getElementsByName('radioTipoRicerca'+filter);

		for (var i = 0, length = radios.length; i < length; i++)
		{
			if (radios[i].checked)
			{
				// do whatever you want with the checked radio
				tipoRicerca=radios[i].value;

				// only one radio can be logically checked, don't check the rest
				break;
			}
		}
		
		var operatoreLogico=document.getElementById("selectAndOr"+filter).value;
		
		var all = document.getElementsByClassName("absoluteActionBarFilterMenuFilterCheckboxContainer"+filter);
		for (var i = 0; i < all.length; i++) 
		{
			all[i].style.display='';
		}
		var value = $("#searcFilters"+filter).val().toLowerCase();
		var value2= $("#searcFilters"+filter+"2").val().toLowerCase();
		$("#absoluteActionBarFilterMenuFilterContainer"+filter+" *").filter(function() 
		{
			if($(this).prop("tagName")=="LABEL")
			{
				var cellValue=$(this).text().toLowerCase();
				
				var valueLength=value.length;
				
				if(tipoRicerca=="inizio")
				{
					if(operatoreLogico=="AND")
					{
						if(cellValue.substring(0, valueLength)==value && cellValue.substring(0, valueLength)==value2)
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}
					}
					else
					{
						if(cellValue.substring(0, valueLength)==value || cellValue.substring(0, valueLength)==value2)
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}
					}
					/*if(cellValue.substring(0, valueLength)==value)
					{
						$(this).show();
					}
					else
					{
						$(this).hide();
					}*/
				}
				if(tipoRicerca=="indexOf")
				{
					//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
					if(operatoreLogico=="AND")
					{
						if($(this).text().toLowerCase().indexOf(value) > -1 && $(this).text().toLowerCase().indexOf(value2) > -1)
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}
					}
					else
					{
						if($(this).text().toLowerCase().indexOf(value) > -1 || $(this).text().toLowerCase().indexOf(value2) > -1)
						{
							$(this).show();
						}
						else
						{
							$(this).hide();
						}
					}
				}
				//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			}
		});
		var all = document.getElementsByClassName("absoluteActionBarFilterMenuFilterCheckboxContainer"+filter);
		for (var i = 0; i < all.length; i++) 
		{
			var checkbox=all[i].childNodes[1];
			if(all[i].style.display=='none')
			{
				checkbox.checked=false;
				checkCheckboxAll(filter);
			}
			else
			{
				checkbox.checked=true;
				checkCheckboxAll(filter);
			}
		}
	}
	function checkCheckboxAll(filter)
	{
		//console.log(filter);
		var checked=true;
		var all=document.getElementsByClassName("absoluteActionBarFilterMenuCheckbox"+filter);
		for (var i = 0; i < all.length; i++) 
		{
			if(all[i].checked==false)
			{
				checked=false;
				break;
			}
		}
		document.getElementById("absoluteActionBarFilterMenuFilterCheckboxAll"+filter).checked=checked;
	}
	function checkCheckboxes(filter)
	{
		if(document.getElementById("absoluteActionBarFilterMenuFilterCheckboxAll"+filter).checked==true)
			var checked=true;
		else
			var checked=false;
		var all=document.getElementsByClassName("absoluteActionBarFilterMenuCheckbox"+filter);
		for (var i = 0; i < all.length; i++) 
		{
			all[i].checked=checked;
		}
	}
	function getFilters()
	{
		filters.forEach(function(filter)
		{
			inValues[filter]="";
			inValuesAll[filter]="";
			var values=[];
			var valuesAll=[];
			var all = document.getElementsByClassName("absoluteActionBarFilterMenuCheckbox"+filter);
			for (var i = 0; i < all.length; i++) 
			{
				valuesAll.push(all[i].getAttribute("fieldValue"));
				if(all[i].checked)
					values.push(all[i].getAttribute("fieldValue").trim());
			}
			inValuesAll[filter]=valuesAll;
			inValues[filter]=values;
		});
	}
	function openContextMenu(event,filter)
	{
		var x = event.clientX;     // Get the horizontal coordinate
		//var y = event.clientY;     // Get the vertical coordinate
		var element=document.getElementById("absoluteActionBarFilterMenu"+filter);
		var y = (element.offsetTop - element.scrollTop + element.clientTop)+35;
		closeContextMenu();
		//console.log(event.srcElement);
		//closeContextMenu();
		/*if(event.srcElement.className != "absoluteActionBarFilterMenuCancelButton")
			document.getElementById("absoluteActionBarFilterMenu"+filter).style.display="inline-block";*/
		//console.log(event.srcElement.className);
		if(event.srcElement.className == "absoluteActionBarButton defaultHiddenElements")
		{
			document.getElementById("absoluteActionBarFilterMenu"+filter).style.left=x;
			document.getElementById("absoluteActionBarFilterMenu"+filter).style.top=y;
			document.getElementById("absoluteActionBarFilterMenu"+filter).style.display="inline-block";
		}
	}
	function closeContextMenu()
	{
		//console.log("chiudi: "+closeContextMenu.caller);
		var all = document.getElementsByClassName("absoluteActionBarFilterMenu");
		for (var i = 0; i < all.length; i++) 
		{
			all[i].style.display='none';
		}
	}
	function resetFilters()
	{
		closeContextMenu();
		var all2 = document.getElementsByClassName("editableTableSearcFilters");
		for (var i = 0; i < all2.length; i++) 
		{
			all2[i].value="";
			all2[i].click();
		}
		filters.forEach(function(filter)
		{
			var all = document.getElementsByClassName("absoluteActionBarFilterMenuCheckbox"+filter);
			for (var i = 0; i < all.length; i++) 
			{
				all[i].checked=true;
			}
			checkCheckboxAll(filter);
		});
	}
	function confermaFiltro()
	{
		closeContextMenu();
		newCircleSpinner("Caricamento in corso...");
		if(grafico=="redditivitaArticoli")
			getChartRedditivitaArticoli();
		if(grafico=="redditivitaAgenti")
			getChartRedditivitaAgenti();
		if(grafico=="redditivitaArticoliTotale")
			getChartRedditivitaTotaleArticoli();
		if(grafico=="redditivitaClienti")
			getChartRedditivitaClienti();
	}
	function esportaGrafico()
	{
		chart.exportChart({format: "jpg"});
		if(chart1!=null)
			chart1.exportChart({format: "jpg"});
		if(chart3!=null)
		chart3.exportChart({format: "jpg"});
		if(chart4!=null)
		chart4.exportChart({format: "jpg"});
	}
	function esportaExcel()
	{
		downloadCSV({ filename: grafico+".csv", chart: chart });
		if(chart1!=null)
			downloadCSV({ filename: grafico+".csv", chart: chart1 });
		if(chart3!=null)
			downloadCSV({ filename: grafico+".csv", chart: chart3 });
		if(chart4!=null)
			downloadCSV({ filename: grafico+".csv", chart: chart4 });
	}
	function convertChartDataToCSV(args) {  
		var result, ctr, keys, columnDelimiter, lineDelimiter, data;

		data = args.data || null;
		if (data == null || !data.length) {
		return null;
		}

		columnDelimiter = args.columnDelimiter || ',';
		lineDelimiter = args.lineDelimiter || '\n';

		keys = Object.keys(data[0]);

		result = '';
		result += keys.join(columnDelimiter);
		result += lineDelimiter;

		data.forEach(function(item) {
		ctr = 0;
		keys.forEach(function(key) {
			if (ctr > 0) result += columnDelimiter;
			result += item[key];
			ctr++;
		});
		result += lineDelimiter;
		});
		return result;
	}

	function downloadCSV(args) {
		var data, filename, link;
		var csv = "";
		for(var i = 0; i < args.chart.options.data.length; i++){
		csv += convertChartDataToCSV({
			data: args.chart.options.data[i].dataPoints
		});
		}
		if (csv == null) return;

		filename = args.filename || 'chart-data.csv';

		if (!csv.match(/^data:text\/csv/i)) {
		csv = 'data:text/csv;charset=utf-8,' + csv;
		}
		
		data = encodeURI(csv);
		link = document.createElement('a');
		link.setAttribute('href', data);
		link.setAttribute('download', filename);
		document.body.appendChild(link); // Required for FF
		link.click(); 
		document.body.removeChild(link);   
	}
	function selectYearFilter(periodo,value)
	{
		if(value!="annulla")
		{
			document.getElementById("select"+periodo+"Inizio").value=value+"01";
			var mesiFine=[];
			$("#select"+periodo+"Fine option").each(function()
			{
				if($(this).val().substring(0, 4)==value)
					mesiFine.push($(this).val().substring(4, 6));
			});
			//console.log(mesiFine);
			var ultimoMese=Math.max.apply(Math,mesiFine).toString();
			if(ultimoMese.length==1)
				ultimoMese="0"+ultimoMese;
			//console.log(ultimoMese);
			document.getElementById("select"+periodo+"Fine").value=value+ultimoMese;
		}
	}
	function apriProprietaGrafico()
	{
		var table=document.createElement("table");
		table.setAttribute("class","material-design-table-dark");

		//thead
		var thead = table.createTHead();

		var row = thead.insertRow(0); 

		var cell1= document.createElement("th");
		var cell2 = document.createElement("th");

		cell1.innerHTML = "Proprietà";
		cell1.setAttribute("class","proprieta-grafico-table-border-right");
		cell2.innerHTML = "Valore";

		row.appendChild(cell1);
		row.appendChild(cell2);

		//tbody
		var tbody = table.createTBody();

		//axisYPropertiesInverval-------------------------------------------------------------
		if(grafico=="redditivitaArticoli" || grafico=="redditivitaAgenti" || grafico=="redditivitaClienti")
		{	
			var row = tbody.insertRow(-1);

			var cell1 = row.insertCell(0);
			cell1.setAttribute("class","proprieta-grafico-table-border-right");
			var cell2 = row.insertCell(1);
			cell2.setAttribute("style","padding-top:0px;padding-bottom:0px");

			cell1.innerHTML = "Intervallo scala asse Y";
			
			var axisYPropertiesInvervalActualValue=chart.options.axisY.interval;
			var inputScalaAsseY=document.createElement("input");
			inputScalaAsseY.setAttribute("type","number");
			inputScalaAsseY.setAttribute("value",axisYPropertiesInvervalActualValue);
			inputScalaAsseY.setAttribute("id","axisYPropertiesInverval");
			inputScalaAsseY.setAttribute("step","1000");
			cell2.appendChild(inputScalaAsseY);

			//<i class="fal fa-euro-sign"></i>
			var simboloInputScalaAsseY=document.createElement("i");
			simboloInputScalaAsseY.setAttribute("class","fal fa-euro-sign");
			simboloInputScalaAsseY.setAttribute("style","margin-left:10px;float:left;display: inline-block;margin-top:5px");
			cell2.appendChild(simboloInputScalaAsseY);
		}
		//propertieIndexLabel-------------------------------------------------------------
		if(grafico=="redditivitaArticoliTotale")
		{	
			//console.log(chart.options.data[0].indexLabel);
			var row = tbody.insertRow(-1);

			var cell1 = row.insertCell(0);
			cell1.setAttribute("class","proprieta-grafico-table-border-right");
			var cell2 = row.insertCell(1);
			cell2.setAttribute("style","padding-top:0px;padding-bottom:0px");

			cell1.innerHTML = "Mostra nella legenda";
			
			var selectIndexLabel=document.createElement("select");
			selectIndexLabel.setAttribute("id","propertieIndexLabel");
			selectIndexLabel.setAttribute("style","width:200px");
			var optionSelectIndexLabel=document.createElement("option");
			optionSelectIndexLabel.setAttribute("value","{label} : {y} €");
			optionSelectIndexLabel.innerHTML="Sia l' articolo che il valore";
			if(chart.options.data[0].indexLabel=="{label} : {y} €")
				optionSelectIndexLabel.setAttribute("selected","selected");
			selectIndexLabel.appendChild(optionSelectIndexLabel);
			var optionSelectIndexLabel=document.createElement("option");
			optionSelectIndexLabel.setAttribute("value","{label}");
			optionSelectIndexLabel.innerHTML="Solo l' articolo";
			if(chart.options.data[0].indexLabel=="{label}")
				optionSelectIndexLabel.setAttribute("selected","selected");
			selectIndexLabel.appendChild(optionSelectIndexLabel);
			var optionSelectIndexLabel=document.createElement("option");
			optionSelectIndexLabel.setAttribute("value","{y} €");
			optionSelectIndexLabel.innerHTML="Solo il valore";
			if(chart.options.data[0].indexLabel=="{y} €")
				optionSelectIndexLabel.setAttribute("selected","selected");
			selectIndexLabel.appendChild(optionSelectIndexLabel);
			cell2.appendChild(selectIndexLabel);
		}
		//------------------------------------------------------------------------------------
		Swal.fire
		({
			title: 'Proprietà grafico',
			background: '#363640',
			width:"800px",
			html: table.outerHTML,
			showCancelButton : true,
			cancelButtonText : "Annulla",
			confirmButtonText: "Conferma",
			onOpen : function()
					{
						document.getElementsByClassName("swal2-title")[0].style.color="#ddd";
						document.getElementsByClassName("swal2-title")[0].style.fontSize="14px";

						$('.swal2-confirm').first().css({
															'border': 'none',
															'background-color': '#3D3D47',
															'box-shadow': '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)',
															'color': '#ddd',
															'font-size': '13px',
															'margin-left': '10px',
															'margin-right': '10px',
															'border-radius': '3px'
														});

						$('.swal2-cancel').first().css({
															'border': 'none',
															'background-color': '#ddd',
															'box-shadow': '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)',
															'color': '#3D3D47',
															'font-size': '13px',
															'margin-left': '10px',
															'margin-right': '10px',
															'border-radius': '3px'
														});
					}
		}).then((result) => 
		{
			if (result.value)
			{
				swal.close();
				if(grafico=="redditivitaArticoliTotale")
				{
					//getChartRedditivitaTotaleArticoli();
					chart.options.data[0].indexLabel=document.getElementById("propertieIndexLabel").value;
					chart.render();
					chart1.options.data[0].indexLabel=document.getElementById("propertieIndexLabel").value;
					chart1.render();
					chart3.options.data[0].indexLabel=document.getElementById("propertieIndexLabel").value;
					chart3.render();
					chart4.options.data[0].indexLabel=document.getElementById("propertieIndexLabel").value;
					chart4.render();
				}
				else
				{
					var axisYPropertiesInverval=document.getElementById("axisYPropertiesInverval").value;
					axisYPropertiesInverval=parseInt(axisYPropertiesInverval);
					if(!isNaN(axisYPropertiesInverval) && axisYPropertiesInverval>0)
					{
						chart.options.axisY.interval=axisYPropertiesInverval;
						chart.render();
					}
					else
					{
						chart.options.axisY.interval=axisYPropertiesInvervalActualValue;
						chart.render();
					}
				}
			}
			else
				swal.close();
		});
	}
	function getClienti()
	{
		newMouseSpinner(event);

		var clientiChecked=inValues["Cliente"];

		document.getElementById("absoluteActionBarFilterMenuFilterContainerCliente").innerHTML="";

		var label=document.createElement("label");
		label.setAttribute("class","absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCliente");
		label.innerHTML="Tutti";

		var checkbox=document.createElement("input");
		checkbox.setAttribute("type","checkbox");
		checkbox.setAttribute("onclick","checkCheckboxes('Cliente')");
		checkbox.setAttribute("id","absoluteActionBarFilterMenuFilterCheckboxAllCliente");
		//checkbox.setAttribute("checked","checked");

		var span=document.createElement("span");
		span.setAttribute("class","absoluteActionBarFilterMenuFilterCheckboxCheckmark");

		label.appendChild(checkbox);
		label.appendChild(span);

		document.getElementById("absoluteActionBarFilterMenuFilterContainerCliente").appendChild(label);

		var orderBy
		var radios = document.getElementsByName('radioTipoOrdinamentoCliente');

		for (var i = 0, length = radios.length; i < length; i++)
		{
			if (radios[i].checked)
			{
				// do whatever you want with the checked radio
				orderBy=radios[i].value;

				// only one radio can be logically checked, don't check the rest
				break;
			}
		}				

		$.post("getClienti.php",
		{
			orderBy
		},
		function(response, status)
		{
			if(status=="success")
			{
				if(response.indexOf("error")>-1)
				{
					Swal.fire
					({
						type: 'error',
						title: 'Errore',
						text: "Se il problema persiste contatta l' amministratore"
					});
				}
				else
				{
					var clienti=[];
					var clientiObj = JSON.parse(response);
					for (var key in clientiObj)
					{
						clienti.push(clientiObj[key]);
					}
					let i=0;
					clienti.forEach(function(cliente)
					{
						var label=document.createElement("label");
						label.setAttribute("class","absoluteActionBarFilterMenuFilterCheckboxContainer absoluteActionBarFilterMenuFilterCheckboxContainerCliente");
						label.innerHTML=cliente;

						var checkbox=document.createElement("input");
						checkbox.setAttribute("type","checkbox");
						checkbox.setAttribute("onchange","checkCheckboxAll('Cliente')");
						checkbox.setAttribute("id","checkboxFilterMenuCliente"+cliente);
						checkbox.setAttribute("fieldValue",cliente);
						checkbox.setAttribute("class","absoluteActionBarFilterMenuCheckboxCliente");
						if(clientiChecked.includes(cliente))
							checkbox.setAttribute("checked","checked");

						var span=document.createElement("span");
						span.setAttribute("class","absoluteActionBarFilterMenuFilterCheckboxCheckmark");

						label.appendChild(checkbox);
						label.appendChild(span);

						document.getElementById("absoluteActionBarFilterMenuFilterContainerCliente").appendChild(label);

						i++;
					});
					searcFilters('Cliente');
				}
				removeMouseSpinner();
			}
			else
				console.log(status);
		});
	}
	function getColoriArticoli()
	{
		newMouseSpinner(event);

		var table=document.createElement("table");
		table.setAttribute("class","material-design-table-dark");
		table.setAttribute("id","tableArticoliColori");

		//thead
		var thead = table.createTHead();

		//tbody
		var tbody = table.createTBody();

		var row = thead.insertRow(0); 

		var cell1= document.createElement("th");
		var cell2 = document.createElement("th");
		var cell3 = document.createElement("th");

		cell1.innerHTML = "Codice";
		cell1.setAttribute("class","proprieta-grafico-table-border-right");
		cell2.innerHTML = "Colore";

		var plus=document.createElement("i");
		plus.setAttribute("class","far fa-plus");
		plus.setAttribute("style","cursor:pointer");
		plus.setAttribute("title","Aggiungi");
		plus.setAttribute("onclick","aggiungiRigaTabellaColori()");
		cell3.appendChild(plus);

		row.appendChild(cell1);
		row.appendChild(cell2);
		row.appendChild(cell3);

		$.post("getColoriArticoli.php",
		function(response, status)
		{
			if(status=="success")
			{
				if(response.indexOf("error")>-1)
				{
					Swal.fire
					({
						type: 'error',
						title: 'Errore',
						text: "Se il problema persiste contatta l' amministratore"
					});
				}
				else
				{
					var codici_colori = JSON.parse(response);
					let i=0;
					codici_colori.forEach(function(itemRow)
					{
						var id_codice_colore=itemRow['id_codice_colore'];
						var codice=itemRow['codice'];
						var colore=itemRow['colore'];

						var row = tbody.insertRow(-1);
						row.setAttribute("class","rowToUpdate");
						row.setAttribute("id_codice_colore",id_codice_colore);

						var cell1 = row.insertCell(0);
						cell1.setAttribute("class","proprieta-grafico-table-border-right");
						cell1.setAttribute("style","padding-top:0px;padding-bottom:0px");
						var inputCodice=document.createElement("input");
						inputCodice.setAttribute("type","text");
						inputCodice.setAttribute("style","width:150px;");
						inputCodice.setAttribute("value",codice);
						cell1.appendChild(inputCodice);

						var cell2 = row.insertCell(1);
						cell2.setAttribute("style","padding-top:0px;padding-bottom:0px");
						var jsColorInput=document.createElement("input");
						jsColorInput.setAttribute("type","color");
						jsColorInput.setAttribute("pattern","^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$");
						jsColorInput.setAttribute("value",colore);
						cell2.appendChild(jsColorInput);
					
						var cell3 = row.insertCell(2);
						var trash=document.createElement("i");
						trash.setAttribute("class","fal fa-trash");
						trash.setAttribute("style","cursor:pointer");
						trash.setAttribute("title","Elimina");
						trash.setAttribute("onclick","eliminaRigaColoriArticoli("+id_codice_colore+",this)");
						cell3.appendChild(trash);
						i++;
					});
				}
				openColoriArticoliPopup(table);
			}
			else
				console.log(status);
		});
	}
	function eliminaRigaColoriArticoli(id_codice_colore,cell)
	{
		$.post("eliminaRigaColoriArticoli.php",
		{
			id_codice_colore
		},
		function(response, status)
		{
			if(status=="success")
			{
				if(response.indexOf("error")>-1)
				{
					Swal.fire
					({
						type: 'error',
						title: 'Errore',
						text: "Se il problema persiste contatta l' amministratore"
					});
				}
				else
				{
					cell.parentElement.parentElement.remove();
				}
			}
			else
				console.log(status);
		});
	}
	function aggiungiRigaTabellaColori()
	{
		var tbody = document.getElementById("tableArticoliColori").tBodies[0];
		var row = tbody.insertRow(0);
		row.setAttribute("class","rowToInsert");

		var cell1 = row.insertCell(0);
		cell1.setAttribute("class","proprieta-grafico-table-border-right");
		cell1.setAttribute("style","padding-top:0px;padding-bottom:0px");
		var inputCodice=document.createElement("input");
		inputCodice.setAttribute("type","text");
		inputCodice.setAttribute("style","width:150px;");
		cell1.appendChild(inputCodice);

		var cell2 = row.insertCell(1);
		cell2.setAttribute("style","padding-top:0px;padding-bottom:0px");
		var jsColorInput=document.createElement("input");
		jsColorInput.setAttribute("type","color");
		jsColorInput.setAttribute("pattern","^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$");
		cell2.appendChild(jsColorInput);
	
		var cell3 = row.insertCell(2);
		var trash=document.createElement("i");
		trash.setAttribute("class","fal fa-trash");
		trash.setAttribute("style","cursor:pointer");
		trash.setAttribute("title","Elimina");
		trash.setAttribute("onclick","this.parentElement.parentElement.remove()");
		cell3.appendChild(trash);
	}
	function openColoriArticoliPopup(table)
	{
		Swal.fire
		({
			title: 'Colori articoli',
			background: '#363640',
			width:"800px",
			html: table.outerHTML,
			showCancelButton : true,
			cancelButtonText : "Annulla",
			confirmButtonText: "Conferma",
			onOpen : function()
					{
						document.getElementsByClassName("swal2-title")[0].style.color="#ddd";
						document.getElementsByClassName("swal2-title")[0].style.fontSize="14px";

						$('.swal2-confirm').first().css({
															'border': 'none',
															'background-color': '#3D3D47',
															'box-shadow': '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)',
															'color': '#ddd',
															'font-size': '13px',
															'margin-left': '10px',
															'margin-right': '10px',
															'border-radius': '3px'
														});

						$('.swal2-cancel').first().css({
															'border': 'none',
															'background-color': '#ddd',
															'box-shadow': '0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23)',
															'color': '#3D3D47',
															'font-size': '13px',
															'margin-left': '10px',
															'margin-right': '10px',
															'border-radius': '3px'
														});
						removeMouseSpinner();
					}
		}).then((result) => 
		{
			if (result.value)
			{
				newCircleSpinner("Aggiornamento colori in corso...");

				swal.close();
				var rowsToInsert=[];
				var rowsToUpdate=[];

				$('.rowToInsert').each(function(i, obj)
				{
					var codice=obj.cells[0].firstChild.value;
					var colore=obj.cells[1].firstChild.value;
					if(codice!='' && colore!='')
					{
						var row = {codice,colore};
						rowsToInsert.push(row);
					}
				});

				$('.rowToUpdate').each(function(i, obj)
				{
					var id_codice_colore=obj.getAttribute("id_codice_colore");
					var codice=obj.cells[0].firstChild.value;
					var colore=obj.cells[1].firstChild.value;
					if(codice!='' && colore!='')
					{
						var row = {id_codice_colore,codice,colore};
						rowsToUpdate.push(row);
					}
				});

				var JSONrowsToInsert=JSON.stringify(rowsToInsert);
				var JSONrowsToUpdate=JSON.stringify(rowsToUpdate);

				$.post("insertUpdateColoriArticoli.php",
				{
					JSONrowsToInsert,
					JSONrowsToUpdate
				},
				function(response, status)
				{
					if(status=="success")
					{
						removeCircleSpinner();
						if(response.indexOf("ok")>-1)
						{
							Swal.fire
							({
								type: 'success',
								title: 'Colori aggiornati'
							}).then((result) => 
							{
								newCircleSpinner('Caricamento in corso...');getChartRedditivitaTotaleArticoli();
							});
						}
						else
						{
							Swal.fire
							({
								type: 'error',
								title: 'Errore',
								text: "Se il problema persiste contatta l' amministratore"
							});
						}
					}
					else
						console.log(status);
				});
			}
			else
				swal.close();
		});
	}
	async function checkInto()
    {
		/*//da cancellare qunado oonline
		setCookie("into1ReportRedditivita","false");
		//-------------------------------------------------------
		if(await getCookie("into1ReportRedditivita")!="true")
		{
			Swal.fire
			({
				type: 'question',
				title: 'Tutorial',
				text: "E' disponibile un tutorial delle funzionalità",
				confirmButtonText: "Inizia tutorial",
				showCancelButton:true,
				cancelButtonText: "Salta tutorial"
			}).then((result) => 
			{
				if (result.value)
				{
					//introJs().setOption('showProgress', true).oncomplete(function() {setCookie("into1GanttStatoOrdini","true")}).onexit(function() {setCookie("into1GanttStatoOrdini","true")}).start();
				}
				else
				{
					console.log("start");
					introJs(".intro-tutorial-location").start();
					//introJs(".intro-tutorial-location").start();
					//setCookie("into1ReportRedditivita","true")
				}					
			});
		}*/
    }