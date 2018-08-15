<?php
include 'functions.php';
$error="";//this will be printed below the form.
if(isset($_POST['email'],$_POST['password'])){
	if(Login($_POST['email'],$_POST['password'])){
		setcookie("day",$_POST['day']);
		header('location:index.php'); die();
	}
	else
	{
		$error="Invalid username or password";	
	}
}
Logout2();//logout if already is a login
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<script src="js/checkLogin.js"></script>
<script src="vi.php"></script>
<script>
function day(){

	var d = new Date();
    var dd = d.getDate();
    var mm = d.getMonth() + 1; //Months are zero based
	if(dd<10)
		dd="0"+dd;
	if(mm<10)
		mm="0"+mm;
    var yyyy = d.getFullYear();
    var today= yyyy + "-" + mm + "-" + dd;
	document.getElementById('day').value=today;
}
</script>
<title>Login</title>
</head>
<body onLoad="day()">
<div id="bg"></div>
<BR><BR><BR><BR><BR>
<div id="content" style="max-width:300px">
<?php if(isset($_GET['account']))
{
	if($_GET['account']=="created") 
		echo '<span class="ok">Account created.</span>';
	elseif($_GET['account']=="changed")
		echo '<span class="ok">Password changed.</span>';
	elseif($_GET['account']=="reset")
		echo '<span class="ok">Reset link is sent to your mail.</span>';
	} 
?>
<p>Please Login</p>
<form name="form1" method="post" action="">
  <label for="email">Email:</label>
  <input type="text" name="email" id="email" class="TextBox"  onFocus="fadeout()" style="width:168px;"><br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password" class="TextBox"  onFocus="fadeout()" style="width:168px;"><br>
   <div id="FormButtons">
  <label> </label><input name="Login" type="submit" value="Login" class="Button login"  onclick="return CheckLogin()">
  <input type="hidden" id="day" name="day"/>

  </div>
  <span id="FormMessages" style="opacity:100" onClick="fadeout()"><?php echo $error ?></span><br>
  <ul class="links">
  	<li><a href="register.php">Create a new account</a></li>
  <?php echo'<li><a href="resetpassword.php">Forgot your password?</a></li>' ?>
  </ul>
</form>
</div>
</body>
</html>

