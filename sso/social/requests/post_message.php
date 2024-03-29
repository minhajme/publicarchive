﻿<?php
include("../includes/config.php");
include("../includes/classes.php");
require_once(getLanguage(null, (!empty($_GET['lang']) ? $_GET['lang'] : $_COOKIE['lang']), 2));
// error_reporting(E_ALL ^ E_NOTICE);
session_start();
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");

$resultSettings = $db->query(getSettings()); 
$settings = $resultSettings->fetch_assoc();

// The theme complete url
$CONF['theme_url'] = $CONF['theme_path'].'/'.$settings['theme'];

// If message is not empty
if(!empty($_POST['message']) || !empty($_FILES['my_image']['size'][0]) || !empty($_POST['value'])) {

	// If the user have session or cookie set
	if(isset($_SESSION['username']) && isset($_SESSION['password']) || isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
		$loggedIn = new loggedIn();
		$loggedIn->db = $db;
		$loggedIn->url = $CONF['url'];
		$loggedIn->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : $_COOKIE['username'];
		$loggedIn->password = (isset($_SESSION['password'])) ? $_SESSION['password'] : $_COOKIE['password'];
		
		$verify = $loggedIn->verify();
		
		// If user is authed successfully
		if($verify['username']) {
			$feed = new feed();
			$feed->db = $db;
			$feed->url = $CONF['url'];
			$feed->user = $verify;
			$feed->id = $verify['idu'];
			$feed->username = $verify['username'];
			$feed->per_page = $settings['perpage'];
			$feed->c_per_page = $settings['cperpage'];
			$feed->c_start = 0;
			$feed->censor = $settings['censor'];
			$feed->smiles = $settings['smiles'];
			$feed->max_size = $settings['sizemsg'];
			$feed->image_format = $settings['formatmsg'];
			$feed->message_length = $settings['message'];
			$feed->max_images = $settings['ilimit'];
			$feed->time = $settings['time'];
			
			// If the value of a type is empty unset it (prevent empty events)
			if(!empty($_POST['type']) && empty($_POST['value'])) {
				unset($_POST['type']);
			}
			
			// Set the $x to output the value via JS
			$x = 1;
		}
	}
}

?>
<?php if($x == 1) { ?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload('<?php echo $feed->postMessage($_POST['message'], $_FILES['my_image'], $_POST['type'], $_POST['value'], $_POST['privacy']); ?>');</script>
<?php } else { ?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload(' ')</script>
<?php } ?>