<?php
include 'functions.php';
Logout2();//logout if already is a login
$error="";
$session='signup';//in this session, we will keep the answer to the math question
StartSession();
$name="";
$email="";
$pass="";
$currency="";
if(isset($_POST['Signup'])){
	$name=stripslashes($_POST['name']);
	$email=stripslashes($_POST['email']);
	$pass=stripslashes($_POST['password']);
	$currency=stripslashes($_POST['currency']);
	if($name==''||$email==''||$pass=='')
		die("Please fill all fields");
	
	if ($_SESSION[$session] != $_POST['math'] ){
		$error="Please enter the correct answer";	
	}
	else
	{
		$error="";
		include("db.php");
		
		$db_handle = mysql_connect($server, $user_name, $password);
		$db_found = mysql_select_db($database,$db_handle );
		
		//delete Quotes from string
		$email=StrQuote($email);
		$Query="select id from users where email='".$email."' limit 1";
		mysql_set_charset("utf8");
		$result=mysql_query($Query);
		if(mysql_num_rows($result)!=0)//if a row already exists
		{
			$error = "This Email is registered before.";
		}
		else
		{
			$name=StrClean($name);//delete all other characters than numbers and alphabet
			$currency=StrQuote($currency);//delete Quote from this
			$salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));//generate a unique code
			$pass=hash("sha512",$pass.$salt);//hash the password with the unique code
			$Query="INSERT INTO users (name, email, password, salt, currency) VALUES ('".$name."', '".$email."', '".$pass."', '".$salt."', '".$currency."')";//insert the data to db
			$result=mysql_query($Query);
			header('location:login.php?account=created');//go back to login page
		}
	}


}

//math question
$n1 = mt_rand(1,20);//make a random number between 1 and 20
$n2 = mt_rand(1,20);//make another random number between 1 and 20

if( mt_rand(0,1) === 1 ) {//make a random number between 0 and 1
	$math = "$n1 + $n2";
	$_SESSION[$session] = $n2 + $n1;//put the answer to the session
} 
else
{
	$math = "$n2 - $n1";
	$_SESSION[$session] = $n2 - $n1;
}
//////


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<script src="js/checkSignup.js"></script>
<script src="vi.php"></script>
<title>Create New User</title>
</head>
<body>
<div id="bg"></div>
<BR><BR><BR><BR><BR>
<div id="content" style="max-width:250px">
<p>Register</p>
<form name="form1" method="post" action="">
  <label for="name">Username:</label>
  <input type="text" name="name" id="name" class="TextBox" onFocus="fadeout()" value="<?php echo $name ?>"><br>
   <label for="email">Email:</label>
  <input type="text" name="email" id="email" class="TextBox" onFocus="fadeout()" value="<?php echo $email ?>"><br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" class="TextBox" onFocus="fadeout()"><br>
  <label for="currency">Currency:</label>
  <input type="text" name="currency" maxlength="4" id="currency" class="TextBox" onFocus="fadeout()" style="width:50px;"  value="<?php echo $currency ?>"> e.g. '$'<br>
  <label for="math"><?php echo $math ?>:</label>
  <input type="text" name="math" id="math" class="TextBox" onFocus="fadeout()"><br>
  <label> </label><input name="Signup" type="submit" value="Make my account" class="Button create_account"  onclick="return CheckRegister()"  style="width:150px">
  <br><span id="FormMessages" style="opacity:100"><?php echo $error ?></span><br>
</form>
</div>
</body>
</html>

