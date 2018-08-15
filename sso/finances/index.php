<?php
include 'functions.php';
checklogin();//check if user has logged in
$user=user;//get the logged in user's name
$email=email;//get his emal
$currency=currency;//and curency
$search=""; //by default, we are not searching for anything
$ignoreList = array("'");/// the array of the things we do not want to search. (IMPORTANT: "'" this sign must not be in searching. so we remove it.)

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

if(isset($_POST['search']))//if this is a search, add the searching sql code for bars
{
	$searchtext=StrQuote($_POST['searchtext']);
	if($searchtext!=''){
		if($_SERVER['QUERY_STRING']){
			$vars=$_SERVER['QUERY_STRING'];
			if(isset($_GET['search'])){//if there is another search in the usrl (previous search)
				$vars="";
				$urlvars=$_SERVER['QUERY_STRING'];
				$vars=RemoveGet("search",$urlvars);//we remove the 'search' from url variables. for example, we have "index.php?sort=name&search=new" after we remove the 'search', user will be taken to "index.php?sort=name"
			}
			if($vars=="")
				header('location:?'.$vars.'search='.$searchtext);//if there are no other GET variables, the search will be sent as ?search=..	
			else
				header('location:?'.$vars.'&search='.$searchtext);//if there are other GET variables, this "search" variable must be added to them as -> ..&search=..					
		}
		else
			header('location:?search='.$searchtext);//if there is no other GET variable, simply we go to "index.php?search=..."
	}
	//if search input is empty, we show all.
	if($searchtext=='')header('location:index.php');
}

if(isset($_GET['search']))//if this is a search, add the searching sql code for bars
{
	$search=" AND name COLLATE UTF8_GENERAL_CI LIKE '%".StrQuote($_GET['search'])."%'"; //% means, missing characters
	//note: COLLATE UTF8_GENERAL_CI is needed when we search is not case senstive. When user enters "NEW", "NEW", "New", "new", ... will be shown in search results. Just because of COLLATE UTF8_GENERAL_CI
}	
mysql_set_charset("utf8");
$Query="SELECT sum( amount ) as p FROM finances WHERE user='".$email."' AND  date<='".$today."' AND sign=0".$search;//To find if the expenses
$result=mysql_query($Query);
$row = mysql_fetch_assoc($result); 
$p0= $row['p'];// $p0 = expenses
if($p0=="")
	$p0=0; //if there is no expense at all, so it is 0
$Query="SELECT sum( amount ) as p FROM finances WHERE user='".$email."' AND date<='".$today."' AND sign=1".$search;//To find if the incomes
$result=mysql_query($Query);
$row = mysql_fetch_assoc($result); 
$p1= $row['p'];// $p1 = incomes
if($p1=="")
	$p1=0;//if there is no income at all, so it is 0
$p2=$p1-$p0;//Profit
$p0=round($p0,2);//round the number (12.58745874 -> 12.5)
$p1=round($p1,2);//round the number 
$p2=round($p2,2);//round the number
$sum=$p1+$p0;//get the all amount (expenses and incomes)
if($sum!=0){
	$p0d=($p0*100)/$sum;//calculate expenses in %
	$p1d=($p1*100)/$sum;//calculate incomes in %
	$p0dr=round($p0d,1);//round the number (12.58745874 -> 12.5)
	$p1dr=round($p1d,1);//round the number 
}
else//if there is no income or expense in the database
{
	$p0d=0;
	$p1d=0;	
}
$p0=number_format($p0,1);//format the money as = xxx,xxx,xxx
$p1=number_format($p1,1);
$p2=number_format($p2,1);
//database data reading start here:
$sort="";///by default, we do not sort the data.
$search="";//by default, we do not search.
if(isset($_GET['search']))//if this is a search (this is for the data)
{
	$search.=" AND name COLLATE UTF8_GENERAL_CI LIKE '%". StrQuote($_GET['search'])."%'";  //make the searching SQL command. This will be added to the end of the select command.	
}
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
mysql_set_charset("utf8");
$Query="select * from finances WHERE user='".$email."' AND date<='".$today."' ".$search.$sort;//add search and sort and make the final select command
//die($Query);
$result=mysql_query($Query);
$userdata="";//the result, is empty at first.
$count=mysql_num_rows($result);
if($count== 0)//No data is stored at the database, we write it in the final result
{
	$userdata='<br><span class="name">No Data Found</span><br>';
}
else
{
	while ( $db_field = mysql_fetch_assoc($result) ){//A loop to read all user's data
		if($db_field['sign']==0) //check for the record's sign
		{
			$point="-";
			$type="Expense";
		}
		else
		{
			$point="";
			$type="Income";
		}
		$urlvars="";
		if($_SERVER['QUERY_STRING'])
			$urlvars= '&'. $_SERVER['QUERY_STRING'];//to prtin sort and search data in links (for edit and detail page)
		$amount=number_format($db_field['amount'],2);
		$fontsize='16';$fontspace='0';
		if(strlen($amount)>10) 
			$fontsize='15';
		//if(strlen($amount)>12) 
		//	$fontsize='14';
		if(strlen($amount)>14) 
			$fontspace='-1';
		$userdata.='<li>
		<ul>
		<li class="name"><a href="detail.php?id='.$db_field['id'].$urlvars.'">'.$db_field['name'].'</a></li>
		<li class="amount'.$db_field['sign'].'" style="font-size:'.$fontsize.'px;letter-spacing: '.$fontspace.'px;">'.$currency.$point.$amount.'</li>
		<li class="date">'.$db_field['date'].'</li>    
		<li class="amount'.$db_field['sign'].'">'.$type.'</li> 
		<li class="edit"><a href="edit.php?id='.$db_field['id'].$urlvars.'">Edit</a></li>  
		</ul>  
		</li>';
	}
}


