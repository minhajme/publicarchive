<?php
    error_reporting(0);
    @ini_set('display_errors', 'off');
    define('DS',DIRECTORY_SEPARATOR);
    require("..".DS."lib".DS."phpSet.php");
    session_start();
    require_once("..".DS."lib".DS."func.php");
    
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2
// Creation Date: 12/09/2013
// Updated To V.2 : 01/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/

    if (!isset($_POST['get'])) { 
    
        echo 'max_execution_time :  '.ini_get('max_execution_time') ."\n";
        echo 'max_input_time :  '.ini_get('max_input_time') ."\n";
        echo 'session.gc_maxlifetime :  '.ini_get('session.gc_maxlifetime') ."\n";
        echo 'session.cookie_lifetime :  '.ini_get('session.cookie_lifetime') ."\n";
        echo 'session.cache_expire :  '.ini_get('session.cache_expire') ."\n";
        echo 'memory_limit :  '.ini_get('memory_limit') ."\n";
        echo 'upload_max_filesize :  '.ini_get('upload_max_filesize') ."\n";
        echo 'post_max_size :  '.ini_get('post_max_size') ."\n";
        echo "\n\n";
        print_r($_POST);
        echo "\n\n";
        print_r($_FILES);
        echo "\n\n";
        
        die('die41');  
    } else {

        $token = $_POST['get'];
        
    }
// connection with tocken protection init! <-- will die if bad token is set!
    require_once('..'.DS.'lib'.DS.'conndb.php');

    $sqlstatus="SELECT * FROM ".$table1." WHERE id='1'";
    $rs_result = mysqli_query($linkos, $sqlstatus) or die ("die1"); 
    while ($idr = mysqli_fetch_array($rs_result)) {
            $brand = $idr['brand_name'];
            $accept_types = $idr['accept_types'];
            $maxfiles = $idr['maxfiles'];
            $maxfile_size = $idr['maxfile_size'];
            $maxrecipients = $idr['maxrecipients'];
            $e_auto_title = $idr['e_auto_title'];
            $e_auto_body = $idr['e_auto_body'];
            $e_auto_title_copy = $idr['e_auto_title_copy'];
            $e_auto_body_copy = $idr['e_auto_body_copy'];
            $files_path = $idr['files_folder'];
            $servermail = $idr['server_mail'];
            $users_mode = $idr['users_mode'];
        }

