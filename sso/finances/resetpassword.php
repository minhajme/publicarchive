<?php
include 'functions.php';
Logout2();//logout if already is a login
$error="";//this will be printed below the form
include("db.php");//include database connection data
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database,$db_handle );
if(isset($_POST['email'])){
	if($_POST['email']=="")
		die("Enter your email");
	$email=stripslashes($_POST['email']);// remove / from the value
	$email=StrQuote($email);//remove quotes
	mysql_set_charset("utf8");
	$Query="select id,name from users where email='".$email."'";//Select the record, where there is this email
	$result=mysql_query($Query);
	if(mysql_num_rows($result)!=0){//if there is a row, if it exists
		$fields = mysql_fetch_assoc($result);
		$id=$fields['id'];//get this user's id
		$name=$fields['name'];//get this user's name
		$link = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));//make a unique code (we'll use it for reset link)
		$now = new DateTime();$now=$now->format('Y-m-d H:i:s');//get the current date and time
		$linkDB=hash("sha512",$link);//code the link to put it to database
		$Query="INSERT INTO password_reset (user, user_id, link, date) VALUES ('".$name."', '".$id."', '".$linkDB."', '".$now."')";//add the request to table
		$result=mysql_query($Query);
		//send email to the user
		$to = $email;
		$from = "";//write sender's email here
		$subject = "Password";
		$message = "Hi ".$name.'<br>Please follow the link below to reset your password:<br><a href="/password.php?l='.$link.'">/password.php?l='.$link.'</a>';///add your site's address before /password.php....
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'To:' . "\r\n";
		$headers .= 'From: '.$from.'' . "\r\n";
		$headers .= '' . "\r\n";
		mail($to, $subject, $message, $headers);
		header('location:login.php?account=reset');
	}
	else
	{
			$error="We couldn't find a user with this email";
	}
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<script src="js/checkReset.js"></script>
<script src="vi.php"></script>
<title>Reset my password</title>
</head>
<body>
<div id="bg"></div>
<BR><BR><BR><BR><BR>
<div id="content" style="max-width:270px">
<p>Enter your email please:</p>
<form name="form1" method="post" action="">
   <label for="email"  style="width:55px">Email:</label>
  <input type="text" name="email" id="email" class="TextBox"  onFocus="fadeout()"  style="width:200px"><br>
   <div id="FormButtons">
  <label style="width:55px"> </label><input name="Reset" type="submit" value="Reset my password" class="Button" style="width:140px"  onClick="return CheckReset()">
  <a href="login.php"><input name="cancel" type="button" value="Cancel" class="Button" style="width:60px"></a>
  </div>
  <span id="FormMessages" style="opacity:100"><?php echo $error ?></span>
</form>
</div>
</body>
</html>

