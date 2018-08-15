<?php
include 'functions.php';
checklogin();
$user=user;	
$db_email=email;
$id=id;
$error="";
if(isset($_POST['cancel']))
{
	header('location:index.php');
	die();
}
include("db.php");//include db connection data
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database,$db_handle );
mysql_set_charset("utf8");
$Query="SELECT * FROM `users` WHERE id='".$id."' AND name='".$user."'";
$result=mysql_query($Query);
if(mysql_num_rows($result)==0)
	header('location:index.php');
$data = mysql_fetch_assoc($result);
$name=$data['name'];
$email=$data['email'];
$currency=$data['currency'];	

if(isset($_POST['email'],$_POST['currency'])){
	$oldemail=$email;
	$email=$_POST['email'];	
	$email=stripslashes($email);//Delete slashes from  email 
	$email=StrQuote($email);//Delete quotes from email
	$currency=$_POST['currency'];	
	$currency=stripslashes($currency);//Delete slashes from currency
	$currency=StrQuote($currency);//Delete quotes from currency
	$Query="SELECT * FROM `users` WHERE email='".$email."'";
	$result=mysql_query($Query);
	if(mysql_num_rows($result)==0 || $oldemail==$email){
		$Query="UPDATE `users` SET `email`='".$email."',`currency`='".$currency."' where id='".$id."' and name='".$user."'";
		mysql_query($Query);	
		if($email!=$db_email){//if user is changing his email, we must change his finances ownership
			$Query="UPDATE `finances` SET `user`='".$email."' where user='".$db_email."'";
			mysql_query($Query);	
		}
		header('location:index.php');
	}
	else
	{
		$error="This email is registered before";
	}
}		
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title><?php echo $name ?></title>
<script src="js/checkAccount.js"></script>
</head>
<body>
<div id="bg"></div>
<BR><BR><BR><BR><BR><BR><BR>
<div id="content" style="max-width:320px">
<form id="form1" name="form1" method="post" action="">
  <label>Name</label>
  <label style="width:70%"><?php echo $name ?></label><br><br>
    <label for="email">Email</label>
  <input name="email" type="text" class="TextBox" id="email" maxlength="25" value="<?php echo $email ?>" onFocus="fadeout()"/><br>
    <label for="currency">Currency</label>
  <input name="currency" type="text" class="TextBox" id="currency" maxlength="4" value="<?php echo $currency ?>"  onFocus="fadeout()" style="width:40px"/> eg. $<br>
  <label> </label>
  <div id="FormButtons"><input name="Submit" type="submit" value="Save" class="Button save"  onclick="return Check()" >
 <input name="cancel" type="submit" value="Cancel" class="Button cancel"></div>
  <span id="FormMessages" style="opacity:100"><?php echo $error ?></span><br>
  <label> </label><a href="editpassword.php">Change password</a>
</form>
</div>
</body>
</html>