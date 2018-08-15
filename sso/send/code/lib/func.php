<?php
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2
// Creation Date: 12/09/2013
// Updated To V.2 : 01/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/

/* SYSTEM VARIBLES */

    if(defined(__DIR__))                                                                define('DIRPATH', __DIR__);                                         else            define('DIRPATH',dirname(__FILE__));
    if(isset($_SERVER['HTTP_REFERER']))                                                 define('REF',$_SERVER['HTTP_REFERER']);                             else            define('REF',false);
    if(isset($_SERVER['DOCUMENT_ROOT']))                                                define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);                       else            define('DOCROOT',false);
    if(isset($_SERVER['SERVER_NAME']))                                                  define('SERVERNAME', $_SERVER['SERVER_NAME']);                      else            define('SERVERNAME',false);
    if(isset($_SERVER['HTTP_HOST']))                                                    define('SERVERHOST', $_SERVER['HTTP_HOST']);                        else            define('SERVERHOST',false);
    if(isset($_SERVER['REMOTE_ADDR']))                                                  define('SERVERREMOTE', $_SERVER['REMOTE_ADDR']);                    else            define('SERVERREMOTE',false);
    if(isset($_SERVER['HTTP_USER_AGENT']))                                              define('USERAGENT', $_SERVER['HTTP_USER_AGENT']);                   else            define('USERAGENT',false);
    if(ini_get('max_file_uploads')!==false&&ini_get('max_file_uploads')!=null)          define('SYS_MAX_UPLOADS',ini_get('max_file_uploads'));              else            define('SYS_MAX_UPLOADS',false);
    if(ini_get('upload_max_filesize')!==false&&ini_get('upload_max_filesize')!=null)    define('SYS_MAX_FILESIZE',ini_get('upload_max_filesize'));          else            define('SYS_MAX_FILESIZE',false);
    if(ini_get('post_max_size')!==false&&ini_get('post_max_size')!=null)                define('SYS_MAX_POST_SIZE',ini_get('post_max_size'));               else            define('SYS_MAX_POST_SIZE',false);
    if(ini_get('max_input_time')!==false&&ini_get('max_input_time')!=null)              define('SYS_MAX_INPUT_TIME',ini_get('max_input_time'));             else            define('SYS_MAX_INPUT_TIME',false);
    if(ini_get('max_input_vars')!==false&&ini_get('max_input_vars')!=null)              define('SYS_MAX_INPUT_VARS',ini_get('max_input_vars'));             else            define('SYS_MAX_INPUT_VARS',false);

    /* PASSWORD PROTECT: */
    define('USE_USERNAME', true);
    define('TIMEOUT_MINUTES', 30);


    /* TOCKENIZE FUNCTIONS */
    if (!function_exists('gettoken')) { 
        function gettoken() {
            // only create new ID for browser - to support multi tab browsers!.
            if(!isset($_SESSION['user_token'])) $_SESSION['user_token'] = uniqid(); 
        }
    }

    if (!function_exists('checktoken')) { 
        function checktoken($token) {
            if (!isset($_SESSION['user_token'])) { 
            
                die ("die2"); 
            
            } else { 
                if ($token!=md5($_SESSION['user_token'])) {  
                    die ("die3"); 
                } 
            }								
        }
    }

    if (!function_exists('gettokenfield')) { 
        function gettokenfield() {
            return ("<input type='hidden' name='get' id='get' value='"
                    .md5($_SESSION['user_token'])."' />"
            );
        }
    }

    if (!function_exists('destroytoken')) { 
        function destroytoken() {
            unset ($_SESSION['user_token']);
        }
    }

?>