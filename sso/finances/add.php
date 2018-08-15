<?php
include 'functions.php';
checklogin();//Lheck if there is an active login.
$user=user;	
$email=email;		
$currency=currency;	
if(isset($_POST['Cancel']))
	header('location:index.php');//If user clicked "Cancel", go back
if(isset($_POST['Submit'])){
	$item_name=$_POST['name'];
	$item_name=str_replace("'","''",$item_name);//We can not insert quote into the database, But if we write 
	$amount=$_POST['price'];
	$amount=StrQuote($amount);//If somehow a quote came here, remove it.
	$date=$_POST['date'];
	$date=StrQuote($date);//If somehow a quote came here, remove it			
	$date=date('Y-m-d',strtotime($date));//convert the time so can be saved in database
	$description=$_POST['des'];
	$description=RemoveTags($description);//replace < with it's html code (user will not ba able to put html tags in the pages)
	$item_name=RemoveTags($item_name);//replace < with it's html code (user will not ba able to put html tags in the pages)
	if($description=='') $description="No Description";
	if(isset($_POST['sign'])){
		$sign=$_POST['sign'];
		if($sign!=0 && $sign!=1)
			die("Error");
	}
 	else
	{
		die("Error");
	}
	if($item_name!="" 
	 && $amount!="" 
	 && $date!=""
	 && $sign!="" 
	 && $description!=""
	 ){
		include("db.php");//include db connection data
		$db_handle = mysql_connect($server, $user_name, $password);
		$db_found = mysql_select_db($database,$db_handle );
		mysql_set_charset("utf8");
		$Query="INSERT INTO `finances` (`id`, `name`, `description`, `date`, `sign`, `amount`, `user`) 
		VALUES (NULL, '".$item_name."', '".$description."', '".$date."', '".$sign."', '".$amount."', '".$email."');";
		mysql_query($Query);//Insert the data
		header('location:index.php');
	}
	else
	{
		die("ERROR");	
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>New item</title>
<script src="js/checkForms.js"></script>
 <link rel="stylesheet" href="css/jquery-ui.css" />
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
<div id="content"  style="max-width:320px">
<form id="form1" name="form1" method="post" action="">
  <label for="name">Name</label>
  <input name="name" type="text" class="TextBox" id="name" maxlength="25" onFocus="fadeout()"/><br>
  <label for="des">Description</label><textarea name="des" id="des" cols="30" rows="5" class="TextBox2" onFocus="fadeout()"></textarea>
  <label for="price">Amount</label>
  <input name="price" type="text"  class="TextBox" id="price" maxlength="10" onFocus="fadeout()" style="width:60px;"/> <?php echo $currency ?><br><br>
  <div class="radios">
  <input type="radio" name="sign" id="p" value="0" onFocus="fadeout()">
  <label for="p">Expense</label>
  </div>
  <div class="radios">
  <input type="radio" name="sign" id="p2" value="1" onFocus="fadeout()">
  <label for="p2">Income</label>
  </div><br><br>
  <label for="name">Date</label>
  <input name="date" type="text"  class="TextBox date2" id="date" maxlength="20" onFocus="fadeout()"/><span id="FormMessages"></span><br><br>
  <label> </label>
  <input id="Submit" class="Button save2" type="submit" onclick="return Check()" value="Add" name="Submit"></input>
  <input name="Cancel" type="submit" value="Cancel" class="Button cancel">
</form>
</div>
</body>
</html>