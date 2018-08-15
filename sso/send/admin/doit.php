<?php
    error_reporting(0);
    @ini_set('display_errors', 'off');   
    session_start();
    define('DS', DIRECTORY_SEPARATOR);

/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2.1
// Creation Date: 12/09/2013
// Updated To V.2.1 : 05/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/



/******************************************************************************/
/*************************** requird core files *******************************/
/******************************************************************************/

    require_once("..".DS."code".DS."lib".DS."func.php");

    if (!isset($_POST['user_token'])) 
        die('die4');
    else 
        $token = $_POST['user_token'];

    require("..".DS."code".DS."lib".DS."conndb.php");
    require_once("password_protect.php");

    if (isset($uname)) {
    if($uname!="nouser") {
    
        // MAKE SURE SENDER WHAT IS SET OR DIE:
        if(!isset($_POST['what_save'])) 
            die('die6'); 
        else 
            $who_send = $_POST['what_save'];
        
/******************************************************************************/        
/***************************** VALIDATE VALUES: *******************************/
/******************************************************************************/
        
    // general settings:
        $brand_name         = false;
        $max_files_count    = false;
        $max_file_size      = false;
        $max_rec            = false;
        $server_email       = false;
        $files_folder       = false;
        $mes_rec_title      = false;
        $mes_rec_body       = false;
        $mes_cop_title      = false;
        $mes_cop_body       = false;
        $accepted_types     = false;
        
        $admin_user_name    = false;
        $admin_user_pass    = false;
    // log search by dates:
        $s_date             = false;
        $e_date             = false;
        
    // user search by mail:
        $user_search_email  = false;
        
    // exclude user email:
        $user_ex_email      = false;
        $user_ex_note       = false;
        
    // remove excluded:
        $ex_rem_id          = false;
        
    // remove blocked:
        $block_rem_id       = false;
        
    // remove log:
        $log_id_rem         = false;
        
    //cleanup interval:
        $intervalweeks      = false;
        
/******************************************************************************/
/**************************** VALIDATE CONDITIONS: ****************************/
/******************************************************************************/        

// CONDITIONS GENERAL:
        
        //brand name:
        if (isset($_POST['brand_name_ge'])&&$_POST['brand_name_ge']!=''
            && $_POST['brand_name_ge']!=null
        ) 
            $brand_name =  mysqli_real_escape_string($linkos,$_POST['brand_name_ge']);
        
        //admin user and pass:        
        if (isset($_POST['userName'])
            &&isset($_POST['userPass'])
            &&strlen (trim(preg_replace('/\s+/', '', $_POST['userName']))) > 4 
            &&strlen (trim(preg_replace('/\s+/', '', $_POST['userPass']))) > 5 
        ) {
        
        $admin_user_name = mysqli_real_escape_string($linkos, 
            trim(preg_replace('/\s+/', '', $_POST['userName'])));
            
        $admin_user_pass = mysqli_real_escape_string($linkos, 
            md5(trim(preg_replace('/\s+/', '', $_POST['userPass']))));       
        
        }
        
        //Max files count:
        if (isset($_POST['max_files_ge']) 
            && $_POST['max_files_ge']!='' 
            && $_POST['max_files_ge']!=null 
            && is_numeric($_POST['max_files_ge']) 
            && intval($_POST['max_files_ge'])>0
        ) 
                $max_files_count =  mysqli_real_escape_string($linkos, $_POST['max_files_ge']);
                                
        if ($max_files_count != false) 
            if ($max_files_count>SYS_MAX_UPLOADS) 
                $max_files_count = SYS_MAX_UPLOADS;
        
        //Max file size:
        if (isset($_POST['max_size_ge']) 
            && isset($_POST['max_size_num_files']) 
            && $_POST['max_size_ge']!='' 
            && $_POST['max_size_ge']!=null 
            && is_numeric($_POST['max_size_ge']) 
            && intval($_POST['max_size_ge'])>0
        ) 		
            $max_file_size = $_POST['max_size_ge'];
            
        if ($max_file_size != false) {
            if($max_file_size>return_bytes(SYS_MAX_FILESIZE)) 
                $max_file_size = mysqli_real_escape_string($linkos, 
                    (return_bytes(SYS_MAX_FILESIZE) - 20000)
                );
                
            if ($max_file_size>(return_bytes(SYS_MAX_POST_SIZE)/intval($_POST['max_size_num_files']))) 
                $max_file_size = mysqli_real_escape_string($linkos, 
                    round((return_bytes(SYS_MAX_POST_SIZE)/
                            intval($_POST['max_size_num_files']))
                    )
                );
        }
        
        //Max recipients:        
        if (isset($_POST['max_rec_ge']) 
            && $_POST['max_rec_ge']!='' 
            && $_POST['max_rec_ge']!=null 
            && is_numeric($_POST['max_rec_ge'])
        ) 	
            $max_rec =  mysqli_real_escape_string($linkos,$_POST['max_rec_ge']);
        
        if ($max_rec != false) 
            if ($max_rec > (SYS_MAX_INPUT_VARS-SYS_MAX_UPLOADS-6)) 
                $max_rec = (SYS_MAX_INPUT_VARS-SYS_MAX_UPLOADS-6);	
         
        //Server Mail:
        if (isset($_POST['email_ge']) 
            && $_POST['email_ge']!='' 
            && $_POST['email_ge']!=null
        )  
            $server_email = mysqli_real_escape_string($linkos, 
                            filter_var($_POST['email_ge'], 
                            FILTER_VALIDATE_EMAIL)
            );
            
        //Uploaded files folder: 
        if (isset($_POST['files_folder']) 
            && file_exists($_POST['files_folder']) 
            && $_POST['files_folder']!=''
        )	
            $files_folder = mysqli_real_escape_string($linkos, 
                $_POST['files_folder']
            );
        
        //message to recipient title & body: 
        if (isset($_POST['mes_rec_title']) && isset($_POST['mes_rec_body'])) { 
            $mes_rec_title =  mysqli_real_escape_string($linkos,
                $_POST['mes_rec_title']); 
            $mes_rec_body =  mysqli_real_escape_string($linkos,
                $_POST['mes_rec_body']); 
        }
        
        //message to sender(copy) title & body:
        if (isset($_POST['mes_cop_title']) && isset($_POST['mes_cop_body'])) { 
            $mes_cop_title =  mysqli_real_escape_string($linkos, 
                $_POST['mes_cop_title']);
                
            $mes_cop_body =  mysqli_real_escape_string($linkos, 
                $_POST['mes_cop_body']); 
        }
        
        //File types allowed:
        if ($who_send == 'types_s' ) {
            foreach ($_POST as $key => $value) {
                if ($key!='what_save' && $key!='user_token') {
                    if($value=='true') {
                        if ($accepted_types==false || $accepted_types=='') {
                            $accepted_types = returnStringTypes($key); 
                        } else { 
                            $accepted_types .= ','.returnStringTypes($key); 
                        }
                    }
                }
            }
            if ($accepted_types=='') { 
                $accepted_types = false; 
            }
            $accepted_types = mysqli_real_escape_string($linkos,$accepted_types);
        }
                                                        
// CONDITIONS FOR LOG SEARCH BY DATES:

        //starting / ending search date:
        if (isset($_POST['s_date_log']) 
            && isset($_POST['e_date_log']) 
            && $_POST['s_date_log']!='' 
            && $_POST['e_date_log']!=''
        ) {
            if (validateDate($_POST['s_date_log'])) {
                $s_date =  preg_replace('/\s+/', '',
                    mysqli_real_escape_string($linkos,$_POST['s_date_log']));
            }
            if (validateDate($_POST['e_date_log'])) 
                $e_date =  preg_replace('/\s+/', '',
                mysqli_real_escape_string($linkos,$_POST['e_date_log'])
                );
                
            if ($s_date!=false&&$e_date!=false) 
                if (strtotime($e_date) < strtotime($s_date)) { 
                    $s_date = false; $e_date = false; 
                }
                                                        
        }
                                                        
// CONDITIONS FOR USER SEARCH BY MAIL:
        
        //Email address search term:
        if (isset($_POST['user_search']) 
            && $_POST['user_search']!='' 
            && $_POST['user_search']!=null
        )  
            $user_search_email = mysqli_real_escape_string($linkos,
                filter_var($_POST['user_search'], FILTER_VALIDATE_EMAIL));
                        
// CONDITIONS FOR EXCLUDE A USER:
        
        //email Address and admin note of user:
        if (isset($_POST['ex_email']) 
            && isset($_POST['ex_note']) 
            && isset($_POST['ex_email'])!=''
        ) { 
            $user_ex_email = mysqli_real_escape_string($linkos,
                filter_var($_POST['ex_email'], FILTER_VALIDATE_EMAIL)); 
            $user_ex_note = mysqli_real_escape_string($linkos,
                $_POST['ex_note']);
        }
                        
// CONDITIONS FOR EXCLUDE REMOVE:
        if (isset($_POST['id_to_remove_ex']) 
            && is_numeric($_POST['id_to_remove_ex']) 
            && $_POST['id_to_remove_ex']>0
        ) 
            $ex_rem_id = mysqli_real_escape_string($linkos,
                $_POST['id_to_remove_ex']);
                        
// CONDITIONS FOR BLOCKED REMOVE:
        if (isset($_POST['id_to_remove_blocked']) 
            && is_numeric($_POST['id_to_remove_blocked']) 
            && $_POST['id_to_remove_blocked']>0
            ) 
                $block_rem_id = mysqli_real_escape_string($linkos, 
                    $_POST['id_to_remove_blocked']);
                        
// CONDITIONS FOR LOG REMOVE:
        if (isset($_POST['id_to_remove_log']) 
            && is_numeric($_POST['id_to_remove_log']) 
            && $_POST['id_to_remove_log']>0
        ) 
            $log_id_rem = mysqli_real_escape_string($linkos, 
                $_POST['id_to_remove_log']);

// CONDITIONS FOR LOGs Clean Up:
        if (isset($_POST['intervalweeks']) 
            && is_numeric($_POST['intervalweeks']) 
            && $_POST['intervalweeks']>0
        ) 
            $intervalweeks = mysqli_real_escape_string($linkos, 
                $_POST['intervalweeks']);
                
/******************************************************************************/          
/****************************** PROCEDURES: ***********************************/
/******************************************************************************/		

    switch ($who_send) {
    
        // setting the default theme => dark , silver , custom:
        case 'theme_s':
            if (isset($_POST['theme_selected'])
                && (
                    $_POST['theme_selected'] == "darkStyle"
                    ||$_POST['theme_selected'] == "silverStyle"
                    ||$_POST['theme_selected'] == "custom"
                )
            ) {
                $theme_selected = "";
                if ($_POST['theme_selected'] == "custom") { $theme_selected = "custom"; }
                else if ( $_POST['theme_selected'] == "darkStyle" ) { $theme_selected = "dark"; }
                else { $theme_selected = "silver"; }
                                    
                $theme_selected = mysqli_real_escape_string($linkos,$theme_selected);
                $sqlstat = "UPDATE ".$table1.
                          " SET `theme`='".$theme_selected."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit;
                }	
            } else {
                die('die8'); 
            }
        break;
        
        //Set the brand name and store to DB
        case 'brand_s':
            if ($brand_name != false) {
                $sqlstat = "UPDATE ".$table1.
                          " SET `brand_name`='".$brand_name."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit;
                }							
            }
        break;
        
        //Set admin user name and password:
        case 'admin_change':
            if ($admin_user_name != false && $admin_user_pass != false) {
                $sqlstat = "UPDATE ".$table1.
                          " SET `db_user`='".$admin_user_name."', `db_password`='".$admin_user_pass."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit;
                }							
            }

        break;  
        
        //set the maximum file count allowed and store to DB
        case 'max_files_s':
            if ($max_files_count != false) {
                $sqlstat = "UPDATE ".$table1." SET ". 
                          "`maxfiles`='".$max_files_count."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else {
                    echo "OK!"; 
                    exit; 
                }							
            }            
        break;
        
        //set the maximum file size allowed and store to DB
        case 'max_size_s':
            if ($max_file_size != false) {
                $sqlstat = "UPDATE ".$table1." SET ".
                          "`maxfile_size`='".$max_file_size."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }							
            }							
        break;
                        
        //set the maximum recipients allowed and store to DB
        case 'max_rec_s':
            if ($max_rec != false) {
                $sqlstat = "UPDATE ".$table1." SET ". 
                           "`maxrecipients`='".$max_rec."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }							
            }							
        break;

        //set the Email account (server) to use and store to DB
        case 'max_email_s':
            if ($server_email != false) {
                $sqlstat = "UPDATE ".$table1." SET ". 
                           "`server_mail`='".$server_email."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!";
                    exit; 
                }							
            }							
        break;

        //set the the folder to store files and store to DB
        case 'folder_s':
            if ($files_folder != false) {
                $sqlstat= "UPDATE ".$table1." SET ".
                          "`files_folder`='".$files_folder."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }							
            }								
        break;

        //set the default message for recipient send and store to DB
        case 'mes_rec_s':
            if ($mes_rec_title != false && $mes_rec_body != false) {
                $sqlstat= "UPDATE ".$table1." SET ".
                          "`e_auto_title`='".$mes_rec_title."',". 
                          "`e_auto_body`='".$mes_rec_body."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }							
            }							
        break;	

        //set the default message for copy send and store to DB
        case 'mes_cop_s':
            if ($mes_cop_title != false && $mes_cop_body != false) {
                $sqlstat= "UPDATE ".$table1." SET ".
                          "`e_auto_title_copy`='".$mes_cop_title."',". 
                          "`e_auto_body_copy`='".$mes_cop_body."'  WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }							
            }	
        break;
                        
        //set the allowed file types for upload and store to DB
        case 'types_s':
            if ($accepted_types != false) {
                $sqlstat= "UPDATE ".$table1." SET ".
                          "`accept_types`='".$accepted_types."' WHERE `id`='1'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }							
            }	
        break;
        
    // search logs procedures by date:   
        case 'search_log':
            if ($s_date != false && $e_date != false) {
                $sqlstat= "SELECT * FROM ".$table2." WHERE ".
                          "`when_sent` BETWEEN TIMESTAMP('".$s_date."') ".
                          "AND DATE_ADD(TIMESTAMP('".$e_date."'),".
                          "INTERVAL 1 DAY)";
                $rs_result = mysqli_query($linkos, $sqlstat) or die ("die8");
                while ($idr = mysqli_fetch_array($rs_result)) {
                
                    $only_file_name = explode('_', $idr['filename']);
                    unset($only_file_name[0], $only_file_name[1]);
                    $only_file_name = preg_replace('/\s+/', '', 
                        implode('_',$only_file_name));
                
                    $row = array(
                        'id_log_search'             => $idr['id'],
                        'group_log_search'          => $idr['group'],
                        'filename_log_search'       => $idr['filename'],
                        'filename_only_log_search'  => $only_file_name,
                        'sender_log_search'         => $idr['sender'],
                        'to_log_search'             => $idr['to'],
                        'notify_log_search'         => $idr['notify']
                    );

                    if ($idr['notify'] === '1') {
                    
                        $sqlstat1="SELECT * FROM ".$table4." WHERE ".
                                  "`who`='".mysqli_real_escape_string($linkos,$idr['to'])."' ".
                                  "AND `notify_to`='".mysqli_real_escape_string($linkos,$idr['sender'])."' ".
                                  "AND `file_name`='".mysqli_real_escape_string($linkos,$idr['filename'])."'";
                        $rs_result1 = mysqli_query($linkos, $sqlstat1) or 
                            die ("die8".mysqli_error($linkos));
                    
                        $tempiii1 = 'prob';
                        $tempiii2 = 'prob';
                    
                        while ($idr1 = mysqli_fetch_array($rs_result1)) { 
                            $tempiii1 = $idr1['status_notify']; 
                            $tempiii2 = $idr1['when_notify']; 
                        }
                    
                        $row['status_notify_log_search']      = $tempiii1;
                        $row['status_notify_when_log_search'] = $tempiii2;
                        
                    } else {
                    
                        $row['status_notify_log_search']      = 'not_needed';
                        $row['status_notify_when_log_search'] = 'not_needed';
                    }
                
                    $row['copy_log_search']      = $idr['copy'];
                    $row['message_log_search']   = $idr['message'];
                    $row['when_sent_log_search'] = $idr['when_sent'];
                    $row['file_type_log_search'] = $idr['file_type'];
                    $row['file_size_log_search'] = $idr['file_size'];
                    $res_log_search[]            = $row;
                }
                
                // output results
                if(isset($res_log_search)) {
                    echo "OK!<p class='results_header'>Results: ".
                        (count($res_log_search))."</p>";
                    echo "<table class='storage_table4' cellpadding='0' cellspacing='0'>".
                         "<tr><td class='table_head1'>Sender</td><td class='table_head1'>".
                         "To</td><td class='table_head1'>Filename</td><td class='table_head1'>".
                         "Date</td><td class='table_head1' colspan='2'>More</td></tr>";
                    
                    foreach ($res_log_search as $key => $row2) {
                        
                        echo "<tr><td>".$row2['sender_log_search']."</td><td>".
                             $row2['to_log_search']."</td><td><span alt='".
                             $row2['filename_only_log_search']."' title='".
                             $row2['filename_only_log_search']."'>...".
                             substr($row2['filename_only_log_search'],-20).
                             "</span></td><td>".$row2['when_sent_log_search'].
                             "</td><td><div class='search_but expand_log' id='expand".
                             "_log' style='padding:3px 10px 1px 10px;'>".
                             "<img src='../img/glyphicons/png/glyphicons_029_notes_2.png'". 
                             " alt='expand details' title='expand details' /></div>".
                             "</td><td><div class='remove_but remove_log_search_but' id".
                             "='remove_log_search_but'><img src='../img/glyphicons/".
                             "png/glyphicons_207_remove_2.png' alt='delete this row' t".
                             "itle='delete this row' /></div><input type='hidden' id".
                             "='row_log_id' value='".$row2['id_log_search']."' />".
                             "</td></tr>";
                        
                        echo "<tr><td colspan='6' style='padding:0;'>".
                             "<div class='row_log_details' style='display:non".
                             "e; width:100%;'>".
                             "<p>&bull;&nbsp;File name: <span>".
                             $row2['filename_only_log_search']."</span></p>".
                             "<p>&bull;&nbsp;File name on server: <span>".
                             $row2['filename_log_search']."</span></p>".
                             "<p>&bull;&nbsp;File type: <span>".
                             $row2['file_type_log_search']."</span></p>".
                             "<p>&bull;&nbsp;File Size: <span>".
                             humanFileSize($row2['file_size_log_search']).
                             "</span></p>";
                        
                        if ($row2['notify_log_search']==='1') {
                            
                            echo "<p>&bull;&nbsp;Notification Request: <span>Yes".
                                 "</span><br />";
                            
                            if ($row2['status_notify_log_search'] === '1') {
                                echo "&bull;&nbsp;Notification Status: <span>Yes".
                                     "</span><br />&bull;&nbsp;Notification recieved:".
                                     "<span>".$row2['status_notify_when_log_search'].
                                     "</span>";
                            } else {
                               echo "&bull;&nbsp;Notification Status: <span>No</span>";
                            }
                        } else {
                            echo "<p>&bull;&nbsp;Notification Request: <span>No</span>";
                        } 
                        
                        echo "</p>";
                        
                        if ($row2['copy_log_search'] === '1') { 
                            echo "<p>&bull;&nbsp;Copy request: <span>Yes</span></p>";	
                        } else { 
                            echo "<p>&bull;&nbsp;Copy request: <span>No</span></p>";	
                        }
                        
                        echo "<p>&bull;&nbsp;User message: <span>".
                             $row2['message_log_search']."</span></p>";	
                        echo "</div></td></tr>";
                    }
                    
                    echo "</table>";
                    exit;
                    
                } else {
                    echo "OK!<p class='results_header'>No Results.</p>";
                    exit;
                }
                                
            }	
        break;
		
        // search logs by email address procedure:
        case 'search_mail':
            if ($user_search_email != false) {
            
                $sqlstat   = "SELECT * FROM ".$table2." WHERE `sender`='".
                            mysqli_real_escape_string($linkos,$user_search_email)."'";
                $rs_result = mysqli_query($linkos, $sqlstat) or die ("die8"); 
            
                while ($idr = mysqli_fetch_array($rs_result)) {
                
                    $row['id_log_search']           = $idr['id'];
                    $row['group_log_search']        = $idr['group'];
                    $row['filename_log_search']     = $idr['filename'];
                
                    $only_file_name = explode('_',$idr['filename']);
                    unset($only_file_name[0],$only_file_name[1]);
                    $only_file_name = preg_replace('/\s+/', '',
                        implode('_',$only_file_name));
                
                    $row['filename_only_log_search'] = $only_file_name;
                    $row['sender_log_search']        = $idr['sender'];
                    $row['to_log_search']            = $idr['to'];
                    $row['notify_log_search']        = $idr['notify'];
                
                    if ($idr['notify'] === '1') {
                        $sqlstat1 = "SELECT * FROM ".$table4." WHERE ".
                                    "`who`='".mysqli_real_escape_string($linkos,$idr['to'])."'".
                                    "AND `notify_to`='".mysqli_real_escape_string($linkos,$idr['sender'])."'". 
                                    "AND `file_name`='".mysqli_real_escape_string($linkos,$idr['filename'])."'";
                  
                        $rs_result1 = mysqli_query($linkos, $sqlstat1) 
                            or 
                                die ("die8".mysqli_error($linkos));
                            
                        $tempiii1 = 'prob';
                        $tempiii2 = 'prob';
                        
                        while ($idr1 = mysqli_fetch_array($rs_result1)) { 
                            
                            $tempiii1 = $idr1['status_notify']; 
                            $tempiii2 = $idr1['when_notify']; 
                        
                        }
                    
                        $row['status_notify_log_search']        = $tempiii1;
                        $row['status_notify_when_log_search']   = $tempiii2;
                 
                    } else {
                
                        $row['status_notify_log_search']        = 'not_needed';
                        $row['status_notify_when_log_search']   = 'not_needed';
                    }
               
                    $row['copy_log_search']      = $idr['copy'];
                    $row['message_log_search']   = $idr['message'];
                    $row['when_sent_log_search'] = $idr['when_sent'];
                    $row['file_type_log_search'] = $idr['file_type'];
                    $row['file_size_log_search'] = $idr['file_size'];
                    $res_log_search[]            = $row;
                }
            
                //output results
                if (isset($res_log_search)) {
                
                    echo "OK!<p class='results_header'>Results: ".
                         (count($res_log_search))."</p>";
                         
                    echo "<table class='storage_table4' cellpadding='0' cellspacing='0'>".
                         "<tr><td class='table_head1'>Sender</td>".
                         "<td class='table_head1'>To</td>".
                         "<td class='table_head1'>Filename</td>".
                         "<td class='table_head1'>Date</td>".
                         "<td class='table_head1' colspan='2'>More</td></tr>";
                          
                    foreach ($res_log_search as $key => $row2) {
                    
                        echo "<tr><td>".$row2['sender_log_search']."</td><td>".
                             $row2['to_log_search']."</td><td><span alt='".
                             $row2['filename_only_log_search']."' title='".
                             $row2['filename_only_log_search']."'>...".
                             substr($row2['filename_only_log_search'],-20).
                             "</span></td><td>".$row2['when_sent_log_search'].
                             "</td><td><div class='search_but expand_search' id=".
                             "'expand_search' style='padding:3px 10px 1px 10px;'>".
                             "<img src='../img/glyphicons/png/glyphicons_029_notes_2".
                             ".png' alt='expand details' title='expand details' />".
                             "</div></td><td><div class='remove_but remove_user_sea".
                             "rch_but' id='remove_user_search_but'>".
                             "<img src='../img/glyphicons/png/glyphicons_207_remove_2".
                             ".png' alt='delete this row' title='delete this row' />".
                             "</div><input type='hidden' id='row_search_id' value='".
                             $row2['id_log_search']."' /></td></tr>";
                             
                        echo "<tr><td colspan='6' style='padding:0;'><div class='row".
                             "_search_details' style='display:none; width:100%;'>".
                             "<p>&bull;&nbsp;File name: <span>".
                             $row2['filename_only_log_search']."</span></p>".
                             "<p>&bull;&nbsp;File name on server: <span>".
                             $row2['filename_log_search']."</span></p>".
                             "<p>&bull;&nbsp;File type: <span>".
                             $row2['file_type_log_search']."</span></p>".
                             "<p>&bull;&nbsp;File Size: <span>".
                             humanFileSize($row2['file_size_log_search']).
                             "</span></p>";
                              
                        if ($row2['notify_log_search'] === '1') {
                            
                            echo "<p>&bull;&nbsp;Notification Request: <span>Yes".
                                 "</span><br />";
                            
                            if ($row2['status_notify_log_search'] === '1') {
                                echo "&bull;&nbsp;Notification Status: <span>Yes".
                                     "</span><br />&bull;&nbsp;Notification recieved:".
                                     "<span>".$row2['status_notify_when_log_search'].
                                     "</span>";
                            } else {
                                echo "&bull;&nbsp;Notification Status: <span>No</span>";
                            }
                        } else {
                            echo "<p>&bull;&nbsp;Notification Request: <span>No</span>";
                        }
                        
                        echo "</p>";
                        
                        if ($row2['copy_log_search'] === '1') { 
                            echo "<p>&bull;&nbsp;Copy request: <span>Yes</span></p>";	
                        } else { 
                            echo "<p>&bull;&nbsp;Copy request: <span>No</span></p>";	
                        }
                        
                        echo "<p>&bull;&nbsp;User message: <span>".
                             $row2['message_log_search']."</span></p>";	
                        echo "</div></td></tr>";
                    }
                    
                    echo "</table>"; 
                    exit;
                    
                } else {
                    echo "OK!<p class='results_header'>No Results.</p>";
                    exit;
                }
            }	
        break;

        //Add an excluded user -> by Email address
        case 'ex_add':
            if ($user_ex_email != false && $user_ex_note != false) {
                $sqlstat = "INSERT INTO ".$table5." ".
                          "(`email_address`,`comment`,`when_added`) ". 
                          "VALUES ".
                          "('".$user_ex_email."','".$user_ex_note."',NOW())";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }		
            }
        break;
         
        //Remove an excluded user -> by Id 
        case 'ex_rem':
            if ($ex_rem_id != false) {
                $sqlstat = "DELETE FROM ".$table5." WHERE `id`='".$ex_rem_id."'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }		
            }					
        break;
 
        //Remove a blocked user -> by Id 
        case 'block_rem':
            if ($block_rem_id != false) {
                $sqlstat = "DELETE FROM ".$table3." WHERE `id`='".$block_rem_id."'";
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                } else { 
                    echo "OK!"; 
                    exit; 
                }		
            }							
        break;
        
        //Delete single logs + file:  
        case 'log_rem':
            if ($log_id_rem != false) {
            
                //get needed values to proccess:
                $sqlstat = "SELECT * FROM ".$table2." WHERE `id`='".$log_id_rem."'";
                $rs_result = mysqli_query($linkos, $sqlstat) or die ("die8"); 
                
                while ($idr = mysqli_fetch_array($rs_result)) {
                    $who_sent           = $idr['sender'];
                    $to_sent            = $idr['to'];
                    $file_server_name   = $idr['filename'];
                }
                                            
                //delete log:
                if (isset($file_server_name)) {
                    $sqlstat = "DELETE FROM ".$table2." WHERE `id`='".$log_id_rem."'";
                    if (!mysqli_real_query($linkos, $sqlstat)) { 
                        die('die8'); 
                    }								
                } else { 
                    die('die8'); 
                }
                                    
                //delete aproval:
                $sqlstat = "DELETE FROM ".$table4." WHERE 
                            `file_name`='".mysqli_real_escape_string($linkos, $file_server_name)."' 
                            AND `notify_to`='".mysqli_real_escape_string($linkos, $who_sent)."' 
                            AND `who`='".mysqli_real_escape_string($linkos, $to_sent)."'
                            ";
                
                if (!mysqli_real_query($linkos, $sqlstat)) { 
                    die('die8'); 
                }
                                        
                //delete from folder:
                $sqlstatus = "SELECT * FROM $table1 WHERE id=1";
                $rs_result = mysqli_query($linkos, $sqlstatus) or die ('die8'); 
                                                    
                while ($idr = mysqli_fetch_array($rs_result)) {
                    $files_path = $idr['files_folder'];
                }
                
                if (isset($files_path)) {
                     if (@unlink($files_path.$file_server_name)) {  } 
                }
                
                echo "OK!";
                exit;
            }
        
        break;
        
        //Manual Cleanup proccess by admin -> interval weeks:
        case 'cleanup_rem':
        
            if ($intervalweeks!=false) {
            
                //files to clean:
                $sqlstat = "SELECT * FROM ".$table2." WHERE ".
                           "`when_sent` < NOW() - INTERVAL ".$intervalweeks." WEEK";
                $rs_result = mysqli_query($linkos, $sqlstat) or die ("die8"); 
                
                $logids = array();
                while ($idr = mysqli_fetch_array($rs_result)) {
                    $logids[]             = $idr['id'];
                    $who_sent[]           = $idr['sender'];
                    $to_sent[]            = $idr['to'];
                    $file_server_name[]   = $idr['filename'];
                }
                
                    //folder path:
                    $sqlstatus = "SELECT * FROM ".$table1." WHERE `id`='1'";
                    $rs_result = mysqli_query($linkos, $sqlstatus) or die ('die8'); 
                                                            
                    while ($idr = mysqli_fetch_array($rs_result)) {
                        $files_path = $idr['files_folder'];
                    }  
                        
                //clean log,approval,folder files:
                    foreach ($logids as $keys => $idss) {  
                        
                        //delete from files log:
                        $sqlstat1 = "DELETE FROM ".$table2." WHERE `id`='".$idss."'";
                        if (!mysqli_real_query($linkos, $sqlstat1)) { die('die8'); }
                        
                        //delete from approval log:
                        $sqlstat2 = "DELETE FROM ".$table4." WHERE 
                                    `file_name`='".mysqli_real_escape_string($linkos, $file_server_name[$keys])."' 
                                    AND `notify_to`='".mysqli_real_escape_string($linkos, $who_sent[$keys])."' 
                                    AND `who`='".mysqli_real_escape_string($linkos, $to_sent[$keys])."'
                                    ";
                        if(!mysqli_real_query($linkos, $sqlstat2)) { 
                            die('die8'); 
                        } 
                        
                        //delete file:
                        if(isset($files_path)) {
                            if (@unlink($files_path.$file_server_name[$keys])) 
                            {  
                            
                            } 
                        }
                    }
                                    
                echo "OK!";
                exit;
            }
        
        break;
        
        default: die('die7');
    }
    /* END OF PROCEDURES */
    
    
    } else { // not loged in
        
        die('die5');
    
    }
    
    } else { // not loged in
      
      die('die5');
    
    }