mysql_set_charset("utf8");
$Query="select * from finances WHERE user='".$email."' AND date>'".$today."' ".$search.$sort;//add search and sort and make the final select command
$result=mysql_query($Query);
$futureData="";//the result, is empty at first.
$count2=mysql_num_rows($result);
if($count2== 0)//No data is stored at the database, we write it in the final result
{
	$futureData='<br><span class="name">No Data Found</span><br>';
}
else
{
	while ( $db_field = mysql_fetch_assoc($result) ){//A loop to read all user's data
		if($db_field['sign']==0) //check for the record's sign
		{
			$point="-";
			$type="Expense";
		}
		else
		{
			$point="";
			$type="Income";
		}
		$urlvars="";
		if($_SERVER['QUERY_STRING'])
			$urlvars= '&'. $_SERVER['QUERY_STRING'];//to prtin sort and search data in links (for edit and detail page)
		$futureData.='<li>
		<ul>
		<li class="name"><a href="detail.php?id='.$db_field['id'].$urlvars.'">'.$db_field['name'].'</a></li>
		<li class="amount'.$db_field['sign'].'">'.$currency.$point.number_format($db_field['amount'],2).'</li>
		<li class="date">'.$db_field['date'].'</li>    
		<li class="amount'.$db_field['sign'].'">'.$type.'</li> 
		<li class="edit"><a href="edit.php?id='.$db_field['id'].$urlvars.'">Edit</a></li>  
		</ul>  
		</li>';
	}
}



$ShowAll="";
if($search!="")
	$ShowAll= '<br><a href="index.php" style="margin-left:30px;"><button  class="Button all" style="width:100px;">Show all</button></a>';//if this is a search, show the back button


//change the user's last active time to today


