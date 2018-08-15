<?php
include 'functions.php';
checklogin();//check for login.
$user=user;	
$email=email;
$currency=currency;	
if(!isset($_GET['id']))
	header('location:index.php');
if(!is_numeric($_GET['id']))//if there are other values for the ?id=.. than numbers, this is dangerous. so go back..
	header('location:index.php');
mysql_set_charset("utf8");
$Query="select * from finances where id='".$_GET['id']."' and user='".$email."'";//select the record
$result=mysql_query($Query);
if(mysql_num_rows($result)==0)
	header('location:index.php');
$db_field = mysql_fetch_assoc($result);
if($db_field['sign']==0){
	$point="-";
	$cc="b00";
}
else
{
	$point="+";
	$cc="080";
}
$name=$db_field['name'];
$description=$db_field['description'];
if($description=="No Description")//if this doesn't have description, show it empty
	$description="";
$price=sprintf('%.2F',$db_field['amount']);
$date=$db_field['date'];
$date=date('m/d/Y',strtotime( $date));//format the date as d/m/Y to display
if(isset($_POST['Cancel'])){ 
	$urlvars='';
	if($_SERVER['QUERY_STRING'])
		$urlvars= $_SERVER['QUERY_STRING'];
	$urlvars=RemoveGet("id",$urlvars);
	if($urlvars!='')
		$urlvars='?'.$urlvars;
	header("location:index.php".$urlvars);
}	
if(isset($_POST['Submit'])){//If the form is submitted, read the form values and update the database.
	$f1=$_POST['name'];
	$f1=str_replace("'","''",$f1);
	$f1=RemoveTags($f1);//replace < with it's html code (user will not ba able to put html tags in the pages)
	$f2=$_POST['price'];
	$f2=StrQuote($f2);//remove quotes from price
	$f3=$_POST['date'];
	$future=0;
	$f3=date('Y-m-d',strtotime($f3));//convert the time so can be saved in database
	$f3=StrQuote($f3);//remove quotes from date
	$f5=$_POST['des'];
	$f5=RemoveTags($f5);
	$f5=str_replace("'","''",$f5);
	if($f5=="No Description")
		$f5="";
	if(isset($_POST['sign']))
		$f4=$_POST['sign'];
	if($f1!=""
	&& $f2!=""
	&& $f3!=""
	&& $f4!=""
	){
		$id=$_GET['id'];
		mysql_set_charset("utf8");
		$Query="UPDATE `finances` SET `name`='".$f1."',`description`='".$f5."',`date`='".$f3."',`sign`='".$f4."',`amount`='".$f2."' where id='".$id."' and user='".$email."'";
		mysql_query($Query);
		$urlvars='';
		if($_SERVER['QUERY_STRING'])
			$urlvars= $_SERVER['QUERY_STRING'];
		$urlvars=RemoveGet("id",$urlvars);
		if($urlvars!='')
			$urlvars='?'.$urlvars;
		header('location:index.php'.$urlvars);
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
<title><?php echo $name ?></title>
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
<div id="content" style="max-width:320px">
<form id="form1" name="form1" method="post" action="">
  <label for="name">Name</label>
  <input name="name" type="text" class="TextBox" id="name" maxlength="25" value="<?php echo $name ?>"  onFocus="fadeout()"/><br>
  <label for="des">Description</label><textarea name="des" id="des" cols="30" rows="5" class="TextBox2"  onFocus="fadeout()"><?php echo $description ?></textarea>
  <label for="price">Amount</label>
  <input name="price" type="text"  class="TextBox" id="price" maxlength="10"  value="<?php  echo $price ?>"  style="width:60px;"  onFocus="fadeout()"/> <?php echo $currency ?><br><br>
  <div class="radios">
  <input name="sign" type="radio" id="p" value="0"<?php if($point=="-") echo "checked" ?>  onFocus="fadeout()">
  <label for="p">Expense</label>
  </div>
  <div class="radios">
  <input type="radio" name="sign" id="p2" value="1" <?php if($point=="+") echo "checked" ?>  onFocus="fadeout()">
  <label for="p2">Income</label>
  </div><br><br>
  <label for="name">Date</label>
  <input name="date" type="text"  class="TextBox date2" id="date" maxlength="20"  value="<?php echo $date ?>"  onFocus="fadeout()"/><br><span id="FormMessages"></span><br><br>
  <label> </label>
  <input name="Submit" type="submit" value="Save" class="Button save"  onclick="return Check()" >
  <input name="Cancel" type="submit" value="Cancel" class="Button cancel">
</form>
</div>
</body>
</html>