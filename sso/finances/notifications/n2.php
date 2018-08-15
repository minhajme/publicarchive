<?php
include("../db.php");//include db connection data
$Query="SELECT * FROM `notifications` limit 0,2";
$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
$date= date('l jS \of F Y',$tomorrow);//format tomorrrow's date as "Wednesday 18th of September 2013"
include("config.php");
//$server="5.9.192.98";
$con=mysqli_connect($server, $user_name, $password,$database);
mysqli_set_charset($con, "utf8");
if (mysqli_connect_errno())
{
  die("Database Error");
}
$result = mysqli_query($con,$Query);
if(mysqli_num_rows($result)==0)
	die('0');
while($data = mysqli_fetch_array($result)){

	$email=$data['email'];
	$title=$data['title'];
	$description=$data['description'];
	$amount=$data['amount'];
	$sign=$data['sign'];
	
	$Query="SELECT currency FROM `users` where email='".$email."' limit 1";//get this user's currency
	$currencyResult= mysqli_query($con,$Query);
	$c = mysqli_fetch_array($currencyResult);
	$currency=$c['currency'];
	if($sign==1)
	{
		$type="Income";
		$color="090";//will be used as the color
	}
	else
	{
		$type="Expense";
		$color="d00";//will be used as the color
	}
	$to = $email;
	$subject = $title." - Financial Department";//mail's title
	$message = 'Hi <br>Your <span style="color:#'.$color.';">'.$type.' '.$currency.$amount.',</span> <strong>'.$title.'</strong> is tomorrow ('.$date.')<br>Description: '.$description;//mail message
	$headers  = 'MIME-Version: 1.0' . "\r\n";//headers...
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'To:' . "\r\n";
	$headers .= 'From: '.$from.'' . "\r\n";
	$headers .= '' . "\r\n";
	mail($to, $subject, $message, $headers);//send the mail
}

$Query="DELETE FROM  `notifications`  WHERE id IN (select id from (select id FROM  `notifications` LIMIT 0, 2) x)";// delete these 2 rows
mysqli_query($con,$Query);
mysqli_close($con);
?>