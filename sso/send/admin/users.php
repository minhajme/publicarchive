<?php
    error_reporting(0);
    @ini_set('display_errors', 'off');   
    session_start();
    define('DS', DIRECTORY_SEPARATOR);
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2
// Creation Date: 12/09/2013
// Updated To V.2 : 01/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/


/* requird core files */
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

                // EXECUTE:				
                switch ($who_send) {
                    case 'users_mode':
                            
                            if (isset($_POST['users_mode'])) 
                            {
                                $users_mode =  mysqli_real_escape_string($linkos, $_POST['users_mode']);
                                
                                $sql229 = "UPDATE ".$table1." SET 
                                                    `users_mode`  = '".$users_mode."'
                                                    WHERE `id`='1'"; 
                                
                                $rs_result229 = mysqli_query ($linkos, $sql229) or die ("prob229"); 
                                
                                    echo "OK!";
                                    exit;
                                    
                            }  else {
                            
                                    echo "prob229!";
                                    exit;
                                    
                            }
                        break;                     
                    case 'update_guest':
                            
                            if (isset($_POST['update_guest_maxSize']) &&
                                isset($_POST['update_guest_maxFiles']) &&
                                isset($_POST['update_guest_maxRec'])
                            ) {
                                $update_guest_maxSize =  mysqli_real_escape_string($linkos, $_POST['update_guest_maxSize']);
                                $update_guest_maxFiles =  mysqli_real_escape_string($linkos, $_POST['update_guest_maxFiles']);
                                $update_guest_maxRec =  mysqli_real_escape_string($linkos, $_POST['update_guest_maxRec']);
                                
                                $sql219 = "UPDATE ".$table7." SET 
                                                    `maxfiles`  = '".$update_guest_maxFiles."',
                                                    `maxsize`   = '".$update_guest_maxSize."',
                                                    `maxrec`    = '".$update_guest_maxRec."'
                                                    WHERE `id`='1'"; 
                                
                                $rs_result219 = mysqli_query ($linkos, $sql219) or die ("prob219"); 
                                
                                    echo "OK!";
                                    exit;
                                    
                            }  else {
                            
                                    echo "prob291!";
                                    exit;
                                    
                            }
                        break;                   
                    case 'delete_user':
                            
                            if (isset($_POST['rowId'])) 
                            {
                                $idDelete           =  mysqli_real_escape_string($linkos, $_POST['rowId']);

                                $sql29 = "DELETE FROM $table6 WHERE `id`='$idDelete'"; 
                                
                                $rs_result29 = mysqli_query ($linkos, $sql29) or die ("prob29"); 
                                
                                    echo "OK!";
                                    exit;
                                    
                            }  else {
                            
                                    echo "prob291!";
                                    exit;
                                    
                            }
                        break;                
                    case 'add_user_new':
                            
                            if (isset($_POST['new_userName'])&& 
                                isset($_POST['new_userPassword'])&& 
                                isset($_POST['new_fullName'])&& 
                                isset($_POST['new_userMail'])&&
                                isset($_POST['new_maxSize'])&&                                
                                isset($_POST['new_maxFiles'])&&
                                isset($_POST['new_maxRec'])                               
                            ) {
                                $new_userName       =  mysqli_real_escape_string($linkos, $_POST['new_userName']);
                                $new_userPassword   =  mysqli_real_escape_string($linkos, md5($_POST['new_userPassword']));
                                $new_fullName       =  mysqli_real_escape_string($linkos, $_POST['new_fullName']);
                                $new_userMail       =  mysqli_real_escape_string($linkos, $_POST['new_userMail']);
                                $new_maxSize        =  mysqli_real_escape_string($linkos, $_POST['new_maxSize']);
                                $new_maxFiles       =  mysqli_real_escape_string($linkos, $_POST['new_maxFiles']);
                                $new_maxRec         =  mysqli_real_escape_string($linkos, $_POST['new_maxRec']);
                                
                                $users_data = array();

                                $sql9 = "SELECT `username` FROM $table6"; 
                                $rs_result9 = mysqli_query ($linkos, $sql9) or die ("prob1"); 
                                while ($idr_check_users = mysqli_fetch_array($rs_result9)) {
                                
                                    $users_data[] = $idr_check_users['username'];
                                
                                }
                                
                                if (!in_array($new_userName, $users_data)) {
                                    $sqlstat= "INSERT INTO ".$table6." (".
                                               "`username`,`password`,`fullname`,".
                                               "`maxfiles`,`maxsize`,`maxrec`,`usermail`,".
                                               "`added`,`active`".
                                               ") VALUES (".
                                                    "'".$new_userName."',".
                                                    "'".$new_userPassword."',".
                                                    "'".$new_fullName."',".
                                                    "'".$new_maxFiles."',".
                                                    "'".$new_maxSize."',".
                                                    "'".$new_maxRec."',".
                                                    "'".$new_userMail."',".
                                                    "NOW(),".
                                                    "'yes')";
                                    if (!mysqli_real_query($linkos, $sqlstat)) { 
                                        die('die8'); 
                                    } else { 
                                        echo "OK!"; 
                                        exit;
                                    }	
                                
                                } else {
                                
                                    echo "Taken!";
                                    exit;
                                
                                }
                            }
                        break;
                    case 'update_user':
                    
                            if (isset($_POST['rowId'])&& 
                                isset($_POST['update_userName'])&& 
                                isset($_POST['update_userPassword'])&& 
                                isset($_POST['update_fullName'])&&
                                isset($_POST['update_userMail'])&&                                
                                isset($_POST['update_maxSize'])&&                                
                                isset($_POST['update_maxFiles'])&&                                
                                isset($_POST['update_maxRec'])&&
                                isset($_POST['update_active'])
                            ) {
                                $rowId                      =  mysqli_real_escape_string($linkos, $_POST['rowId']);
                                
                                if ($_POST['update_userPassword'] != '')
                                    $update_userPassword =  mysqli_real_escape_string($linkos, md5($_POST['update_userPassword']));
                                else 
                                    $update_userPassword = '';
                                    
                                $update_userName            =  mysqli_real_escape_string($linkos, $_POST['update_userName']);
                                $update_fullName            =  mysqli_real_escape_string($linkos, $_POST['update_fullName']);
                                $update_userMail            =  mysqli_real_escape_string($linkos, $_POST['update_userMail']);
                                $update_maxSize             =  mysqli_real_escape_string($linkos, $_POST['update_maxSize']);
                                $update_maxFiles            =  mysqli_real_escape_string($linkos, $_POST['update_maxFiles']);
                                $update_maxRec              =  mysqli_real_escape_string($linkos, $_POST['update_maxRec']);
                                $update_active              =  mysqli_real_escape_string($linkos, $_POST['update_active']);
                                
                                $users_data = array();
                                
                                $sql9 = "SELECT `id`,`username`,`password` FROM $table6"; 
                                $rs_result9 = mysqli_query ($linkos, $sql9) or die ("prob1"); 
                                while ($idr_check_users = mysqli_fetch_array($rs_result9)) {
                                    $users_data[$idr_check_users['id']] = $idr_check_users['username'];
                                    $users_pass[$idr_check_users['id']] = $idr_check_users['password'];
                                }
                                
                                if ($users_data[$rowId] == $update_userName && $update_userPassword == '') {
                                
                                    $sqlstat= "UPDATE ".$table6." SET 
                                                    `fullname`  = '".$update_fullName."',
                                                    `maxfiles`  = '".$update_maxFiles."',
                                                    `maxsize`   = '".$update_maxSize."',
                                                    `maxrec`    = '".$update_maxRec."',
                                                    `usermail`  = '".$update_userMail."',
                                                    `added`     = NOW(),
                                                    `active`    = '".$update_active."'
                                              WHERE id='".$rowId."'";
                                              
                                    if (!mysqli_real_query($linkos, $sqlstat)) { 
                                        die('die8'); 
                                    } else { 
                                        echo "OK!"; 
                                        exit; 
                                    }	
                                    
                                }
                                else if ($users_data[$rowId] == $update_userName && $update_userPassword != '') {
                                
                                    $sqlstat= "UPDATE ".$table6." SET 
                                                    `password`  = '".$update_userPassword."',
                                                    `fullname`  = '".$update_fullName."',
                                                    `maxfiles`  = '".$update_maxFiles."',
                                                    `maxsize`   = '".$update_maxSize."',
                                                    `maxrec`    = '".$update_maxRec."',
                                                    `usermail`  = '".$update_userMail."',
                                                    `added`     = NOW(),
                                                    `active`    = '".$update_active."'
                                              WHERE id='".$rowId."'";
                                              
                                    if (!mysqli_real_query($linkos, $sqlstat)) { 
                                        die('die8'); 
                                    } else { 
                                        echo "OK!"; 
                                        exit; 
                                    }
	                                
                                }
                                else if ($users_data[$rowId] != $update_userName && $update_userPassword == '') {
                                    
                                    unset($users_data[$rowId]);
                                    if (!in_array($update_userName, $users_data)) {

                                        $sqlstat= "UPDATE ".$table6." SET 
                                                        `username`  = '".$update_userName."',
                                                        `fullname`  = '".$update_fullName."',
                                                        `maxfiles`  = '".$update_maxFiles."',
                                                        `maxsize`   = '".$update_maxSize."',
                                                        `maxrec`    = '".$update_maxRec."',
                                                        `usermail`  = '".$update_userMail."',
                                                        `added`     = NOW(),
                                                        `active`    = '".$update_active."'
                                                  WHERE id='".$rowId."'";
                                                  
                                        if (!mysqli_real_query($linkos, $sqlstat)) { 
                                            die('die8'); 
                                        } else { 
                                            echo "OK!"; 
                                            exit; 
                                        }
                                    
                                    } else {
                                    
                                        echo "Taken!";
                                        exit;
                                    
                                    }
                                }
                                else if ($users_data[$rowId] != $update_userName && $update_userPassword != '') {
                                
                                    unset($users_data[$rowId]);
                                    if (!in_array($update_userName, $users_data)) {

                                        $sqlstat= "UPDATE ".$table6." SET 
                                                        `username`  = '".$update_userName."',
                                                        `password`  = '".$update_userPassword."',
                                                        `fullname`  = '".$update_fullName."',
                                                        `maxfiles`  = '".$update_maxFiles."',
                                                        `maxsize`   = '".$update_maxSize."',
                                                        `maxrec`    = '".$update_maxRec."',
                                                        `usermail`  = '".$update_userMail."',
                                                        `added`     = NOW(),
                                                        `active`    = '".$update_active."'
                                                  WHERE id='".$rowId."'";
                                                  
                                        if (!mysqli_real_query($linkos, $sqlstat)) { 
                                            die('die8'); 
                                        } else { 
                                            echo "OK!"; 
                                            exit; 
                                        }
                                    
                                    } else {
                                    
                                        echo "Taken!";
                                        exit;
                                    
                                    }                                
                                }
                                else {
                                    die('no action');
                                }
                        }
                        break;
                        
                    default: break;
                }
        
    } else {
        
        die('die5');
    
    }
    
    } else {
      
      die('die5');
    
    }
      
/*functions*/
    function humanFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }
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
            'Raster'	=>'.bmp,.dds,.gif,.jpg,.png,.psd'.
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

    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

?>