function orders($id,$e)
{
	if(isset($_GET['sort'])) 
	{
		if($_GET['sort']==$id)
		{
			if(isset($_GET['by']))
			{
				if($e==1)
				{
					if($_GET['by']=="asc")
						echo "asc"; 
					elseif($_GET['by']=="desc")
						echo "desc";
					else
						echo "";
				}
				else
				{ 
					if($_GET['by']=="asc")
						echo "&by=desc"; 
					else 
						echo "&by=asc";
				}
			}
			else
			{
					if($e==0) echo "&by=desc";
					else echo "asc";
			}	
		}
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<link href="css/images/icon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Financial Department</title>
<script src="js/Print.js"></script>
<script src="js/today.js"></script>
</head>
<body onLoad="day()">
<div id="bg"></div>
<BR><BR><BR>
<div id="content">
<div id="top">
    <div id="user">Hi,<span id="name" class="account"><?php echo $user ?></span>
        <div id="menu">
        <a href="user.php" class="setting">Account</a>
        <a href="help" class="help">Help</a>
        <a href="logout.php" class="logout">Logout</a>
        </div>
          
</div><span class="totalRecords">Today: <?php echo $today ?></span>
</div>
<div id="top">
  <div class="head">
  <div class="left">
    <a href="add.php"><button  class="Button new" style="width:130px;" >Add New</button></a>
    <div class="right" style="top:0; width:180px;">
    <a href="#" onClick="print()"><button  class="Button print" style="width:80px;">Print</button></a>
    <a href="excel.php<?php if($_SERVER['QUERY_STRING']) echo '?'. $_SERVER['QUERY_STRING']; ?>"><button  class="Button excel" style="width:80px;">Excel</button></a>
    </div>
  </div>
  <div class="right">
    <form method="post">
    <input type="text" id="searchtext" name="searchtext" maxlength="18" class="TextBox" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"' ?> style="height:20px"> <input type="Submit" id="search" name="search" value="Search" class="Button search">
    </form>
  </div>
</div>
  <div id="Content1">
  <div id="user-data-bar" >
  <div id="expense">Expenses: <?php echo $currency.'-'.$p0 ?></div>
  <div id="profit" style="color:#<?php if($p2<0) echo '900'; else echo '090'; ?>;">Profit: <?php echo $currency.$p2?></div>
  <div id="income">Incomes: <?php echo $currency.$p1 ?></div>
  <div id="bars" <?php if($count==0) echo 'style="background: #cdd"'; ?>>
  <span id="out" style="width:<?php echo ($p0d-0.5).'%;';if($p0d<1)echo'visibility:hidden; position:fixed;';  if($p0dr<7)echo"color:#ff5a0e;" ?>"><?php //echo $p0dr.'%' ?></span>
  <span id="in" style="width:<?php echo ($p1d-0.5).'%;'; if($p1d<1)echo'visibility:hidden;position:fixed;'; if($p1dr<7)echo"color:#0e0;" ?>"><?php //echo $p1dr.'%' ?></span>
  </div>
  </div>
</div>
</div>
<div  id="Content2">
  <h4 class="title">Incomes/Expenses:</h4>
  <span class="totalRecords" id="count">Total: <?php echo $count ?> records.</span>

  <ul class="user-data-list" id="data">
    <li class="listhead">
    <ul>
      <li class="columns<?php  orders('name',1) ?>" style="margin-left:10px;width: 220px; background-position:26% center"><a href="?sort=name<?php orders('name',0);  if(isset($_GET['search']))echo"&search=".$_GET['search']; ?>">Name</a></li>
      <li class="columns<?php  orders('amount',1) ?>" style="width: 110px; text-align:center; background-position:90% center"><a href="?sort=amount<?php orders('amount',0);   if(isset($_GET['search']))echo"&search=".$_GET['search'];?>">Amount</a></li>
      <li class="columns<?php  orders('date',1) ?>" style="width: 120px; text-align:center; background-position:80% center"><a href="?sort=date<?php orders('date',0);   if(isset($_GET['search']))echo"&search=".$_GET['search'];?>">Date</a></li>
      <li class="columns<?php  orders('type',1) ?>" style="width: 110px; text-align:center; background-position:80% center"><a href="?sort=type<?php orders('type',0);   if(isset($_GET['search']))echo"&search=".$_GET['search'];?>">Type</a></li>
    </ul>
    </li>
    <?php 
    //print the database data here
    echo $userdata;
    ?>
  </ul>
  <!--Future data-->
  <h4 class="title">Future Incomes/Expenses:</h4>
  <span class="totalRecords" id="count2">Total: <?php echo $count2 ?> records.</span>

   <ul class="user-data-list" id="data2">
    <li class="listhead">
    <ul>
      <li class="columns<?php  orders('name',1) ?>" style="margin-left:10px;width: 220px; background-position:26% center"><a href="?sort=name<?php orders('name',0);  if(isset($_GET['search']))echo"&search=".$_GET['search']; ?>">Name</a></li>
      <li class="columns<?php  orders('amount',1) ?>" style="width: 110px; text-align:center; background-position:90% center"><a href="?sort=amount<?php orders('amount',0);   if(isset($_GET['search']))echo"&search=".$_GET['search'];?>">Amount</a></li>
      <li class="columns<?php  orders('date',1) ?>" style="width: 120px; text-align:center; background-position:80% center"><a href="?sort=date<?php orders('date',0);   if(isset($_GET['search']))echo"&search=".$_GET['search'];?>">Date</a></li>
      <li class="columns<?php  orders('type',1) ?>" style="width: 110px; text-align:center; background-position:80% center"><a href="?sort=type<?php orders('type',0);   if(isset($_GET['search']))echo"&search=".$_GET['search'];?>">Type</a></li>
    </ul>
    </li>
    <?php 
    //print the database data here
    echo $futureData;
    ?>
  </ul>
</div>
<?php echo $ShowAll ?>
<div class="footer">&copy; 2013, Financial Department.</div>
</div>
<br><br>
</body>
</html>
