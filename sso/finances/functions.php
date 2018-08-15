<?php
StartSession();
function Login($email,$pass){
	include("db.php");//include db connection data
	$db_handle = mysql_connect($server, $user_name, $password);
	$db_found = mysql_select_db($database,$db_handle );	
	$email=stripslashes($email);//delete / from email (if user entered it)
	$email=StrQuote($email);//delete ' from email
	//$name=StrClean($email);
	$Query="select id,password,salt from users where email='".$email."' limit 1";
	$result=mysql_query($Query);
	if(mysql_num_rows($result)== 1)//user exists
	{
		$db = mysql_fetch_assoc($result) ;
		$id=$db['id'];
		$salt=$db['salt'];
		$db_password=$db['password'];
		$pass=stripslashes($pass);
		$pass=hash("sha512",$pass.$salt);//we must hash the form password with user's salt code to see if this password is correct
		if($db_password==$pass){		//if password is correct	
			$browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
			$id = preg_replace("/[^0-9]+/", "", $id); // XSS protection as we might print this value
			$_SESSION['id'] = $id; //set session #1
			$email = preg_replace("/[^a-zA-Z0-9_\@.-]+/", "", $email); // XSS protection as we might print this value
			$_SESSION['email'] = $email;//set session #2
			$_SESSION['login'] = hash('sha512', $pass.$browser);//hash the password with use browser string, #3
			return true;
		}
		else
		{
			return false;
		}
	}
	else
		return false;
}

function checklogin(){
	if(isset($_SESSION['id'], $_SESSION['email'], $_SESSION['login'])) {//If a user has logged in, these sessions must exist.
		$id = $_SESSION['id'];
		$email = $_SESSION['email'];
		$login = $_SESSION['login'];
		include("db.php");
		$db_handle = mysql_connect($server, $user_name, $password);
		$db_found = mysql_select_db($database,$db_handle );
		mysql_set_charset("utf8");
		$Query="select password,name,currency from users where id='".$id."' and email='".$email."' limit 1";//we select the user with this email and id.
		$result=mysql_query($Query);
		if(mysql_num_rows($result)==0)//User doesn't exists (this may happen if the session has wrong data.)
			logout();
		$browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
		$db_field = mysql_fetch_assoc($result) ;
		$password=$db_field['password'];
		$password=hash('sha512', $password.$browser);//Hash the password and browser data
		if($login==$password)//User is logged in
		{
			define("user",$db_field['name']);//return user's information
			define("currency",$db_field['currency']);
			define("id",$id);
			define("email",$email);
		}
		else
		{
			header('location:login.php');
		}
	}
	else
	{
		header('location:login.php');
	}
}

function Logout(){
	$_SESSION = array();
	$params = session_get_cookie_params();// get session parameters 
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);// Delete the cookie.
	session_destroy();// Destroy the session
	header('Location: ./');//go to the main page
}
function Logout2(){
	$_SESSION = array();
	$params = session_get_cookie_params();// get session parameters 
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);// Delete the cookie.
	session_destroy();// Destroy the session	
}
function StrClean($string){
	$string=strip_tags($string,"");
	$string = preg_replace('/[^A-Za-z0-9\s.\s-]/','',$string); 
	return $string;
}
function StrQuote($string){//deletes the quote from a string
	$string=strip_tags($string,"");//delete tags
	$string=str_replace("'","",$string);
	return $string;
}
function RemoveGet($var,$url){
	$string="index.php?".$url;
	$parts = parse_url($string);
	$queryParams = array();
	parse_str($parts['query'], $queryParams);
	unset($queryParams[$var]);
	$queryString = http_build_query($queryParams);
	$data = $parts['path'] . '?' . $queryString;
	list($temp,$vars)= explode("?",$data );	
	return $vars;
}
function StartSession() {
	$session_name = 'sn23da41'; // Set  session name. Write what name you like
	$secure = false; // Set to true if using https.
	$httponly = true; // Stops javascript being able to access the session id. 
	$cookieParams = session_get_cookie_params(); //current cookies params.
	session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
	session_name($session_name); // Sets the session name to the one set above.
	session_start(); // Start the session
	session_regenerate_id(); // regenerated the session, delete the old one.  
}
function RemoveTags($string){
	$string=str_replace(">","&gt;",$string);
	$string=str_replace("<","&lt;",$string);
	return $string;
}
?>