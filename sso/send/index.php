<?php
    error_reporting(0);
    @ini_set('display_errors', 'off');
    define('DS', DIRECTORY_SEPARATOR);
    require("code".DS."lib".DS."phpSet.php");
    session_start();
    require("code".DS."lib".DS."func.php");
    
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2.1
// Creation Date: 12/09/2013
// Updated To V.2.1 : 05/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/
    
    //tockenize the page:
    gettoken();
    $token = md5($_SESSION['user_token']);
    require("code".DS."lib".DS."conndb.php");
    //get user settings: 
    $sqlstatus = "SELECT * FROM $table1 WHERE id=1";
    $rs_result = mysqli_query($linkos, $sqlstatus) 
        or 
            die ( "1. currently not available." ); 
    $users_mode  = '';        
            while ($idr=mysqli_fetch_array($rs_result)) {
                $brand 			= $idr['brand_name'];
                $accept_types 	= $idr['accept_types'];
                $files_path		= $idr['files_folder'];
                $servermail 	= $idr['server_mail'];
                $users_mode 	= $idr['users_mode'];
                $themeUse       = $idr['theme'];
            }
            

    if (isset($brand) && isset($accept_types)) {
        
        if (!isset($_GET["get"]) && !isset($_GET["gr"])) {

            switch($users_mode) {
                
                //Main -> users & guests:
                case "users-guests":
                    include("code".DS."client".DS."usersNguests.php");   
                break;
                
                //Main -> guests:
                case "guests":
                    include("code".DS."client".DS."GuestsOnlyPage.php");
                break;
                
                //Main -> users:
                case "users":
                    require("code".DS."client".DS."password_protect_users.php");
                    include("code".DS."client".DS."usersOnlyPage.php");
                break;
                
                //default Main -> users & guests:
                default:  echo "<h3>Installation needed!</h3>";            
            }
        } else {
            include("code".DS."client".DS."downloadFile.php");
        }

    } else {
        // Output a installation needed
        echo "<h3>Installation needed!</h3>";
    }

    mysqli_close($linkos);

    
    
/*******************************   FUNCTIONS   ********************************/
    function humanFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }
    
    function sendMailTakeThatNotificationApprove(
                $to_user, $from_user, $filename, $servermail, $brand, $confirm
    ) {
        //$subject:
        $subject  = 'Your file has been downloaded.';
                        
        //file name:
        $filename = explode('_', $filename);
        unset($filename[0], $filename[1]);
        $filename = implode('_', $filename);
                        
        // message
        $message_join = 'Hi,<br /><br /> As requested we are notifying you that - '.
            $from_user.' has downloaded the file (<b>'.$filename.
            '</b>) you sent him. <br />Your confirmation token is:'.
            ' <b>'.$confirm.'</b> .<br /><br /> Have a nice day. ';

        // Additional headers			
        $header_to = explode('@', $to_user);
        $headers  = 'MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                    'To: '.ucfirst($header_to[0]).' <'.$header_to[0].$header_to[1].'>'."\r\n".
                    'From: '.ucfirst($brand).' <'.$servermail.'>' . "\r\n" .
                    'X-Mailer: PHP/'.phpversion();
        // Mail it
        if (@mail($to_user, $subject, $message_join, $headers)) { 
            return true;  
        } else { 
            return false; 
        }	
    }
    
    function createUrlFromAbsolutePath($ab_path){

        //check for matching path to file system: 
        if (strpos(DOCROOT,DS)=== false) { 
            if (DS=='/') { 
                $server_root = str_replace('\\', DS, DOCROOT); 
            } else { 
                $server_root = str_replace('/', DS, DOCROOT); 
            } 
        } else { 
            $server_root = DOCROOT; 
        }  
        if (substr($server_root, -1)!=DS) { 
            $server_root.=DS; 
        }
        
        //delete un needed trail root:
        $files_root = substr($ab_path, strlen($server_root)-1);
        
        //parse to Url compabillity:
        $files_dir_url = str_replace('\\', '/', SERVERNAME.$files_root);
        
        return ($files_dir_url);

    }
?>