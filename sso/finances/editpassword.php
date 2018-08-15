<?php
include 'functions.php';
checklogin();
$user=user;	
$id=id;
$FormMessage="";
if(isset($_POST['cancel']))
{
	header('location:user.php');
	die();
}
if(isset($_POST['old'],$_POST['new1'],$_POST['new2'])){
	$Query="SELECT password,salt FROM `users` WHERE id='".$id."' AND name='".$user."'";//We get password and salt for this user.
	include("db.php");//include db connection data
	$db_handle = mysql_connect($server, $user_name, $password);
	$db_found = mysql_select_db($database,$db_handle );
	$result=mysql_query($Query);
	$data = mysql_fetch_assoc($result);
	$db_password=$data['password'];
	$salt=$data['salt'];
	$oldpassword=hash("sha512",$_POST['old'].$salt);//We hash the old password sent from the form with user's salt 
	if($oldpassword!=$db_password)//we check if this old password is correct,
	{
		$FormMessage="Wrong password";
	}
	else
	{
		if($_POST['new1']!=$_POST['new2'])//If both new passwords are the same,
		{
			die("New passwords do not match!");
		}
		else
		{			
			$newpassword=hash("sha512",$_POST['new1'].$salt);//Hash the new password with salt code
			$Query="UPDATE `users` SET `password`='".$newpassword."' where id='".$id."' and name='".$user."'";//Update the user
			mysql_query($Query);	
			Logout();//Logout, User will login with his new password
			header('location:login.php?account=changed');
		}
	}
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Password</title>
<script src="js/checkPassword.js"></script>
 <link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<script>
$(function() {
$( "#date" ).datepicker();
});
</script>
</head>
<body>
<div id="bg"></div>
<BR><BR><BR><BR><BR><BR><BR>
<div id="content" style="max-width:320px">
<form id="form1" name="form1" method="post" action="">
  <label for="old" style="width:120px;">Old Password:</label>
  <input name="old" type="password" class="TextBox" id="old" maxlength="40" onFocus="fadeout()"/><br>
  <label for="new1" style="width:120px;">New Password:</label>
  <input name="new1" type="password" class="TextBox" id="new1" maxlength="40" onFocus="fadeout()" /><br>
  <label for="new2" style="width:120px;">New Password:</label>
  <input name="new2" type="password" class="TextBox" id="new2" maxlength="40" onFocus="fadeout()" /><br>
  <label style="width:120px;"> </label>
  <div id="FormButtons"><input name="Submit" type="submit" value="Save" class="Button save"  onclick="return CheckPass()" >
  <input class="Button cancel" type="submit" value="Cancel" name="cancel"></input><br></div>
  <span id="FormMessages" style="opacity: 1;"><?php echo $FormMessage ?></span>
</form>
</div>
</body>
</html>