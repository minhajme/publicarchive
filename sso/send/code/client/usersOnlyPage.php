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

if (!isset($uname)||$uname=='nouser') {
/**************************** LOGIN REQUIRED **********************************/

?>
<!DOCTYPE html>
<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta charset="UTF-8" />
    <META http-equiv="Pragma" CONTENT="no-cache">
    <META http-equiv="Expires" CONTENT="-1">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $brand; ?> - File Sharing system</title>
    
    <script language="javascript" src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script language="javascript" src="js/jquery.browser.min.js" type="text/javascript"></script>	
    <script language="javascript" src="js/TextCustom.js" type="text/javascript"></script>    
    <link href='http://fonts.googleapis.com/css?family=Prosto+One' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/<?php echo $themeUse; ?>.css" type="text/css" media="screen" />	

<!--[if IE 9]>
  <style type="text/css">
    div {
       filter: none;
    }
  </style>
<![endif]-->	
</head>
<body>
<div class='outerConatainer'  style="margin:0 auto;">
<form action='index.php' method='POST' class='hiddder'>
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
    <tr>
        <td colspan='3'><p class='admin_head1'>Please Login:</p></td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div2'></div></td>
    </tr>
    <tr>
        <td colspan='3'>
                        <p class='sec_form'>User name:</p>
                        <input name='access_user_name' type='text' />
                        <br />
                        <p class='sec_form' style='margin-top:5px;'>Password:</p>
                        <input name='access_user_password' type='password' style='margin-bottom:8px;' />
                        <?php echo gettokenfield(); ?>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3' class='button_td'><div class='button_td'><img src='img/glyphicons/png/glyphicons_386_log_in.png' class='envelop' width='18px' height='16px' /><input class='css3button gradient' type='submit' id='but1in' value='Log In&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' /></div></td>
    </tr>
</table>
</form>
</div>
</body>
</html>
<?php

} else { 

/**************************** LOGED IN USER ***********************************/

    $userName = mysqli_real_escape_string($linkos, $uname); 
    //get user settings: 
    $sqlstatus = "SELECT * FROM ".$table6." WHERE `username`='".$userName."'";
    $rs_result = mysqli_query($linkos, $sqlstatus) 
        or 
            die ( "Some thing went terribly wrong contact Admin ~ Code: 230983" ); 
       
            while ($idr=mysqli_fetch_array($rs_result)) {
                $maxfiles 		= $idr['maxfiles'];
                $maxrecipients 	= $idr['maxrec'];
                $maxsize		= $idr['maxsize'];
                $userEmail		= $idr['usermail'];
                $userFullName	= $idr['fullname'];
            }
            
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
    <script type="text/javascript">
            var maxfiles 			= <?php 	echo $maxfiles; ?>;
            var maxrecipients 		= <?php 	echo $maxrecipients; ?>;
            var accept_file_types 	= "<?php 	echo $accept_types; ?>";
    </script>
    <script language="javascript" src="js/TextCustom.js" type="text/javascript"></script>    
    <script language="javascript" src="js/usersFunc.js" type="text/javascript"></script>
    
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
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxsize; ?>" />
<?php echo gettokenfield(); ?>

<div class="outerConatainer"  grab="top2-"  style="margin:0 auto;">

<table border='0'>
    <tr>
        <td colspan='3'>
            <div alt='Log Out' title='Log Out' class='css3button logoutbutton' id='logMeout'><div></div></div>
            <div class='logoTakeThat'><a href='index.php'><img src='img/takethatlogo.png' style="border:0" /></a></div>
            <div class='logoBrand'><span class='Brand' id='Brand'><?php echo $brand; ?></span></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3'><p class='sec_form'>File/s:&nbsp;&nbsp;<span class='sizeclac smaller' id='sizeclac'></span></p></td>
    </tr>
    <tr>
        <td>
            <input class='file' type='file' name='file1' id='file1' accept='.jpeg,.gif,.jpg,.png' style='display:none;' />
            
            <table border='0' cellpadding='0' cellspacing='0' style='border:0; padding:0; margin:0;'>
                <tr>
                    <td style='width:85px;'>
                        <div style='position:relative; margin:0; padding:0; border-radius:29px;'>
                            <button type='button' class='css4button gradient' grab='top1-' >&nbsp;&nbsp;&nbsp;Browse</button>
                        </div>
                    </td>
                    <td style='padding-right:5px;'>
                        <input class='tempShowfiler' type='text' name='tempShowfiler' id='tempShowfiler' placeholder="select a file" readonly />
                    </td>
                </tr>
            </table>
        </td>
        <td width='30px'>
            <div class='clear_file' id='clear_file'>X</div>
        </td>
        <td  width='40px'>
            <div id='showerror1' class='showerror' style='display:none;'>
                <img src='img/glyphicons/png/1error.png' />
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3' style='position:relative;'>
            <div class='add_file' id='add_file' style="display:inline-block;"></div>
            <div style="position:relative; display:inline-block; left:20%;">
                <div class='maximum_reach' style='display:none;'>That's the maximum files allowed.</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div2'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <p class='sec_form'>Recipient/s:</p>
        </td>
    </tr>
    <tr>
        <td>
            <input class='recipients' type='text' name='sendto1' id='sendto1' placeholder="recipient e-mail address" />
        </td>
        <td>
            <div class='clear_to' id='clear_to'>X</div>
        </td>
        <td>
            <div id='showerror_to1' class='showerror' style='display:none;'>
                <img src='img/glyphicons/png/1error.png' />
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3' style='position:relative;'>
            <div class='add_to' id='add_to' style="display:inline-block;"></div>
            <div style="position:relative; display:inline-block; left:10%;">
                <div class='maximum_reach' style='display:none;'>That's the maximum recipients allowed.</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div2'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <p class='sec_form'>From:</p>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='userAccountDisplay'>
                <table>
                    <tr>
                        <td class='colorMe1'>Email:</td>
                        <td class='colorMe2'><?php echo $userEmail; ?></td>
                    </tr> 
                    <tr>
                        <td class='colorMe1'>Name:</td>  
                        <td class='colorMe2'><?php echo $userFullName; ?></td>
                    </tr> 
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div2'></div>
        </td>
    </tr>
    <tr>
        <td>
            <p class='sec_form'>Notify me when delivered:</p>
        </td>
        <td colspan='2' style='padding:0px 0px 4px 7px;'>
            <input  class="css-checkbox" type='checkbox' name='checkbox_notify' id='checkbox_notify' CHECKED />
            <label for="checkbox_notify" class="css-label"></label>
        </td>
    </tr>
    <tr>
        <td>
            <p class='sec_form'>Send me a copy:</p>
        </td>
        <td colspan='2' style='padding:0px 0px 4px 7px;'>
            <input  class="css-checkbox" type='checkbox' name='checkbox_copy' id='checkbox_copy' />
            <label for="checkbox_copy" class="css-label"></label>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div2'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <p class='sec_form'>Text message: <span class='smaller'>(optional)</span></p>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <textarea class='css-textarea' name='message' id='message' placeholder="text message"></textarea>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3' id='prog_mes'>
                        <div class='gradient' id='conMessageReturn' style='display:none;'></div>
                        <div class='gradient' id='conMessageDone' style='display:none;'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3' class='button_td'>
            <div class='button_td'>
                <img src='img/glyphicons/png/glyphicons_421_send.png' class='envelop' />
                <input class='css3button gradient' type='button' id='but1sub' value='Send&nbsp;&nbsp;&nbsp;'  grab='top3-' />
            </div>
        </td>
    </tr>
</table>
</div>
</form>
</body>
</html>

<?php
}
    
?>