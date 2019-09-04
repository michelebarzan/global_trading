<?php
	
	include "Session.php";
	include "connessione.php";
    
    require_once "php_libraries/excel_reader/PHPExcel.php";

    set_time_limit(3000);
    ini_set('memory_limit', '-1');

    /*$tables=
    [
        'bi_anaage',
        'bi_anaart',
        'bi_anaclif',
        'bi_anapag',
        'bi_anazone',
        'bi_fatart'
    ];*/
    $tables=
    [
        'bi_anaage',
        'bi_anaart',
        'bi_anaclif',
        'bi_anapag',
        'bi_anazone'
    ];

    $q2="DELETE FROM log_importa_dati WHERE sessionId='".session_id()."'";
    $r2=sqlsrv_query($conn,$q2);
    if($r2==FALSE)
    {
        //die("log_error");
        echo "log_error";
    }
    
    foreach ($tables as $table)
    {
        $file="import_files/".$table.".csv";
        $excelReader = PHPExcel_IOFactory::createReaderForFile($file);
        $excelObj = $excelReader->load($file);
        $worksheet = $excelObj->getSheet(0);
        $lastRow = $worksheet->getHighestRow();

        $rows=[];
        $colNum=getColumnsNum($conn,$table);

        $rowCount=0;
        for ($excelRow = 1; $excelRow <= $lastRow; $excelRow++)
        {
            $row=[];
            if($rowCount==0)
            {
                $header=explode(";",str_replace(" ","",$worksheet->getCell('A'.$excelRow)->getValue()));
                array_pop($header);
                foreach($header as $key=>$value)
                {
                    $header[$key]=str_replace("'"," ",$value);
                }
            }
            else
            {
                $row=explode(";",str_replace(" ","",$worksheet->getCell('A'.$excelRow)->getValue()));
                $lDiff=sizeof($row)-sizeof($header);
                if($lDiff>0)
                {
                    for ($x = 0; $x < $lDiff; $x++)
                    {
                        array_pop($row);
                    }
                }
                else
                {
                    $lDiff=sizeof($header)-sizeof($row);
                    for ($x = 0; $x < $lDiff; $x++)
                    {
                        array_push($row,"");
                    }
                }
                foreach($row as $key=>$value)
                {
                    $row[$key]=str_replace("'","",$value);
                }
                array_push($rows,$row);
            }            
            $rowCount++;
        }
        insertData($conn,$table,$header,$rows);
        /*if($table=="bi_fatart")
        {
            echo implode("|",$header)."\n-----------------------------------------------------------------------------\n";
            foreach ($rows as $r)
            {
                echo implode("|",$r);
                echo "\n-----------------------------------------------------------------------------\n";
            } 
        }*/
    }
    //echo "fine";
    $q="SELECT * FROM log_importa_dati WHERE sessionId='".session_id()."' AND type='error'";
    $r=sqlsrv_query($conn,$q);
    if($r==FALSE)
    {
        //die("log_error");
        echo "log_error";
    }
    else
    {
        $rows = sqlsrv_has_rows( $r );
        if ($rows === true)
        {
            echo "error!";
            $errors=[];
            while($row=sqlsrv_fetch_array($r))
            {
                $errors[$row['id_log']]=$row['table']; 
            }
        }
        else 
            echo "ok";
    }
    //echo "|";

    function insertData($conn,$table,$header,$rows)
    {
        $query2="DELETE FROM [$table]";

        $result2=sqlsrv_query($conn,$query2);
        if($result2==FALSE)
        {
            insertLogRow($conn,$table,$query2,print_r(sqlsrv_errors(),TRUE),'error');
            //die("\n\nerror query: ".$query2."\n\nerror message: ".print_r(sqlsrv_errors(),TRUE));
        }
        else
        {
            insertLogRow($conn,$table,$query2,'','success');
            $query1="INSERT INTO [$table] ([".implode('],[',$header)."]) SELECT";	
            foreach ($rows as $r)
            {
                $query1.=" '".implode("','",$r)."' UNION ALL SELECT";
            }
            $query1=substr($query1, 0, -17);

            $result1=sqlsrv_query($conn,$query1);
            if($result1==FALSE)
            {
                insertLogRow($conn,$table,$query1,print_r(sqlsrv_errors(),TRUE),'error');
                //die("\n\nerror query: ".$query1."\n\nerror message: ".print_r(sqlsrv_errors(),TRUE));
            }
            else
            {
                insertLogRow($conn,$table,$query1,'','success');
            }
        }
    }
    function insertLogRow($conn,$table,$query,$errorMessage,$type)
    {
        $query=str_replace("'",'"',$query);
        $errorMessage=str_replace("'",'"',$errorMessage);
        $query1="INSERT INTO [dbo].[log_importa_dati]
                        ([table]
                        ,[query]
                        ,[errorMessage]
                        ,[type]
                        ,[dataOra]
                        ,[utente]
                        ,[sessionId])
                VALUES
                        ('$table'
                        ,'$query'
                        ,'$errorMessage'
                        ,'$type'
                        ,GETDATE()
                        ,".getIdUtente($conn,$_SESSION['Username'])."
                        ,'".session_id()."')";	
        $result1=sqlsrv_query($conn,$query1);
        if($result1==FALSE)
        {
            //die("log_error");
            echo "log_error";
        }
        /*if($result1==FALSE)
        {
            echo "\n\n---------------------------------------------------------------Errore esecuzione query\n\nQuery: ".$query1."\n\nErrore: ";
            die(print_r(sqlsrv_errors(),TRUE)."\n\n---------------------------------------------------------------");
        }*/
    }
    function getIdUtente($conn,$username) 
    {
        $q="SELECT id_utente FROM utenti WHERE username='$username'";
        $r=sqlsrv_query($conn,$q);
        if($r==FALSE)
        {
            insertLogRow($conn,$table,$q,print_r(sqlsrv_errors(),TRUE),'error');
            /*echo "<br><br>Errore esecuzione query<br>Query: ".$q."<br>Errore: ";
            die(print_r(sqlsrv_errors(),TRUE));*/
        }
        else
        {
            while($row=sqlsrv_fetch_array($r))
            {
                return $row['id_utente'];
            }
        }
    }
    function getColumnsNum($conn,$table)
    {
        $query1="SELECT COUNT(*) AS columnsNum
			FROM global_trading2.INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = N'$table'";	
        $result1=sqlsrv_query($conn,$query1);
        if($result1==FALSE)
        {
            insertLogRow($conn,$table,$query1,print_r(sqlsrv_errors(),TRUE),'error');
            //die("\n\nerror query: ".$query1."\n\nerror message: ".print_r(sqlsrv_errors(),TRUE));
        }
        else
        {
            while($row=sqlsrv_fetch_array($result1))
            {
                return $row["columnsNum"];
            }
        }
    }

?>