// set limitations:

    switch($users_mode) {
    
        case "users":
                        if (isset($_COOKIE['user_login'])) {
                            
                            //get user:
                            $userName = explode("%",$_COOKIE['user_login']);
                            if (isset($userName[0])) {
                                $userName = $userName[0];
                            } else {
                                echo('log1');
                                exit;                            
                            }
                            
                            $userName = mysqli_real_escape_string($linkos, $userName); 
                            //get user settings: 
                            $sqlstatus = "SELECT * FROM ".$table6." WHERE `username`='".$userName."'";
                            $rs_result = mysqli_query($linkos, $sqlstatus) 
                                or 
                                    die ( "Some thing went terribly wrong contact Admin ~ Code: 230983" ); 
                               
                                    while ($idr=mysqli_fetch_array($rs_result)) {
                                        $maxfiles 		= $idr['maxfiles'];
                                        $maxrecipients 	= $idr['maxrec'];
                                        $maxfile_size	= $idr['maxsize'];
                                        $userEmail		= $idr['usermail'];
                                        $userFullName   = $idr['fullname'];
                                        $DbUserName   = $idr['username'];
                                        $DbUserPass   = $idr['password'];
                                    }
                                    
                            //check leagal cookie:
                            if ($_COOKIE['user_login'] != $DbUserName.'%'.$DbUserPass) {
                            
                                echo('log2');
                                exit;                                
                            
                            }    
                        } else {
                            
                            echo('log3');
                            exit;
                            
                        }
            break;
        case "guests":
                        //get guests default settings: 
                        $sqlstatus = "SELECT * FROM ".$table7." WHERE id=1";
                        $rs_result = mysqli_query($linkos, $sqlstatus) 
                            or 
                                die ( "Some thing went terribly wrong contact Admin ~ Code: 230983" ); 
                           
                                while ($idr=mysqli_fetch_array($rs_result)) {
                                    $maxfiles 		= $idr['maxfiles'];
                                    $maxrecipients 	= $idr['maxrec'];
                                    $maxfile_size		= $idr['maxsize'];
                                }
            break;
        case "users-guests":
                        if (!isset($_POST['fromsend'])) { //user:
                            if (isset($_COOKIE['user_login'])) {
                            
                                //get user:
                                $userName = explode("%",$_COOKIE['user_login']);
                                if (isset($userName[0])) {
                                    $userName = $userName[0];
                                } else {
                                    echo('log1');
                                    exit;                            
                                }
                                
                                $userName = mysqli_real_escape_string($linkos, $userName); 
                                //get user settings: 
                                $sqlstatus = "SELECT * FROM ".$table6." WHERE `username`='".$userName."'";
                                $rs_result = mysqli_query($linkos, $sqlstatus) 
                                    or 
                                        die ( "Some thing went terribly wrong contact Admin ~ Code: 230983" ); 
                                   
                                        while ($idr=mysqli_fetch_array($rs_result)) {
                                            $maxfiles 		= $idr['maxfiles'];
                                            $maxrecipients 	= $idr['maxrec'];
                                            $maxfile_size	= $idr['maxsize'];
                                            $userEmail		= $idr['usermail'];
                                            $userFullName   = $idr['fullname'];
                                            $DbUserName   = $idr['username'];
                                            $DbUserPass   = $idr['password'];
                                        }
                                        
                                //check leagal cookie:
                                if ($_COOKIE['user_login'] != $DbUserName.'%'.$DbUserPass) {
                                
                                    echo('log2');
                                    exit;                                
                                
                                }                            
                            
                            } else { //login requierd
                                
                                echo('log4');
                                exit;
                                
                            }                        
                        } else { //guest:
                        
                            //get guests default settings: 
                            $sqlstatus = "SELECT * FROM ".$table7." WHERE id=1";
                            $rs_result = mysqli_query($linkos, $sqlstatus) 
                                or 
                                    die ( "Some thing went terribly wrong contact Admin ~ Code: 230983" ); 
                               
                                    while ($idr=mysqli_fetch_array($rs_result)) {
                                        $maxfiles 		= $idr['maxfiles'];
                                        $maxrecipients 	= $idr['maxrec'];
                                        $maxfile_size		= $idr['maxsize'];
                                    }      
                                    
                        }
                            
            break;
        default: die('shlomi1');
        
    }
    
// proccess variables:
    $validator_catch_errors = array();
    $recipients             = array();
    $fromsend               = "";
    $messsage_head_recip    = $e_auto_title;
    $messsage_body_recip    = $e_auto_body;
    $messsage_head_copy     = $e_auto_title_copy;
    $messsage_body_copy     = $e_auto_body_copy;
    $messsage_user          = "";
    $filetypes              = $accept_types;
    $filesizes              = (int)$maxfile_size;

