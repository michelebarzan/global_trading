<?php
	
	include "Session.php";
	include "connessione.php";
    
    echo $_POST["fileNameResponse"]."|";
    
    $target_dir="import_files\\";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    define ('SITE_ROOT', realpath(dirname(__FILE__)));

    if (move_uploaded_file($_FILES["file"]["tmp_name"], SITE_ROOT.'\\'.$target_file)) 
    {
        echo "ok";
    } 
    else 
    {
        echo "error";
    }

?>