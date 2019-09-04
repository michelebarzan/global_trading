<?php

	require 'php_libraries/php_office/vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\IOFactory;
	$inputFileName = 'bi-anaage.csv';
	$spreadsheet = IOFactory::load($inputFileName);
	$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
	//var_dump($sheetData);
	
	/*foreach ($sheetData as $key => $value)
	{
		foreach($value as $item)
		{
			echo $item;
		}
		echo "<br>";
	}*/
	
	echo "<table style='border-collapse:collapse;'>";
	foreach ($sheetData as $key => $value)
	{
		echo "<tr>";
		foreach($value as $item)
		{
			echo "<td style='border:1px solid gray'>".$item."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
?>