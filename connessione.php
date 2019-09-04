<?php
$serverName = '10.0.1.4';
//$serverName = 'sql.servizioglobale.it';
//$serverName = 'web.azure.servizioglobale.it';
$connectionInfo=array("Database"=>"global_trading2", "UID"=>"sa", "PWD"=>"Serglo123");
$conn = sqlsrv_connect($serverName,$connectionInfo);
?>