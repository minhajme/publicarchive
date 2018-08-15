<?php 

/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2.1
// Creation Date: 12/09/2013
// Updated To V.2.1 : 05/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/


//connection to db with protection:

    checktoken($token);

    require_once("dbvar.php");

    $table1 = "install_manager";
    $table2 = "files_log";
    $table3 = "blocked_users";
    $table4 = "approval_log";
    $table5 = "exclude_users";
    $table6 = "users_manager";
    $table7 = "def_account";
        
    $linkos = @mysqli_connect("$host", "$data_username", "$data_password");
    if(!$linkos) 
        die('<h3>Installation is Needed First.</h3>');
        
    //using utf8 data
    mysqli_query($linkos, "SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    mysqli_set_charset($linkos, "utf8_general_ci");
    if(!mysqli_select_db($linkos, "$database")) 
        die('no connection - 2'); 
        
//end of connection to db
?>