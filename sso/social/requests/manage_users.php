﻿<?php
include("../includes/config.php");
include("../includes/classes.php");
include(getLanguage(null, (!empty($_GET['lang']) ? $_GET['lang'] : $_COOKIE['lang']), 2));
session_start();
$db = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}
$db->set_charset("utf8");

$resultSettings = $db->query(getSettings());
$settings = $resultSettings->fetch_assoc();

if(isset($_SESSION['usernameAdmin']) && isset($_SESSION['passwordAdmin'])) {
	$loggedInAdmin = new loggedInAdmin();
	$loggedInAdmin->db = $db;
	$loggedInAdmin->url = $CONF['url'];
	$loggedInAdmin->username = $_SESSION['usernameAdmin'];
	$loggedInAdmin->password = $_SESSION['passwordAdmin'];
	$loggedIn = $loggedInAdmin->verify();

	if($loggedIn['username']) { 
		if(isset($_POST['start'])) {
			$manageUsers = new manageUsers();
			
			$manageUsers->db = $db;
			$manageUsers->url = $CONF['url'];
			$manageUsers->per_page = $settings['uperpage'];
			
			echo $manageUsers->getUsers($_POST['start']);
		}
	}
}
?>