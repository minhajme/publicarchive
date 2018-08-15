<?php 
include 'functions.php';
checklogin();
$user=user;	
$email=email;
$currency=currency;	

include("db.php");//include db connection data
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database,$db_handle );

if(isset($_COOKIE['day']))
{
	$today=$_COOKIE['day'];
	$today=StrClean($today);
}
else
{
	$today= date("Y-m-d");
}

require 'php-export-data.class.php';
$exporter = new ExportDataExcel('browser', "ExportExcel.xls");//excel file
$exporter->initialize();


mysql_set_charset("utf8");
$sort="";
$search=" WHERE  date<='".$today."' and user='".$email."'";
if(isset($_GET['search']))//if this is a search, add the searching sql code
	$search.=" AND name COLLATE UTF8_GENERAL_CI LIKE '%".$_GET['search']."%'"; 
if(isset($_GET['sort']))//if there is a 'sort' variable in the url, we must sord the data with this variable's data
{
	$sort=" ORDER BY ";
	switch ($_GET['sort']) {
	case "id":
		$sort.="id";
		break;
		
	case "name":
		$sort.="name";
		break;
		
	case "amount":
		$sort.="amount";
		break;
		
	case "date":
		$sort.="date";
		break;
		
	case "type":
		$sort.="sign";
		break;
		
	default:
		$sort="ERROR";//if user enters something else for sort, we should stop this.
		break;
	}	
	//if there is a 'sort' and 'by' varible, so we must order the data 'asc' or 'desc'
	//if the url variable 'by' is asc, we add " ASC" to the end of $sort. else, we add DESC to it.
	if($sort!="ERROR")
	{
		if($sort==" ORDER BY name") $sort.=" COLLATE UTF8_GENERAL_CI";//to make the sort case insensitive
		if(isset($_GET['by']))
		{
			if($_GET['by']=="asc")
				$sort.=" ASC";
			elseif ($_GET['by']=="desc")
				$sort.=" DESC";
		}
	}
	else
	{
		$sort="";//if it's error, so we empty it
	}
}
$exporter->addRow(array("Name", "Amount", "Date" ,"Type")); //first row (header)
$Query="select * from finances".$search.$sort;
$result=mysql_query($Query);
$o="";
while ( $db_field = mysql_fetch_assoc($result) ){
	if($db_field['sign']==0) 
	{
		$point="-";
		$type="Expense";
	}
	else
	{
		$point="+";
		$type="Income";
	}
	$exporter->addRow(array($db_field['name'], $currency.$point.number_format($db_field['amount'],2), $db_field['date'],$type)); //add each record as a new row
}
		$exporter->addRow(array("")); //first row (header)
		$exporter->addRow(array("Future expenses/incomes")); //first row (header)
		
$sort="";
$search=" WHERE  date>'".$today."' and user='".$email."'";
if(isset($_GET['search']))//if this is a search, add the searching sql code
	$search.=" AND name COLLATE UTF8_GENERAL_CI LIKE '%".$_GET['search']."%'"; 
if(isset($_GET['sort']))//if there is a 'sort' variable in the url, we must sord the data with this variable's data
{
	$sort=" ORDER BY ";
	switch ($_GET['sort']) {
	case "id":
		$sort.="id";
		break;
		
	case "name":
		$sort.="name";
		break;
		
	case "amount":
		$sort.="amount";
		break;
		
	case "date":
		$sort.="date";
		break;
		
	case "type":
		$sort.="sign";
		break;
		
	default:
		$sort="ERROR";//if user enters something else for sort, we should stop this.
		break;
	}	
	//if there is a 'sort' and 'by' varible, so we must order the data 'asc' or 'desc'
	//if the url variable 'by' is asc, we add " ASC" to the end of $sort. else, we add DESC to it.
	if($sort!="ERROR")
	{
		if($sort==" ORDER BY name") $sort.=" COLLATE UTF8_GENERAL_CI";//to make the sort case insensitive
		if(isset($_GET['by']))
		{
			if($_GET['by']=="asc")
				$sort.=" ASC";
			elseif ($_GET['by']=="desc")
				$sort.=" DESC";
		}
	}
	else
	{
		$sort="";//if it's error, so we empty it
	}
}
$exporter->addRow(array("Name", "Amount", "Date" ,"Type")); //first row (header)
$Query="select * from finances".$search.$sort;
$result=mysql_query($Query);
$o="";
while ( $db_field = mysql_fetch_assoc($result) ){
	if($db_field['sign']==0) 
	{
		$point="-";
		$type="Expense";
	}
	else
	{
		$point="+";
		$type="Income";
	}
	$exporter->addRow(array($db_field['name'], $currency.$point.number_format($db_field['amount'],2), $db_field['date'],$type)); //add each record as a new row
}	
		
		
		
		
$exporter->finalize(); // writes the footer, flushes remaining data to browser.
	exit();//done

?>