/******************************************************************************/
/****************************** FUNCTIONS *************************************/
/******************************************************************************/
    
    //Function: convert file calculation types 
    function humanFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }
    
    //Function: calculate bytes:
    function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
    
    //Function: file type groups check:
    function returnStringTypes($group) {
        $fileTypes_string_group = array(
            'Text'		=>'.doc,.docx,.log,.msg,.odt,.pages,'.
                          '.rtf,.tex,.txt,.wpd,.wps',
            'Data'		=>'.csv,.dat,.gbr,.ged,.ibooks,.key,'.
                          '.keychain,.pps,.ppt,.pptx,.sdf,.tar'.
                          '.tax2012,.vcf,.xml',
            'Audio'		=>'.aif,.iff,.m3u,.m4a,.mid,.mp3,.mpa'.
                          '.ra,.wav,.wma',
            'Video'		=>'.3g2,.3gp,.asf,.asx,.avi,.flv,.m4v'.
                          '.mov,.mp4,.mpg,.rm,.srt,.swf,.vob'.
                          '.wmv',
            'eBook'		=>'.acsm,.aep,.apnx,.ava,.azw,.azw1'.
                          '.azw3,.azw4,.bkk,.bpnueb,.cbc,.ceb'.
                          '.dnl,.ebk,.edn,.epub,.etd,.fb2'.
                          '.html0,.htmlz,.htxt,.htz4,.htz5'.
                          '.koob,.lit,.lrf,.lrs,.lrx,.mart'.
                          '.mbp,.mobi,.ncx,.oeb,.opf,.pef'.
                          '.phl,.pml,.pmlz,.pobi,.prc,.qmk'.
                          '.rzb,.rzs,.tcr,.tk3,.tpz,.tr,.tr3'.
                          '.vbk,.webz,.ybk',
            'image3d'	=>'.3dm,.3ds,.max,.obj',
            'Raster'	=>'.bmp,.dds,.gif,.jpg,.jpeg,.png,.psd'.
                          '.pspimage,.tga,.thm,.tif,.tiff,.yuv',
            'Vector'	=>'.ai,.eps,.ps,.svg',			
            'Camera'	=>'.3fr,.ari,.arw,.bay,.cr2,.crw,.dcr'.
                          '.dng,.eip,.erf,.fff,.iiq,.k25,.kdc'.
                          '.mef,.mos,.mrw,.nef,.nrw,.orf,.pef'.
                          '.raf,.raw,.rw2,.rwl,.rwz,.sr2,.srf'.
                          '.srw,.x3f',
            'Layout'	 =>'.indd,.pct,.pdf',	
            'Spreadsheet'=>'.xlr,.xls,.xlsx',		
            'Database'	 =>'.accdb,.db,.dbf,.mdb,.pdb,.sql',		
            'Executable' =>'.apk,.app,.bat,.cgi,.com,.exe'.
                           '.gadget,.jar,.pif,.vb,.wsf',	
            'Game'		=>'.dem,.gam,.nes,.rom,.sav',	
            'CAD'		=>'.dwg,.dxf',	
            'GIS'		=>'.gpx,.kml,.kmz',
            'Web'		=>'.asp,.aspx,.cer,.cfm,.csr,.css'.
                          '.htm,.html,.js,.jsp,.php,.rss,.xhtml',	
            'Plugin'	=>'.crx,.plugin',
            'Font'		=>'.fnt,.fon,.otf,.ttf',
            'System'	=>'.cab,.cpl,.cur,.deskthemepack,.dll'.
                          '.dmp,.drv,.icns,.ico,.lnk,.sys',
            'Settings'	=>'.cfg,.ini,.prf',		
            'Encoded'	=>'.hqx,.mim,.uue',	
            'Compressed'=>'.7z,.cbr,.deb,.gz,.pkg,.rar,.rpm'.
                          '.sitx,.tar,.gz,.zip,.zipx',	
            'Disk'		=>'.bin,.cue,.dmg,.iso,.mdf,.toast,.vcd',	
            'Developer'	=>'.c,.class,.cpp,.cs,.dtd,.fla,.h'.
                          '.java,.lua,.m,.pl,.py,.sh,.sln'.
                          '.vcxproj,.xcodeproj',
            'Backup'	=>'.bak,.tmp',
            'Misc'		=>'.crdownload,.ics,.msi,.part,.torrent'
        );
        
        if (array_key_exists($group, $fileTypes_string_group)) { 
            return $fileTypes_string_group[$group]; 
        } else { 
            return ""; 
        }
    }
    
    //Function: validate correct date format:
    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

?>