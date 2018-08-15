<?php
include 'functions.php';
Logout2();//logout if already is a login
$FormMessage="";//this will be printed below the form
$passwordchanged=false;
	include("db.php");//include the database connection info
	$db_handle = mysql_connect($server, $user_name, $password);
	$db_found = mysql_select_db($database,$db_handle );
	if(isset($_GET['l'])){//if there is 'l' variable in the url,
		$link=hash("sha512",$_GET['l']);//hash it
		$Query="select * from password_reset where link='".$link."' limit 1";//select the record
		$result=mysql_query($Query);
		if(mysql_num_rows($result)==0)//if this doesn't exist (this link is not correct) go back
			header('location:resetpassword.php');
		$fields = mysql_fetch_assoc($result);
		$name=$fields['user'];
		$id=$fields['user_id'];
		$db_date=$fields['date'];
		
		///check the request time
		$now = new DateTime();
		$cdate=new DateTime($db_date);
		$time = $cdate->diff($now);//find the data difference
		$h=$time->h;//put the difference hour to $h
		$d=$time->d;//put the days to $d
		if($d>1||$h>23)//if this time is larger than 23 hours, or it's more than a day, so the request has expired.
		{
			$Query="DELETE FROM `password_reset` WHERE link='".$link."'";//delete this request
			mysql_query($Query);		
			header('location:login.php');
		}
		if(isset($_POST['p1'],$_POST['p2'])){
			$p1=$_POST['p1'];
			$p2=$_POST['p2'];
			if($p1==$p2)//check if 2 passwords are the same.
			{
				
				$Query="select salt from users where id='".$id."' and name='".$name."' limit 1";//select the user
				$result=mysql_query($Query);
				$fields = mysql_fetch_assoc($result);
				$salt=$fields['salt'];
				$newpassword=hash("sha512",$p1.$salt);//hash the password with user's unique salt
				$Query="UPDATE `users` SET `password`='".$newpassword."' where id='".$id."' and name='".$name."'";
				mysql_query($Query);//put the new password to db
				$Query="DELETE FROM `password_reset` WHERE link='".$link."'";//delete this request
				mysql_query($Query);		
				header('location:login.php?account=changed');
			}
			else
			{
				$FormMessage="Passwords do not match";
			}
			
		}
}
else
header('location:login.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<script src="js/checkChangePassword.js"></script>
<script src="vi.php"></script>
<title>Password Reset</title>
</head>
<body>
<div id="bg"></div>
<BR><BR><BR><BR><BR>
<div id="content" style="max-width:250px">
<p>Enter your new password:</p>
<form name="form1" method="post" action="">
  <label for="p1">Pasword:</label>
  <input type="password" name="p1" id="p1"class="TextBox" onFocus="fadeout()"><br>
  <label for="p2">Pasword:</label>
  <input type="password" name="p2" id="p2" class="TextBox" onFocus="fadeout()"><br>
  <label> </label><input name="Submit"  type="submit" value="Reset my password" class="Button" style="width:150px" onClick="return CheckPass()">
  <br><span id="FormMessages" style="opacity:100"><?php echo $FormMessage ?></span><br>
</form>
</div>
</body>
</html>

