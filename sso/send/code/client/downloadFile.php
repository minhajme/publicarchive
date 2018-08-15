<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2.1
// Creation Date: 12/09/2013
// Updated To V.2.1 : 05/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/
?>
<!DOCTYPE html>
<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta charset="UTF-8" />
    <META http-equiv="Pragma" CONTENT="no-cache">
    <META http-equiv="Expires" CONTENT="-1">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>
            <?php echo $brand; ?> - File Sharing system
    </title>
    
    <script language="javascript" src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script language="javascript" src="js/jquery.browser.min.js" type="text/javascript"></script>	
    <script language="javascript" src="js/guestsFunc.js" type="text/javascript"></script>
    
    <link href='http://fonts.googleapis.com/css?family=Prosto+One' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/<?php echo $themeUse; ?>.css" type="text/css" media="screen" />
    
<!--[if IE 9]>
<style type="text/css">  
    div {
       filter: none;
    }
input[type="text"],
input[type="password"],
.css-textarea{ background-color: #FAFAFA !important; filter: none; }
.css3button,.css4button { filter: none; }
</style>
<![endif]-->
</head>

<body>

<br />

<form method="post" action="code/client/filesend.php" enctype="multipart/form-data" id='takethatform'>
<input type='hidden' name='modeEQ' id='modeEQ' value='<?php echo $users_mode; ?>' />
<?php echo gettokenfield(); ?>

<div class="outerConatainer"  grab="top2-"  style="margin:0 auto;">

<table border='0'>
    <tr>
        <td colspan='3'>
            <div class='logoTakeThat'><a href='index.php'><img src='img/takethatlogo.png' style="border:0" /></a></div>
            <div class='logoBrand'><span class='Brand' id='Brand'><?php echo $brand; ?></span></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
<?php
//download pannel

            //prepare link:
            if (isset($_GET['get'])) {
            
                $secret_file = mysqli_real_escape_string($linkos,$_GET['get']);
                $sqlstatus1="SELECT * FROM ".$table4." WHERE `secret`='".$secret_file."'";
                $rs_result1 = mysqli_query($linkos, $sqlstatus1) 
                    or 
                        die ("2. currently not available."); 
                        
                        while ($idr1=mysqli_fetch_array($rs_result1)) {
                            $id_file 		= $idr1['id'];
                            $file_name 		= $idr1['file_name'];
                            $notify_to 		= $idr1['notify_to'];
                            $do_notify 		= $idr1['do_notify'];
                            $status_notify 	= $idr1['status_notify'];
                            $when_notify 	= $idr1['when_notify'];
                            $who_down 		= $idr1['who'];
                        }

                if(isset($id_file)) {
                
                    //file name:
                    $file_only_name = explode('_',$file_name);
                    unset($file_only_name[0],$file_only_name[1]);
                    $file_only_name = implode('_',$file_only_name);
                        
                    //files path:
                    $files_path1 = createUrlFromAbsolutePath($files_path);

                    //link to file:
                    $link_file = "<a class='link_tofile' href='http://".
                                 $files_path1.$file_name."' download='".
                                 $file_name."' ><img src='img/glyphicons".
                                 "/png/1down.png' class='down111' />".
                                 $file_only_name." - Size: ".
                                 humanFileSize(filesize($files_path.$file_name)).
                                 "</a>"; 
                        
                    //output
                    echo "<tr><td colspan='3'>";
                    echo $link_file;				
                    echo "</td></tr>";
                    echo "<tr><td colspan='3'><div class='border_div'></div>";
                    echo "</td></tr>";
                    echo "<tr><td colspan='3' class='button_td'><div class='button_td'>".
                         "<img src='img/glyphicons/png/glyphicons_020_home.png' ".
                         "class='return_home' width='18px' height='18px' /><input ".
                         "class='home_but1 css3button gradient' type='button' ".
                         "id='but1home' value='' /></div></td></tr>";
                    
                    //send notification if its requested and mark as notified:
                    if ($do_notify) {
                        if(!$status_notify) {
                            if(!sendMailTakeThatNotificationApprove($notify_to,
                                $who_down,$file_name,$servermail,$brand,
                                $secret_file
                            )) {  
                            } else {
                                //update confirmation sent:
                                $sqlstat= "UPDATE ".$table4." SET ".
                                                        "`status_notify`='1',".
                                                        "`when_notify`=NOW() ".
                                                        "WHERE id='".$id_file."'";
                                if(!mysqli_real_query($linkos, $sqlstat)) { 
                                    die(); 
                                }
                            }
                        }
                    }
                } else {
                    echo "<tr><td colspan='3'>";
                    echo "<p class='no_file'><img src='img/glyphicons/png/1error.".
                         "png' class='down111' />The requested file\s is not availa".
                         "ble.</p>";			
                    echo "</td></tr>";
                    echo "<tr><td colspan='3'><div class='border_div'></div></td></tr>";
                    echo "<tr><td colspan='3' class='button_td'><div class='butto".
                         "n_td'><img src='img/glyphicons/png/glyphicons_020_home.p".
                         "ng' class='return_home' /><input class='home_but1 css3bu".
                         "tton gradient' type='button' id='but1home' value='' />".
                         "</div></td></tr>";			
                }
            } elseif (isset($_GET['gr'])) {
                $group_file = mysqli_real_escape_string($linkos,$_GET['gr']);
                $sqlstatus2="SELECT id,file_size,filename FROM ".$table2." WHERE `group`='".$group_file."' GROUP BY filename";
                $rs_result2 = mysqli_query($linkos, $sqlstatus2) 
                    or 
                        die ( "3. currently not available." ); 
                        
                while ($idr2 = mysqli_fetch_array($rs_result2)) {
                    $id[] = $idr2['id'];
                    $file_name[] = $idr2['filename'];
                    $file_size[] = $idr2['file_size'];
                }
                
                //files path:
                $files_path1 = createUrlFromAbsolutePath($files_path);
                    
                if (isset($id)){	
                    $fileList = '<ul class="links_group">';
                    foreach($file_name as $key => $value) {
                        $file_only_name = explode('_', $value);
                        unset($file_only_name[0], $file_only_name[1]);
                        $file_only_name=implode('_',$file_only_name);				
                        $fileList .="<li><a href='http://".$files_path1.$value.
                                    "' download='".$value."' >".$file_only_name.
                                    " - Size: ".humanFileSize($file_size[$key]).
                                    "</a></li>";	
                    }
                    $fileList .= '</ul>';
                        
                    echo "<tr><td colspan='3'>";
                    echo $fileList;				
                    echo "</td></tr>";
                    echo "<tr><td colspan='3'><div class='border_div'></div></td></tr>";
                    echo "<tr><td colspan='3' class='button_td'><div class='but".
                         "ton_td'><img src='img/glyphicons/png/glyphicons_020_".
                         "home.png' class='return_home' /><input class='home_bu".
                         "t1 css3button gradient' type='button' id='but1home' v".
                         "alue='' /></div></td></tr>";
                } else {
                    echo "<tr><td colspan='3'>";
                    echo "<p class='no_file'><img src='img/glyphicons/png/1erro".
                         "r.png' width='18px' height='18px' />The requested file\s ".
                         "is not available.</p>";			
                    echo "</td></tr>";
                    echo "<tr><td colspan='3'><div class='border_div'></div>".
                         "</td></tr>";
                    echo "<tr><td colspan='3' class='button_td'><div class='but".
                         "ton_td'><img src='img/glyphicons/png/glyphicons_020_".
                         "home.png' class='return_home' /><input class='home_b".
                         "ut1 css3button gradient' type='button' id='but1home'".
                         "value='' /></div></td></tr>";						
            }
        } else {
            echo "<tr><td colspan='3'>";
            echo "<p class='no_file'><img src='img/glyphicons/png/1error.png' ". 
                 "width='18px' height='18px' />The requested file\s is not ". 
                 "available.</p>";			
            echo "</td></tr>";		
            echo "<tr><td colspan='3'><div class='border_div'></div></td></tr>";
            echo "<tr><td colspan='3' class='button_td'><div class='button_td'>".
                 "<img src='img/glyphicons/png/glyphicons_020_home.png' ".
                 "class='return_home' /><input class='home_but1 css3button ".
                 "gradient' type='button' id='but1home' value='' /></div></td></tr>";
        }
?>

</table>
</div>
</form>
</body>
</html>