<?php
include("../db.php");//include db connection data
$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));//make tomorrow's date (+1 day from today)
$date= date('Y-m-d',$tomorrow);
$Query="INSERT INTO `notifications`(`email`, `title`, `description`, `amount`, `sign`) 
SELECT user, name, description, amount, sign from `finances` where `date`='".$date."'";
include("config.php");
//$server="5.9.192.98";
$con=mysqli_connect($server, $user_name, $password,$database);
mysqli_set_charset($con, "utf8");
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
mysqli_query($con,$Query);
mysqli_close($con);
?>