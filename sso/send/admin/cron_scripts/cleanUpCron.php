<?php
    error_reporting(0);
    @ini_set('display_errors', 'off');   
    define('DS', DIRECTORY_SEPARATOR);
/*******************************   DEFINE   ***********************************/


//weeks interval => older then [1, 2, 3, 4] weeks  
$intervalweeks = 2;

//write to log?
$write_to_log = true; 

  
/******************************************************************************/
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2
// Creation Date: 12/09/2013
// Updated To V.2 : 01/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/

/******************************     FUNCTIONS      ****************************/
    // CHECK THAT FILES IS WRITEABLE:

    function write_to_log_success($deleted) {    
        if (file_exists("clean_up_log.txt")
            &&is_writable("clean_up_log.txt")
        ) {
                $f = fopen("clean_up_log.txt", "a");
                $msg = utf8_encode(date("Y-m-d H:i:s")." --> Cronjob Done! deleted ".
                        $deleted." files from server. \r\n");
                fwrite($f, $msg);
                fclose($f);
        }
    }

    function write_to_log_Error($error_message) {    
        if (file_exists("clean_up_log.txt")
            &&is_writable("clean_up_log.txt")
        ) {
                $f = fopen("clean_up_log.txt", "a");
                $msg = utf8_encode(date("Y-m-d H:i:s")." --> ".
                        $error_message.". \r\n");
                fwrite($f, $msg);
                fclose($f);
        }
    }
    
/****************  connection to db with protection  **************************/

    require_once("..".DS."..".DS."code".DS."lib".DS."dbvar.php");

    $table1 = "install_manager";
    $table2 = "files_log";
    $table3 = "blocked_users";
    $table4 = "approval_log";
    $table5 = "exclude_users";
    $table6 = "users_manager";
    $table7 = "def_account";
        
    $linkos = @mysqli_connect($host, $data_username, $data_password);
    if (!$linkos) {
        if ($write_to_log) {
            write_to_log_Error("No DB connection - installation is required! Code: dbcon12");
            exit;
        } else {
            exit;
        }  
    }
        
    //using utf8 data
    mysqli_query($linkos, "SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    mysqli_set_charset($linkos, "utf8_general_ci");
    if (!mysqli_select_db($linkos, $database)) {
        if ($write_to_log) {
            write_to_log_Error("No DB connection - installation is required! Code: dbset13");
            exit;
        } else {
            exit;
        }  
    }
        
/******************************************************************************/







/*****************************    PROCEDURE        ****************************/
    if (isset($intervalweeks)
        && is_numeric($intervalweeks)
        && $intervalweeks > 0
    ) {
            
        //files to clean:
        $sqlstat = "SELECT * FROM ".$table2." WHERE ".
                   "`when_sent` < NOW() - INTERVAL ".$intervalweeks." WEEK";
        $rs_result = mysqli_query($linkos, $sqlstat); 
        if (!$rs_result) {
            if ($write_to_log) {
                write_to_log_Error("Can't Search File logs!  Code: dblog14");
                exit;
            } else {
                exit;
            }
        } 
        
        $logids = array();
        while ($idr = mysqli_fetch_array($rs_result)) {
            $logids[]             = $idr['id'];
            $who_sent[]           = $idr['sender'];
            $to_sent[]            = $idr['to'];
            $file_server_name[]   = $idr['filename'];
        }
                
        //folder path:
        $sqlstatus = "SELECT * FROM ".$table1." WHERE `id`='1'";
        $rs_result = mysqli_query($linkos, $sqlstatus); 

        if (!$rs_result) {
            if ($write_to_log) {
                write_to_log_Error("Can't find files folder!  Code: dbpath15");
                exit;
            } else {
                exit;
            }
        } else { 
        
            while ($idr = mysqli_fetch_array($rs_result)) {
                $files_path = $idr['files_folder'];
            }
            
            //clean log,approval,folder files:
            $count_removed = 0;
            foreach ($logids as $keys => $idss) {  
                            
                //delete from files log:
                $sqlstat1 = "DELETE FROM ".$table2." WHERE `id`='".$idss."'";
                
                if (!mysqli_real_query($linkos, $sqlstat1)) { 
                    if ($write_to_log) {
                        write_to_log_Error("Can't Delete from file log table! file name: ".$file_server_name[$keys]);
                    }
                }
                                
                //delete from approval log:
                $sqlstat2 = "DELETE FROM ".$table4." WHERE 
                            `file_name`='".mysqli_real_escape_string($linkos, $file_server_name[$keys])."' 
                            AND `notify_to`='".mysqli_real_escape_string($linkos, $who_sent[$keys])."' 
                            AND `who`='".mysqli_real_escape_string($linkos, $to_sent[$keys])."'
                            ";
                if(!mysqli_real_query($linkos, $sqlstat2)) { 
                    if ($write_to_log) {
                        write_to_log_Error("Can't Delete from aproval log table! file name: ".$file_server_name[$keys]);
                    }
                } 
                                
                //delete files:
                if(isset($files_path)) {
                    if (@unlink($files_path.$file_server_name[$keys])) 
                    {  
                        $count_removed ++; 
                    }
                }
                
            }
            
            //log results:
            if ($write_to_log) {
                write_to_log_success($count_removed);
            } 
            exit;
        }
    } else {
    
        if ($write_to_log) {
            write_to_log_Error("No weeks interval Set! Code: intset11");
            exit;
        }  
        
    }
?>