/************************** PAGE - FUNCTIONS **********************************/
   function check_email_address($email) {
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            return false;
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            $patt_mail = "/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-]".
                         "[A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,".
                         "63})|(\"[^(\\|\")]{0,62}\"))$/";
                         
            if (!preg_match($patt_mail, $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false;
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                $patt_mail2 = "/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}".
                              "[A-Za-z0-9])|([A-Za-z0-9]+))$/";
                if (!preg_match($patt_mail2, $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    function sendMailTakeThatStyleTorecipient(
             $to_user, $from_user, $subject, $message_define, $message_user, 
             $filename, $secret, $files_path, $servermail, $brand
    ) {
        $link = REF;
        $link = explode('?', $link);
        $link = $link[0];
        // message
        $file_only_name = explode('_', $filename);
        unset($file_only_name[0], $file_only_name[1]);
        $file_only_name = implode('_', $file_only_name);
        
        $message_join = "Hi, <br />".$from_user." sent you a file(s) he wants to ".
                        "share with you. <br /><br />".$message_define."<br />".
                        "<br /><strong>Here is your download link:</strong><br/><a href='".$link.
                        "?get=".$secret."' />".$file_only_name."</a>";
        
        if ($message_user!='') {
            $message_join .= "<br /><br /><strong>Note From Sender:</strong> <br />".$message_user;
        }
        
        // Additional headers			
        $header_to = explode('@',$to_user);
        $headers  = "MIME-Version: 1.0" . "\r\n" .
                    "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
                    "To: ".ucfirst($header_to[0])." <".
                    $header_to[0].$header_to[1].">"."\r\n".
                    "From: ".ucfirst($brand)." <".$servermail.">" . "\r\n" .
                    "X-Mailer: PHP/".phpversion();
        
        // Mail it
        if (@mail($to_user, $subject, $message_join, $headers)) { 
            return true;  
        } else { 
            return false;
        }	
    }
    
    function sendMailTakeThatStyleTocopy(
             $to_users, $from_user, $subject_copy, $message_define_copy, $files,
             $group, $files_path, $servermail, $brand
    ) {
        // to users string:
        $users_string = "<ul><li>".implode("</li><li>", $to_users)."</li></ul>";
        
        //prepare link path:
        $link = REF;
        $link = explode('?', $link);
        $link = $link[0];
        
        //link file string:
        $link_file = "<ul>";
        foreach ($files as $key => $value) {
            $file_only_name = explode('_', $value[0]);
            unset($file_only_name[0], $file_only_name[1]);
            $file_only_name  = implode('_', $file_only_name);
            $link_file 		.= "<li><a href='".$link."?gr=".$group."&copy=1'>".
                               $file_only_name." - Size: ".
                               humanFileSize($value[1])."</a></li>"; 
        }
        $link_file .= "</ul>";
                    
        // message
        $message_join = "Hi,<br /> This is a copy of the file(s) ". 
                        "you sent.<br /><br />".$message_define_copy."<br />".
                        "<br /><strong>Here is your download link(s):</strong> <br />".$link_file.
                        "<br />That you sent to:<br />".$users_string;

        // Additional headers			
        $header_to 	= explode('@', $from_user);
        $headers  	= "MIME-Version: 1.0"."\r\n".
                    "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
                    "To: ".ucfirst($header_to[0]).
                    " <".$header_to[0].$header_to[1].">"."\r\n".
                    "From: ".ucfirst($brand)." <".$servermail.">" . "\r\n" .
                    "X-Mailer: PHP/".phpversion();
        
        // Mail it
        if (@mail($from_user, $subject_copy, $message_join, $headers)) { 
            return true;  
        } else { 
            return false; 
        }	
    }

    function humanFileSize($size, $unit="") {
        if((!$unit && $size >= 1<<30) || $unit == "GB")
            return number_format($size/(1<<30),2)."GB";
        if((!$unit && $size >= 1<<20) || $unit == "MB")
            return number_format($size/(1<<20),2)."MB";
        if((!$unit && $size >= 1<<10) || $unit == "KB")
            return number_format($size/(1<<10),2)."KB";
        return number_format($size)." bytes";
    }

    
/************************** PROCEDURE BEGINS HERE *****************************/


// VALIDATE EXCLUDES AND BOT PROTECTION:

    // if first time set a timestamp:
    $excludes = array();

    if (defined('SERVERREMOTE')) 
        $user_ip = mysqli_real_escape_string($linkos, SERVERREMOTE);
    else 
        $user_ip = false;
        
    if (defined('USERAGENT')) 
        $user_agent = mysqli_real_escape_string($linkos, USERAGENT); 
    else 
        $user_agent = false;
            
    if (!isset($_SESSION['time'])) { 
        $_SESSION['time'] = time(); 
        $flag_time_protect = false; 
    } else { 
        $flag_time_protect = true; 
    }
    
    if (!isset($_SESSION['timetries'])) 
        $_SESSION['timetries'] = 1;
        
    if ($flag_time_protect===true) { 
        $user_rate = (time() - $_SESSION['time']);
        $_SESSION['time'] = time(); 
    } 
                                    
    //get Email address:
    switch($users_mode) {
    
        case "users":
        
                $user_email = mysqli_real_escape_string($linkos, $userEmail);
        
        break;
        case "guests":
                if (isset($_POST["fromsend"])) 
                    $user_email = mysqli_real_escape_string($linkos, $_POST["fromsend"]); 
                else 
                    $user_email = false;        
            break;
        case "users-guests":
                if (isset($userEmail)) {
                
                    $user_email = mysqli_real_escape_string($linkos, $userEmail);

                } else {
                
                    if (isset($_POST["fromsend"])) 
                        $user_email = mysqli_real_escape_string($linkos, $_POST["fromsend"]); 
                    else 
                        $user_email = false;                   
                }
                
            break;
            
        default: die('shlomi1');
        
    }

    // check conditions:
        // check for basic variables needed:
        if (!$user_ip || !$user_agent) { 
            $excludes[] = 'nothuman1'; 
        }
                
    // check for time rate that is human!
        if (isset($user_rate)&&$user_rate<3) { 
            $_SESSION['timetries']++; 
        }
        
        if (isset($_SESSION['timetries']) && $_SESSION['timetries']>5) { 
            $excludes[] = 'nothuman2'; 
            // block list insert:
            $sqlstat3 = "INSERT INTO ".$table3."(
                                                `ip_user`,
                                                `user_agent`,
                                                `when_blocked`
                                                ) VALUES (
                                                '$user_ip',
                                                '$user_agent',
                                                NOW()
                                                )";
            
            if (!mysqli_real_query($linkos, $sqlstat3)) { }																				
        }
        
        if(count($excludes)<1) {
            // check in the database exclude and blocked users!
            $sqlstatus4="SELECT * FROM $table3 WHERE `ip_user`='$user_ip'";
            $stmt = mysqli_prepare($linkos, $sqlstatus4); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt); 
            $rows_blocked = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);
                        
            $sqlstatus5="SELECT * FROM $table5 WHERE `email_address`='$user_email'";
            $stmt = mysqli_prepare($linkos, $sqlstatus5); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt); 
            $rows_exclude = mysqli_stmt_num_rows($stmt);
            mysqli_stmt_close($stmt);
                    
            if ($rows_blocked>0 || $rows_exclude>0) 
                $excludes[] = 'unauthorized';
        }

        // execute blocking:
        if (count($excludes)>0) {  
            echo "blo".json_encode($excludes); 
            exit;  
        }
                
// VALIDATE FILES:

    if (!isset($_FILES)) { 
        $validator_catch_errors[] = 'File1'; 
    } else {
     
        // make sure we proccess only accepted amount of 
        // file <- mybe hacked by user via js so kill it here!
        if (count($_FILES)>$maxfiles) { 
            array_slice($_FILES, 0, $maxfiles); 
        }
                        
        // searh for not empty input fields:
        $flag=0;
        
        for ( $i = 0; $i < count($_FILES); $i++ ) { 
            if($_FILES['file'.($i+1)]['name']=='') { 
                $flag++; 
            } 
        }
        
        if ( $flag == count($_FILES) ) { 
            $validator_catch_errors[] = 'File1'; 
        }
    }


                
// VALIDATE MAIL RECIPIENTS ADDRESS: RECIPIENTS:

    $flag_recipients = 0;
    foreach ($_POST as $key => $values) {
        if (substr($key, 0, 6)=='sendto' && $values!="") {
            if (check_email_address($values)) {
                $recipients[] = mysqli_real_escape_string($linkos, $values);
            } else {
                $validator_catch_errors[]= 'tosendem-'.$key;
                $flag_recipients++;
            }
        }
    }
    
    // make sure there are recipients sent
    if (count($recipients)<1) {
        if ($flag_recipients<1) { 
            $validator_catch_errors[] = 'tosend1'; 
        }
    } else {
        // make sure we proccess only accepted amount of 
        // recipients <- mybe hacked by user via js so kill it here!
        if (count($recipients)>$maxrecipients) { 
        array_slice($recipients, 0, $maxrecipients); 
        }
    }
    
    // remove duplicate recipients:
    $recipients = array_unique($recipients);
        

// VALIDATE MAIL SENDER ADDRESS:

    if (!$user_email) { 
        $validator_catch_errors[] = 'fromsend1'; 
    } else { 
        $fromsend = $user_email; //ecaped in earli  
    }
    if (!in_array("fromsend1", $validator_catch_errors)) {
        if (!check_email_address($fromsend)) { 
            $validator_catch_errors[] = 'fromsend2'; 
        }
    }
    
// VALIDATE FILE TYPES:

    if (!in_array("File1", $validator_catch_errors)) {
        $filetypes = explode(",", $filetypes);
        foreach ($_FILES as $key => $file) {
            if ($file["name"]!=null) {
                $type = explode('.', $file["name"]);
                $type = strtolower($type[count($type)-1]);
                if (!in_array(".".$type,$filetypes)) { 
                    $validator_catch_errors[] = 'filetype-'.$key; 
                }
            }
        }	
    }

// VALIDATE FILE SIZES:

    if (!in_array("File1", $validator_catch_errors)) {
        foreach ($_FILES as $key => $file) {
            if ($file["size"]!=null) {
                if ($file["size"]>$filesizes) {
                    $validator_catch_errors[] = 'filesize-'.$key; 
                }
            }
        }
    }
    
// CLEAN MESSAGE FROM TAGS:

    if (isset($_POST["message"]) && $_POST["message"]!="") { 
        $messsage_user = mysqli_real_escape_string(
                        $linkos, strip_tags($_POST["message"])
                        ); 
    }
        
// IF EVERYTHING IS OK PROCCESS AND EXECUTE:

if (count($validator_catch_errors)<1) { // no errors validating	
    
    // CHECK FOR USER BEHAVIOR BEFORE STARTING!:
    if (isset($_POST['be'])) {
        $user_behavior_log = $_POST['be'];
        $user_behavior_log = explode('-', $user_behavior_log);
        $user_behavior_log = array_filter($user_behavior_log);
        $user_behavior_log = array_unique($user_behavior_log);
                                
        if (!in_array('top1',$user_behavior_log) 
            ||!in_array('top2', $user_behavior_log)
            ||!in_array('top3',$user_behavior_log)
        ) {
            echo "hum"; exit;
        }
    } else { 
        echo "hum"; 
        exit; 
    }
    
    // file list and debuger:
    $file_list  = array();
    $debug_list = array();
    
    // store file:
    $username   = explode('@', $fromsend);
                        
    for($i=0; $i<count($_FILES); $i++) { 
        if($_FILES['file'.($i+1)]['name']!='') { 
            
            $prefix = uniqid();
            $file_type = explode('.',$_FILES['file'.($i+1)]['name']);
            $file_type = $file_type[count($file_type)-1];
            
            if(@move_uploaded_file($_FILES['file'.($i+1)]['tmp_name'], 
               $files_path.
               $username[0].
               "_".$prefix.
               "_".$_FILES['file'.($i+1)]['name']
               )
            ) {
                $file_list[] = array(
                                     $username[0]."_".
                                     $prefix."_".
                                     $_FILES['file'.($i+1)]['name'],
                                     $_FILES['file'.($i+1)]['size'],
                                     $file_type
                                     );
            } else {
                $debug_list[] = 'upload'.($i+1);
            }
        }
    } 
                        
    // step1: store to log & approval
                            
    $log_group_for_copy_send = uniqid($username[0]);
    $log_group 	= mysqli_real_escape_string($linkos, $log_group_for_copy_send);
    $log_sender =  $fromsend; // allready escaped
    
    if (isset($_POST['checkbox_notify']) 
        && $_POST['checkbox_notify'] == 'on'
    ) 
    $log_notify = true; else $log_notify = false;
    
    if (isset($_POST['checkbox_copy']) 
        && $_POST['checkbox_copy'] == 'on'
    ) $log_copy = true; else $log_copy = false; 
                            
    if (defined('SERVERREMOTE')) 
        $log_user_ip = mysqli_real_escape_string($linkos, SERVERREMOTE); 
    else 
        $log_user_ip = 'False!';
                            
    if (defined('USERAGENT')) 
        $log_user_agent = mysqli_real_escape_string($linkos, USERAGENT); 
    else 
        $log_user_agent = 'False!';
    

    // vars to insert:
    $log_message    = $messsage_user; // allready escaped
    $timestamp      = date('Y-m-d G:i:s');
    $log_filename   = "";
    $log_filetype   = "";
    $log_filesize   = "";
    $log_to         = "";
    
    // build list and send the mails:
    foreach ($file_list as $key => $value) {
        $filename_for_delete_if_needed = $value[0];
        $log_filename   = mysqli_real_escape_string($linkos, $value[0]);
        $log_filetype   = mysqli_real_escape_string($linkos, $value[2]);
        $log_filesize   = mysqli_real_escape_string($linkos, $value[1]);
                                    
        foreach ($recipients as $index => $to_send) {
            $to_send_name   = explode('@', $to_send);
            $secret         = uniqid($to_send_name[0]);
            $log_secret     = mysqli_real_escape_string($linkos, $secret);									
            $flag_sent_recip=true;
            
            // send it:
            if (sendMailTakeThatStyleTorecipient(
                $to_send,
                $log_sender,
                $messsage_head_recip,
                $messsage_body_recip,
                $messsage_user,
                $log_filename,
                $secret,
                $files_path,
                $servermail,
                $brand
                )
            ) {  
            } else { 
                $flag_sent_recip = false; 
            }

            if	($flag_sent_recip) { 
                // if i succeded sending the mail:
                // strore to LOG files
                $log_to = $to_send; //allready escaped!
                $sqlstat= "INSERT INTO ".$table2." (
                                                    `group`,
                                                    `filename`,
                                                    `sender`,
                                                    `to`,
                                                    `notify`,
                                                    `copy`,
                                                    `user_ip`,
                                                    `user_agent`,
                                                    `message`,
                                                    `when_sent`,
                                                    `file_type`,
                                                    `file_size`
                                                    
                                                    ) VALUES (
                                                    
                                                    '$log_group',
                                                    '$log_filename',
                                                    '$log_sender',
                                                    '$log_to',
                                                    '$log_notify',
                                                    '$log_copy',
                                                    '$log_user_ip',
                                                    '$log_user_agent',
                                                    '$log_message',
                                                    '$timestamp',
                                                    '$log_filetype',
                                                    '$log_filesize'
                                                    )";
                
                if(!mysqli_real_query($linkos, $sqlstat)) { 
                    $debug_list[] = 'logfil'.($index+1); 
                }

                // store to LOG approval					
                $sqlstat= "INSERT INTO ".$table4." (`file_name`,
                                                    `notify_to`,
                                                    `who`,
                                                    `secret`,
                                                    `do_notify`,
                                                    `status_notify`,
                                                    `when_notify`
                                                    ) VALUES (
                                                    '$log_filename',
                                                    '$log_sender',
                                                    '$log_to',
                                                    '$log_secret',
                                                    '$log_notify',
                                                    '0',
                                                    '$timestamp'
                                                    )";
                if(!mysqli_real_query($linkos, $sqlstat)) { 
                    $debug_list[] = 'logapp'.($index+1); 
                }								
            } else { // cant send the mail so drop all!
                
                // removes the bad file
                if (file_exists($files_path.$filename_for_delete_if_needed)) {
                    unlink($files_path.$filename_for_delete_if_needed);
                }
                $debug_list[] = 'mailre'.($index+1); // debuger catch;
            }
        }						
    }
                            
    // send copy to user if requested:
    if ($log_copy) { 
        if(sendMailTakeThatStyleTocopy(
            $recipients,
            $fromsend,
            $messsage_head_copy,
            $messsage_body_copy,
            $file_list,
            $log_group_for_copy_send,
            $files_path,$servermail,
            $brand)
        ) {
        } else { 
            $debug_list[] = 'mailco'; 
        } 
    }
                            
    // output debuger handler:
    if(count($debug_list)>0) { 
        echo "deb".json_encode($debug_list); 
    } else { 
        echo "ok[";  
    }
} else {
    // there is validation isusses return json:
    echo "val".json_encode($validator_catch_errors); 
} 
    mysqli_close($linkos);
?>