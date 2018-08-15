<?php
include 'functions.php';
checklogin();
$user=user;
$email=email;
$currency=currency;
include("db.php");//include db connection data
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database,$db_handle );
if(!isset($_GET['id']))
	header('location:index.php');
if(!is_numeric($_GET['id']))
	header('location:index.php');
$urlvars=RemoveGet("id",$_SERVER['QUERY_STRING']);//if there are data in the url, now we have them witout 'id' in this varible
if(isset($_GET['action'])){//Check if the "?action=..." is in the url
	if($_GET['action']=="Delete"){//Check if the "?action=Delete" was sent in the url
		$urlvars=RemoveGet("action",$urlvars);//remove the 'action' varible
		if($urlvars!='')//if there are other url variable's left
			$urlvars='?'.$urlvars;
		$Query="DELETE FROM `finances` WHERE id='".$_GET['id']."' AND user='".$email."'";//delete the record with this id
		mysql_query($Query);
		header('location:index.php'.$urlvars);
		die();//stop the code here
	}
}
if($urlvars!='') 
	$urlvars='?'.$urlvars;//if there are varibles to add to the url, we must add it with a '?' at the beginning.
mysql_set_charset("utf8");
$Query="select * from finances where id='".$_GET['id']."' and user='".$email."'";//select the record
$result=mysql_query($Query);
if(mysql_num_rows($result)==0)//if this row doesn't exist, go back
	header('location:index.php');	
$db_field = mysql_fetch_assoc($result);
if($db_field['sign']==0){//Sign = 0 -> -, sign = 1 -> +
	$cc="b00";// $cc will be the color
}
else
{
	$cc="080";// $cc will be the color
}
$name=$db_field['name'];
$description=$db_field['description'];
if($description=="")
	$description="No Description.";//if the field description in the database is null, so this record doesn't have description.
$price=$db_field['amount'];
$date=$db_field['date'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title><?php echo $name ?></title>
<script>
function aa(){
	 if (confirm("Are you sure?")==true)
    return true;
  else
    return false;
}
</script>
</head>
<body >
<div id="bg"></div>
<BR><BR><BR><BR><BR><BR><BR>
<div id="content"style="max-width:400px">
<span id="name1"><?php echo $name ?></span><br><span class="smallData">(Date: <?php echo $date ?>)</span>
<span id="price1" style="color:#<?php echo $cc ?>"><?php  echo $currency; echo  number_format($price,2); ?></span><br><br>
<div id="description1" style="width:100%"><div id="name1">Description:</div><p><?php echo $description; ?></p></div>
<br><br>
<a href="index.php<?php echo $urlvars ?>"><button  class="Button back">Back</button></a>
<a href="edit.php?<?php echo $_SERVER['QUERY_STRING'] ?>"><button  class="Button editt">Edit</button></a>
<a href="?action=Delete&<?php echo $_SERVER['QUERY_STRING'] ?>" onClick="return aa()"><button  class="Button delete">Delete</button></a>
</div>
</body>
</html>