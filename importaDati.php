<?php
	
	include "Session.php";
	include "connessione.php";

    set_time_limit(3000);

    $tables=
    [
        'bi_anaage',
        'bi_anaart',
        'bi_anaclif',
        'bi_anapag',
        'bi_anazone',
        'bi_fatart'
    ];

    $q2="DELETE FROM log_importa_dati WHERE sessionId='".session_id()."'";
    $r2=sqlsrv_query($conn,$q2);
    if($r2==FALSE)
    {
        echo "log_error";
    }
    
    foreach ($tables as $table)
    {
        insertData($conn,$table);
    }
    $q="SELECT * FROM log_importa_dati WHERE sessionId='".session_id()."' AND type='error'";
    $r=sqlsrv_query($conn,$q);
    if($r==FALSE)
    {
        echo "log_error";
    }
    else
    {
        $rows = sqlsrv_has_rows( $r );
        if ($rows === true)
        {
            echo "3rr0rdata|";
            $errors=[];
            while($row=sqlsrv_fetch_array($r))
            {
                $errors[$row['id_log']]=$row['table']; 
            }
            echo json_encode($errors);
        }
        else
        {
            echo cleanTables($conn,$tables);
        }
    }

    function cleanTables($conn)
    {
        $result="ok";
        $resultArray=[];
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'''','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'  ','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'  ','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'  ','')"));
        foreach ($resultArray as $queryResult)
        {
            if($queryResult==FALSE)
                $result= "error";
        }

        
        echo $result;
        /*$result="ok";
        $resultArray=[];
        $doubleQuote='"';

       array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'  ','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],' ','_')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'".$doubleQuote."','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'''','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'%','PERCENT')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'*','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'+','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'-','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'/','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-MADRE] = REPLACE([FAR_ID_ART-MADRE],'\\','')"));

        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'  ','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],' ','_')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'".$doubleQuote."','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'''','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'%','PERCENT')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'*','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'+','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'-','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'/','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ART-RADICE] = REPLACE([FAR_ID_ART-RADICE],'\\','')"));

        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'  ','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],' ','_')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'".$doubleQuote."','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'''','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'%','PERCENT')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'*','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'+','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'-','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'/','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_fatart] SET [FAR_ID_ARTICOLO] = REPLACE([FAR_ID_ARTICOLO],'\\','')"));

        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'  ','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],' ','_')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'".$doubleQuote."','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'''','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'%','PERCENT')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'*','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'+','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'-','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'/','')"));
        array_push($resultArray,sqlsrv_query($conn,"UPDATE [bi_anaclif] SET [ACF_RAGSOC] = REPLACE([ACF_RAGSOC],'\\','')"));

        foreach ($resultArray as $queryResult)
        {
            if($queryResult==FALSE)
                $result= "error";
        }

        echo $result;*/
    }
    function insertData($conn,$table)
    {
        $query2="DELETE FROM [$table]";

        $result2=sqlsrv_query($conn,$query2);
        if($result2==FALSE)
        {
            insertLogRow($conn,$table,$query2,print_r(sqlsrv_errors(),TRUE),'error');
        }
        else
        {
            insertLogRow($conn,$table,$query2,'','success');
            $query1="BULK INSERT global_trading2.dbo.".$table."
                    FROM 'C:\\xampp\\htdocs\\global_trading\\import_files\\".$table.".csv'
                    WITH
                    (
                        FIRSTROW = 2,
                        FIELDTERMINATOR = ';',
                        ROWTERMINATOR = '\n',
                        TABLOCK
                    )";

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