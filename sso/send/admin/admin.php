<?php
    error_reporting(0);
    @ini_set('display_errors', 'off');
    session_start();
    define('DS', DIRECTORY_SEPARATOR);
/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2
// Creation Date: 12/09/2013
// Updated To V.2 : 01/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/

/* requird core files */
    require("..".DS."code".DS."lib".DS."func.php");
    gettoken();
    $token = md5($_SESSION['user_token']);
    require("..".DS."code".DS."lib".DS."conndb.php");
    require("password_protect.php");

/* admin settings */

    $sqlstatus = "SELECT * FROM ".$table1." WHERE `id`='1'";
    $rs_result = mysqli_query($linkos, $sqlstatus) or die ( "problem1" ); 
                while ($idr=mysqli_fetch_array($rs_result)) {
                    $brand          = $idr['brand_name'];
                    $accept_types   = $idr['accept_types'];
                    $maxfiles       = $idr['maxfiles'];
                    $maxrecipients  = $idr['maxrecipients'];
                    $files_path     = $idr['files_folder'];
                    $servermail     = $idr['server_mail'];
                    $themeUse       = $idr['theme'];
                }

/* file types group */
    $fileTypes = array(
        'Text'      =>array('.doc','.docx','.log','.msg','.odt','.pages','.rtf',
                      '.tex','.txt','.wpd','.wps'),
        'Data'      =>array('.csv','.dat','.gbr','.ged','.ibooks','.key','.keychain',
                      '.pps','.ppt','.pptx','.sdf','.tar','.tax2012','.vcf','.xml'),
        'Audio'     =>array('.aif','.iff','.m3u','.m4a','.mid','.mp3','.mpa','.ra',
                       '.wav','.wma'),
        'Video'     =>array('.3g2','.3gp','.asf','.asx','.avi','.flv','.m4v','.mov',
                       '.mp4','.mpg','.rm','.srt','.swf','.vob','.wmv'),
        'eBook'     =>array('.acsm','.aep','.apnx','.ava','.azw','.azw1','.azw3',
                       '.azw4','.bkk','.bpnueb','.cbc','.ceb','.dnl','.ebk',
                       '.edn','.epub','.etd','.fb2','.html0','.htmlz','.htxt',
                       '.htz4','.htz5','.koob','.lit','.lrf','.lrs','.lrx',
                       '.mart','.mbp','.mobi','.ncx','.oeb','.opf','.pef',
                       '.phl','.pml','.pmlz','.pobi','.prc','.qmk','.rzb','.rzs',
                       '.tcr','.tk3','.tpz','.tr','.tr3','.vbk','.webz','.ybk'),
        'image3d'   =>array('.3dm','.3ds','.max','.obj'),
        'Raster'    =>array('.bmp','.dds','.gif','.jpg','.png','.psd','.pspimage',
                        '.tga','.thm','.tif','.tiff','.yuv','jpeg'),
        'Vector'    =>array('.ai','.eps','.ps','.svg'),			
        'Camera'    =>array('.3fr','.ari','.arw','.bay','.cr2','.crw','.dcr','.dng',
                        '.eip','.erf','.fff','.iiq','.k25','.kdc','.mef','.mos',
                        '.mrw','.nef','.nrw','.orf','.pef','.raf','.raw','.rw2',
                        '.rwl','.rwz','.sr2','.srf','.srw','.x3f'),
        'Layout'    =>array('.indd','.pct','.pdf'),	
        'Spreadsheet'=>array('.xlr','.xls','.xlsx'),		
        'Database'  =>array('.accdb','.db','.dbf','.mdb','.pdb','.sql'),		
        'Executable'=>array('.apk','.app','.bat','.cgi','.com','.exe','.gadget',
                            '.jar','.pif','.vb','.wsf'),	
        'Game'      =>array('.dem','.gam','.nes','.rom','.sav'),	
        'CAD'       =>array('.dwg','.dxf'),	
        'GIS'       =>array('.gpx','.kml','.kmz'),
        'Web'       =>array('.asp','.aspx','.cer','.cfm','.csr','.css','.htm','.html',
                     '.js','.jsp','.php','.rss','.xhtml'),	
        'Plugin'    =>array('.crx','.plugin'),
        'Font'      =>array('.fnt','.fon','.otf','.ttf'),
        'System'    =>array('.cab','.cpl','.cur','.deskthemepack','.dll','.dmp',
                        '.drv','.icns','.ico','.lnk','.sys'),
        'Settings'  =>array('.cfg','.ini','.prf'),		
        'Encoded'   =>array('.hqx','.mim','.uue'),	
        'Compressed'=>array('.7z','.cbr','.deb','.gz','.pkg','.rar','.rpm',
                            '.sitx','.tar','.gz','.zip','.zipx'),	
        'Disk'      =>array('.bin','.cue','.dmg','.iso','.mdf','.toast','.vcd'),	
        'Developer' =>array('.c','.class','.cpp','.cs','.dtd','.fla','.h',
                           '.java','.lua','.m','.pl','.py','.sh','.sln','.vcxproj',
                           '.xcodeproj'),
        'Backup'    =>array('.bak','.tmp'),
        'Misc'      =>array('.crdownload','.ics','.msi','.part','.torrent')
    );
?>
<!DOCTYPE html>
<html lang='en' xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta charset="UTF-8" />
    <META http-equiv="Pragma" CONTENT="no-cache">
    <META http-equiv="Expires" CONTENT="-1">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $brand; ?> - File Sharing system</title>
    
    <script language="javascript" src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script language="javascript" src="../js/jquery.browser.min.js" type="text/javascript"></script>	
    <script type="text/javascript">
        $(document).ready(function(){

                var fileTypes = {
                        Text:['.doc','.docx','.log','.msg','.odt','.pages','.rtf','.tex','.txt','.wpd','.wps'],
                        Data:['.csv','.dat','.gbr','.ged','.ibooks','.key','.keychain','.pps','.ppt','.pptx','.sdf','.tar','.tax2012','.vcf','.xml'],
                        Audio:['.aif','.iff','.m3u','.m4a','.mid','.mp3','.mpa','.ra','.wav','.wma'],
                        Video:['.3g2','.3gp','.asf','.asx','.avi','.flv','.m4v','.mov','.mp4','.mpg','.rm','.srt','.swf','.vob','.wmv'],
                        eBook:['.acsm','.aep','.apnx','.ava','.azw','.azw1','.azw3','.azw4','.bkk','.bpnueb','.cbc','.ceb','.dnl','.ebk','.edn','.epub','.etd','.fb2','.html0','.htmlz','.htxt','.htz4','.htz5','.koob','.lit','.lrf','.lrs','.lrx','.mart','.mbp','.mobi','.ncx','.oeb','.opf','.pef','.phl','.pml','.pmlz','.pobi','.prc','.qmk','.rzb','.rzs','.tcr','.tk3','.tpz','.tr','.tr3','.vbk','.webz','.ybk'],
                        image3d:['.3dm','.3ds','.max','.obj'],
                        Raster:['.bmp','.dds','.gif','.jpg''.jpeg','.png','.psd','.pspimage','.tga','.thm','.tif','.tiff','.yuv'],
                        Vector:['.ai','.eps','.ps','.svg'],			
                        Camera:['.3fr','.ari','.arw','.bay','.cr2','.crw','.dcr','.dng','.eip','.erf','.fff','.iiq','.k25','.kdc','.mef','.mos','.mrw','.nef','.nrw','.orf','.pef','.raf','.raw','.rw2','.rwl','.rwz','.sr2','.srf','.srw','.x3f'],
                        Layout:['.indd','.pct','.pdf'],	
                        Spreadsheet:['.xlr','.xls','.xlsx'],		
                        Database:['.accdb','.db','.dbf','.mdb','.pdb','.sql'],		
                        Executable:['.apk','.app','.bat','.cgi','.com','.exe','.gadget','.jar','.pif','.vb','.wsf'],	
                        Game:['.dem','.gam','.nes','.rom','.sav'],	
                        CAD:['.dwg','.dxf'],	
                        GIS:['.gpx','.kml','.kmz'],
                        Web:['.asp','.aspx','.cer','.cfm','.csr','.css','.htm','.html','.js','.jsp','.php','.rss','.xhtml'],	
                        Plugin:['.crx','.plugin'],
                        Font:['.fnt','.fon','.otf','.ttf'],
                        System:['.cab','.cpl','.cur','.deskthemepack','.dll','.dmp','.drv','.icns','.ico','.lnk','.sys'],
                        Settings:['.cfg','.ini','.prf'],		
                        Encoded:['.hqx','.mim','.uue'],	
                        Compressed:['.7z','.cbr','.deb','.gz','.pkg','.rar','.rpm','.sitx','.tar','.gz','.zip','.zipx'],	
                        Disk:['.bin','.cue','.dmg','.iso','.mdf','.toast','.vcd'],	
                        Developer:['.c','.class','.cpp','.cs','.dtd','.fla','.h','.java','.lua','.m','.pl','.py','.sh','.sln','.vcxproj','.xcodeproj'],
                        Backup:['.bak','.tmp'],
                        Misc:['.crdownload','.ics','.msi','.part','.torrent']
                };
                
                //set current page:
                var page_load_first = <?php if(isset($_GET['pager'])&&is_numeric($_GET['pager'])) echo $_GET['pager']; else echo 1; ?>;
                        switch(page_load_first){
                            case 1: $('#page_general').css({'z-index':'4'}); break;
                            case 2: $('#page_logfiles').css({'z-index':'4'}); break;
                            case 3: $('#page_searchfile').css({'z-index':'4'}); break;
                            case 4: $('#page_exclude').css({'z-index':'4'}); break;
                            case 5: $('#page_blocked').css({'z-index':'4'}); break;
                            case 6: $('#page_storage').css({'z-index':'4'}); break;
                            case 7: $('#page_users').css({'z-index':'4'}); break;
                            default: $('#page_general').css({'z-index':'4'});
                        }
        });
        </script>
        
        <script language="javascript" src="../js/script2.js" type="text/javascript"></script>
        
        <link href='http://fonts.googleapis.com/css?family=Prosto+One' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="../css/<?php echo $themeUse; ?>.css" type="text/css" media="screen" />	

<!--[if IE 9]>
  <style type="text/css">
    div {
       filter: none;
    }
  </style>
<![endif]-->	
</head>
<body>
<?php
if (isset($uname)) {
        if($uname!="nouser") {
            echo gettokenfield();
?>
<div class='outerConatainer'  style="margin:0 auto; max-width: 600px; min-width: 420px;" >
<table border='0'>
    <tr>
        <td colspan='3'>
            <div class='logoTakeThat'>
                <img src='../img/takethatlogo.png' />
            </div>
            <div class='logoBrand'>
                <span class='Brand' id='Brand'><?php echo $brand; ?></span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <p class='admin_head1'>Administration panel:</p>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <div class='border_div2'></div>
        </td>
    </tr>
    <tr>
        <td colspan='3' style='height:650px; vertical-align:top; position:relative;' valign='top'>
            <div class='tabs_admin'>
                <div class='tab_select' id='general'>General</div>
                <div class='tab_select' id='logfiles'>Log</div>
                <div class='tab_select' id='searchfile'>Search</div>
                <div class='tab_select' id='exclude'>Exclude</div>
                <div class='tab_select' id='blocked'>Blocked</div>
                <div class='tab_select' id='storage'>Storage</div>
                <div class='tab_select' id='users'>Users</div>
            </div>
            <div style='position:relative;'>
            <div class='tab_page' id='page_general'>
                <p class='admin_head2'>General settings</p>
<?php
                
/* current settings */
    $sqlstatus2 = "SELECT * FROM ".$table1." WHERE `id`='1'";
    $rs_result2 = mysqli_query($linkos, $sqlstatus2) or die ();
    
        while ($idr=mysqli_fetch_array($rs_result2)) {
            $id_ge          = $idr['id'];
            $user_ge        = $idr['db_user'];
            $pass_ge        = $idr['db_password'];
            $max_files_ge   = $idr['maxfiles'];
            $max_size_ge    = $idr['maxfile_size'];
            $max_rec_ge     = $idr['maxrecipients'];
            $brand_ge       = $idr['brand_name'];
            $types_ge       = $idr['accept_types'];
            $mail_ge        = $idr['server_mail'];
            $folder_ge      = $idr['files_folder'];
            $title_rec_ge   = $idr['e_auto_title'];
            $body_rec_ge    = $idr['e_auto_body'];
            $title_copy_ge  = $idr['e_auto_title_copy'];
            $body_copy_ge   = $idr['e_auto_body_copy'];
            $users_mode     = $idr['users_mode'];
            $theme_use      = $idr['theme'];
        }
    
/* which file types groups are set? */			
    $fileTypes_check = array(
        'Text'          =>'',
        'Data'          =>'',
        'Audio'         =>'',
        'Video'         =>'',
        'eBook'         =>'',
        'image3d'       =>'',
        'Raster'        =>'',
        'Vector'        =>'',
        'Camera'        =>'',
        'Layout'        =>'',
        'Spreadsheet'   =>'',
        'Database'      =>'',
        'Executable'    =>'',
        'Game'          =>'',
        'CAD'           =>'',
        'GIS'           =>'',
        'Web'           =>'',
        'Plugin'        =>'',
        'Font'          =>'',
        'System'        =>'',
        'Settings'      =>'',
        'Encoded'       =>'',
        'Compressed'    =>'',
        'Disk'          =>'',
        'Developer'     =>'',
        'Backup'        =>'',
        'Misc'		    =>''
    );		
    
    $fileset = explode(',', $types_ge);
    
    foreach ($fileset as $key => $value) { 
        foreach ($fileTypes as $types_r => $values_r) {
            if (in_array($value,$values_r)) 
                $fileTypes_check[$types_r] = 'CHECKED';
        }
    }
                        
?>
                <table class='storage_table3'>
                    <tr><td colspan='2' class='table_head1'>App theme:<div class='button_save_ge' id='theme_s'>save</div></td></tr>
                    <tr><td>
                        <select id='theme_select' class='selectBoxNew larger_selectBoxNew'>
                            <option value='darkStyle' <?php if ($theme_use == "dark") echo " selected"; ?>>Dark</option>
                            <option value='silverStyle' <?php if ($theme_use == "silver") echo " selected"; ?>>Silver</option>
                            <option value='custom' <?php if ($theme_use == "custom") echo " selected"; ?>>Custom</option>
                        </select><br />
                        <div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div>
                        </td>
                        <td class='help_td_ge'></td>
                    </tr>
                    <tr><td colspan='2' class='table_head1'>Admin User:<div class='button_save_ge' id='admin_change'>save</div></td></tr>
                    <tr>
                        <td>
                            <label  for="general_userName">
                                User Name: <span style='color:blue; font-size:0.8em; font-weight:bold;'> ( min 5 chars ) </span>
                            </label>
                            <input id='general_userName' type='text' value='<?php echo $user_ge; ?>' placeholder='user name min 5 chars' />
                        </td>

                        <td>
                            <label  for="general_userPass">
                                Admin Password:  <span style='color:blue; font-size:0.8em; font-weight:bold;'> ( min 6 chars ) </span>
                            </label>
                            <input id='general_userPass' type='password' value='' placeholder='Reset password min 6 chars' />
                        </td>                       
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct.</div>
                        </td>
                    </tr>
                    <tr><td colspan='2' class='table_head1'>Your Brand Name:<div class='button_save_ge' id='brand_s'>save</div></td></tr>
                    <tr><td><input id='general_brand' type='text' value='<?php echo $brand_ge; ?>' /><br /><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'></td></tr>
                    <tr><td colspan='2' class='table_head1'>Maximum files allowed:<div class='button_save_ge' id='max_files_s'>save</div></td></tr>
                    <tr><td><input id='general_max_files' type='text' value='<?php echo $max_files_ge; ?>' /><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>Server restriction: <?php echo SYS_MAX_UPLOADS; ?></td></tr>
                    <tr><td colspan='2' class='table_head1'>Maximum file size:<div class='button_save_ge' id='max_size_s'>save</div></td></tr>
                    <tr><td><input id='general_max_file_size' type='text' value='<?php echo $max_size_ge; ?>' /><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>Server restriction size single file: <?php echo SYS_MAX_FILESIZE; ?><br/>Server restriction POST size: <?php echo SYS_MAX_POST_SIZE; ?><br />Server restriction POST time: <?php echo SYS_MAX_INPUT_TIME.' sec'; ?></td></tr>
                    <tr><td colspan='2' class='table_head1'>File types allowed:<div class='button_save_ge' id='types_s'>save</div></td></tr>
                    <tr><td colspan='2'><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div>										
                                                    <table class='file_type_table' cellpadding='0' cellpadding='0'>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='1' type='checkbox' value='Text' <?php echo $fileTypes_check['Text']; ?>><label for="1" class="css-label dec_font" alt='doc,docx,log,msg,odt,pages,rtf,tex,txt,wpd,wps' title='doc,docx,log,msg,odt,pages,rtf,tex,txt,wpd,wps'>Text</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='2' type='checkbox' value='Data' <?php echo $fileTypes_check['Data']; ?>><label for="2" class="css-label dec_font" alt='csv,dat,gbr,ged,ibooks,key,keychain,pps,ppt,pptx,sdf,tar,tax2012,vcf,xml' title='csv,dat,gbr,ged,ibooks,key,keychain,pps,ppt,pptx,sdf,tar,tax2012,vcf,xml'>Data</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='3' type='checkbox' value='Audio' <?php echo $fileTypes_check['Audio']; ?>><label for="3" class="css-label dec_font" alt='aif,iff,m3u,m4a,mid,mp3,mpa,ra,wav,wma' title='aif,iff,m3u,m4a,mid,mp3,mpa,ra,wav,wma'>Audio</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='4' type='checkbox' value='Video' <?php echo $fileTypes_check['Video']; ?>><label for="4" class="css-label dec_font" alt='3g2,3gp,asf,asx,avi,flv,m4v,mov,mp4,mpg,rm,srt,swf,vob,wmv' title='3g2,3gp,asf,asx,avi,flv,m4v,mov,mp4,mpg,rm,srt,swf,vob,wmv'>Video</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='5' type='checkbox' value='eBook' <?php echo $fileTypes_check['eBook']; ?>><label for="5" class="css-label dec_font" alt='acsm,aep,apnx,ava,azw,azw1,azw3,azw4,bkk,bpnueb,cbc,ceb,dnl,ebk,edn,epub,etd,fb2,html0,htmlz,htxt,htz4,htz5,koob,lit,lrf,lrs,lrx,mart,mbp,mobi,ncx,oeb,opf,pef,phl,pml,pmlz,pobi,prc,qmk,rzb,rzs,tcr,tk3,tpz,tr,tr3,vbk,webz,ybk' title='acsm,aep,apnx,ava,azw,azw1,azw3,azw4,bkk,bpnueb,cbc,ceb,dnl,ebk,edn,epub,etd,fb2,html0,htmlz,htxt,htz4,htz5,koob,lit,lrf,lrs,lrx,mart,mbp,mobi,ncx,oeb,opf,pef,phl,pml,pmlz,pobi,prc,qmk,rzb,rzs,tcr,tk3,tpz,tr,tr3,vbk,webz,ybk'>eBook</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='6' type='checkbox' value='image3d' <?php echo $fileTypes_check['image3d']; ?>><label for="6" class="css-label dec_font" alt='3dm,3ds,max,obj' title='3dm,3ds,max,obj'>3D Image</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='7' type='checkbox' value='Raster' <?php echo $fileTypes_check['Raster']; ?>><label for="7" class="css-label dec_font" alt='bmp,dds,gif,jpg,png,psd,pspimage,tga,thm,tif,tiff,yuv' title='bmp,dds,gif,jpg,png,psd,pspimage,tga,thm,tif,tiff,yuv'>Raster Image</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='8' type='checkbox' value='Vector' <?php echo $fileTypes_check['Vector']; ?>><label for="8" class="css-label dec_font" alt='ai,eps,ps,svg' title='ai,eps,ps,svg'>Vector Image</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='9' type='checkbox' value='Camera' <?php echo $fileTypes_check['Camera']; ?>><label for="9" class="css-label dec_font" alt='3fr,ari,arw,bay,cr2,crw,dcr,dng,eip,erf,fff,iiq,k25,kdc,mef,mos,mrw,nef,nrw,orf,pef,raf,raw,rw2,rwl,rwz,sr2,srf,srw,x3f' title='3fr,ari,arw,bay,cr2,crw,dcr,dng,eip,erf,fff,iiq,k25,kdc,mef,mos,mrw,nef,nrw,orf,pef,raf,raw,rw2,rwl,rwz,sr2,srf,srw,x3f'>Camera Raw</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='10' type='checkbox' value='Layout' <?php echo $fileTypes_check['Layout']; ?>><label for="10" class="css-label dec_font" alt='indd,pct,pdf' title='indd,pct,pdf'>Page Layout</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='11' type='checkbox' value='Spreadsheet' <?php echo $fileTypes_check['Spreadsheet']; ?>><label for="11" class="css-label dec_font" alt='xlr,xls,xlsx' title='xlr,xls,xlsx'>Spreadsheet</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='12' type='checkbox' value='Database' <?php echo $fileTypes_check['Database']; ?>><label for="12" class="css-label dec_font" alt='accdb,db,dbf,mdb,pdb,sql' title='accdb,db,dbf,mdb,pdb,sql'>Database</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='13' type='checkbox' value='Executable' <?php echo $fileTypes_check['Executable']; ?>><label for="13" class="css-label dec_font" alt='apk,app,bat,cgi,com,exe,gadget,jar,pif,vb,wsf' title='apk,app,bat,cgi,com,exe,gadget,jar,pif,vb,wsf'>Executable</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='14' type='checkbox' value='Game' <?php echo $fileTypes_check['Game']; ?>><label for="14" class="css-label dec_font" alt='dem,gam,nes,rom,sav' title='dem,gam,nes,rom,sav'>Game</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='15' type='checkbox' value='CAD' <?php echo $fileTypes_check['CAD']; ?>><label for="15" class="css-label dec_font" alt='dwg,dxf' title='dwg,dxf'>CAD</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='16' type='checkbox' value='GIS' <?php echo $fileTypes_check['GIS']; ?>><label for="16" class="css-label dec_font" alt='gpx,kml,kmz' title='gpx,kml,kmz'>GIS</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='17' type='checkbox' value='Web' <?php echo $fileTypes_check['Web']; ?>><label for="17" class="css-label dec_font" alt='asp,aspx,cer,cfm,csr,css,htm,html,js,jsp,php,rss,xhtml' title='asp,aspx,cer,cfm,csr,css,htm,html,js,jsp,php,rss,xhtml'>Web</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='18' type='checkbox' value='Plugin' <?php echo $fileTypes_check['Plugin']; ?>><label for="18" class="css-label dec_font" alt='crx,plugin' title='crx,plugin'>Plugin</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='19' type='checkbox' value='Font' <?php echo $fileTypes_check['Font']; ?>><label for="19" class="css-label dec_font" alt='fnt,fon,otf,ttf' title='fnt,fon,otf,ttf'>Font</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='20' type='checkbox' value='System' <?php echo $fileTypes_check['System']; ?>><label for="20" class="css-label dec_font" alt='cab,cpl,cur,deskthemepack,dll,dmp,drv,icns,ico,lnk,sys' title='cab,cpl,cur,deskthemepack,dll,dmp,drv,icns,ico,lnk,sys'>System</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='21' type='checkbox' value='Settings' <?php echo $fileTypes_check['Settings']; ?>><label for="21" class="css-label dec_font" alt='cfg,ini,prf' title='cfg,ini,prf'>Settings</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='22' type='checkbox' value='Encoded' <?php echo $fileTypes_check['Encoded']; ?>><label for="22" class="css-label dec_font" alt='hqx,mim,uue' title='hqx,mim,uue'>Encoded</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='23' type='checkbox' value='Compressed' <?php echo $fileTypes_check['Compressed']; ?>><label for="23" class="css-label dec_font" alt='7z,cbr,deb,gz,pkg,rar,rpm,sitx,tar,gz,zip,zipx' title='7z,cbr,deb,gz,pkg,rar,rpm,sitx,tar,gz,zip,zipx'>Compressed</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='24' type='checkbox' value='Disk' <?php echo $fileTypes_check['Disk']; ?>><label for="24" class="css-label dec_font" alt='bin,cue,dmg,iso,mdf,toast,vcd' title='bin,cue,dmg,iso,mdf,toast,vcd'>Disk Image</label></td>
                                                        </tr>
                                                        <tr>
                                                            <td><input class='allowed_type_files css-checkbox' id='25' type='checkbox' value='Developer' <?php echo $fileTypes_check['Developer']; ?>><label for="25" class="css-label dec_font" alt='c,class,cpp,cs,dtd,fla,h,java,lua,m,pl,py,sh,sln,vcxproj,xcodeproj' title='c,class,cpp,cs,dtd,fla,h,java,lua,m,pl,py,sh,sln,vcxproj,xcodeproj'>Developer</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='26' type='checkbox' value='Backup' <?php echo $fileTypes_check['Backup']; ?>><label for="26" class="css-label dec_font" alt='bak,tmp' title='bak,tmp'>Backup</label></td>
                                                            <td><input class='allowed_type_files css-checkbox' id='27' type='checkbox' value='Misc' <?php echo $fileTypes_check['Misc']; ?>><label for="27" class="css-label dec_font" alt='crdownload,ics,msi,part,torrent' title='crdownload,ics,msi,part,torrent'>Misc</label></td>
                                                            <td></td>
                                                        </tr>														
                                                    </table>
                                                    
                                                    </td></tr>
                    <tr><td colspan='2' class='table_head1'>Maximum recipients:<div class='button_save_ge' id='max_rec_s'>save</div></td></tr>
                    <tr><td><input id='general_max_rec' type='text' value='<?php echo $max_rec_ge; ?>' /><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>Server restriction: <?php echo (SYS_MAX_INPUT_VARS-SYS_MAX_UPLOADS-6); ?></td></tr>
                    
                    <tr><td colspan='2' class='table_head1'>Server E-mail address:<div class='button_save_ge' id='max_email_s'>save</div></td></tr>
                    <tr><td><input id='general_email' type='text' value='<?php echo $mail_ge; ?>' /><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>Server Host: <?php echo SERVERHOST; ?></td></tr>
                    
                    <tr><td colspan='2' class='table_head1'>Files folder URL:<div class='button_save_ge' id='folder_s'>save</div></td></tr>
                    <tr><td><input id='general_folder' type='text' value='<?php echo $folder_ge; ?>' /><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>include full path.</td></tr>
                    
                    <tr><td colspan='2' class='table_head1'>Message Construct - recipient:<div class='button_save_ge' id='mes_rec_s'>save</div></td></tr>
                    <tr><td><input id='general_mes_rec_title' type='text' value='<?php echo $title_rec_ge; ?>' /><br/><textarea class='css-textarea' id='general_mes_rec_body'><?php echo $body_rec_ge; ?></textarea><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>title <br /><br />custom body</td></tr>
                    
                    <tr><td colspan='2' class='table_head1'>Message Construct - Copy to sender:<div class='button_save_ge' id='mes_cop_s'>save</div></td></tr>
                    <tr><td><input id='general_mes_cop_title' type='text' value='<?php echo $title_copy_ge; ?>' /><br/><textarea class='css-textarea' id='general_mes_cop_body'><?php echo $body_copy_ge; ?></textarea><div class='error_page_ge' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct or violates server restrictions.</div></td><td class='help_td_ge'>title <br /><br />custom body</td></tr>
                
                </table>
            
            </div>
            

            <div class='tab_page' id='page_users' style='overflow-y:scroll;'>
                <p class='admin_head2 row_backcolor1'>Users Mode:<img src='../img/glyphicons/png/glyphicons_136_cogwheel2.png' class='expand_users_action_display' /></p>
                    <div style='display:none'>
                        <table border='0' cellspacing='10px'>                    
                            <tr>
                                <td><p style='margin:0;'>Users Mode: </p></td>
                                <td>
                                    <select id='users_mode_select' class='selectBoxNew larger_selectBoxNew'>
                                        <option value='guests' <?php if ($users_mode=='guests') echo " SELECTED"; ?>>Guests Only</option>
                                        <option value='users' <?php if ($users_mode=='users') echo " SELECTED"; ?>>Users Only</option>
                                        <option value='users-guests' <?php if ($users_mode=='users-guests') echo " SELECTED"; ?>>Users & Guests</option>
                                    </select>
                                </td>
                                <td align='right'  style='text-align:right;'><div class='css5button guestsMode' id='but_submit_users_mode' style='margin:0px 0px 0px 30px;'>Update Mode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>                            
                            </tr>                           
                        </table>
                    </div>
                <p class='admin_head2 row_backcolor1'>Guest Account:<img src='../img/glyphicons/png/glyphicons_136_cogwheel2.png' class='expand_users_action_display' /></p>
                    <div style='display:none'>
                        <div class='error_catcher' id='valid_of_guest_user' style='display:none'>
                            <div class='closeMessageVal'></div>
                        </div>  
                        <?php
                            $sqlstatus199 = "SELECT * FROM $table7 LIMIT 1";
                            $rs_result199 = mysqli_query($linkos, $sqlstatus199) or die (); 
                                while ($idr = mysqli_fetch_array($rs_result199)) {
                                
                                    $guest_id[]             = $idr['id'];
                                    $guest_maxfiles[]       = $idr['maxfiles'];
                                    $guest_maxsize[]        = $idr['maxsize'];
                                    $guest_maxrec[]         = $idr['maxrec'];
                                }
                            
                        ?>                      
                        <table border='0' cellspacing='10px'>                      
                            <tr>
                                <td><p style='margin:0;'>Max file Size: </p></td>
                                <td><select id='guest_MaxFsize_new' class='selectBoxNew'>
                                    <?php

                                            $devider = 0.1;
                                            for ($i = 0; $i<10; $i++) {
                                                $byte_size = round($max_size_ge*$devider);
                                                $devider += 0.1;
                                                if ($guest_maxsize[0] == $byte_size) $selected = "selected"; else $selected = "";
                                                echo "<option value='".$byte_size."' ".$selected.">".humanFileSize($byte_size)."</option>";
                                            }
                                    ?>
                                    </select>                            
                                </td>
                                <td><p style='margin:0;'>Max files: </p></td>
                                <td><select id='guest_MaxFnum_new' class='selectBoxNew'> 
                                    <?php
                                        for ($i = 0; $i<$max_files_ge; $i++) {
                                            if ($guest_maxfiles[0] == $i+1) $selected = "selected"; else $selected = "";
                                            echo "<option value='".($i+1)."' ".$selected.">".($i+1)."</option>";
                                        }
                                    ?>
                                    </select>
                                </td>                            
                            </tr>   
                            <tr>
                                <td><p style='margin:0;'>Max recipients: </p></td>
                                <td><input id='guest_MaxRec_new' type='text' value='<?php  echo $guest_maxrec[0];  ?>' class='new_user_add' placeholder='Max recipients' /></td>
                                <td colspan='2' align='right'  style='text-align:right;'><div class='css5button moveright' id='but_submit_guest' style='margin:0px 0px 0px 75px;'>Update Guest&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>                            
                            </tr>                           
                        </table>
                    </div>
                <p class='admin_head2 row_backcolor1'>Create Users:<img src='../img/glyphicons/png/glyphicons_136_cogwheel2.png' class='expand_users_action_display' /></p>
                    <div style='display:none'>
                        <div class='error_catcher' id='valid_of_new_user' style='display:none'>
                            <div class='closeMessageVal'></div>
                        </div>             
                        <table border='0' cellspacing='10px'>
                            <tr>
                                <td><p style='margin:0;'>User Name: </p></td>
                                <td><input id='user_name_new' type='text' value='' class='new_user_add' placeholder='user name -> Min 5 chars' /></td>
                                <td><p style='margin:0;'>Password: </p></td>
                                <td><input id='user_pass_new' type='text' value='' class='new_user_add' placeholder='user password -> Min 5 chars' /></td>                            
                            </tr>
                            <tr>
                                <td><p style='margin:0;'>Full name: </p></td>
                                <td><input id='user_Fname_new' type='text' value='' class='new_user_add' placeholder='User full name' /></td>
                                <td><p style='margin:0;'>User mail: </p></td>
                                <td><input id='user_Email_new' type='text' value='' class='new_user_add' placeholder='Valid user Email' /></td>                            
                            </tr>                          
                            <tr>
                                <td><p style='margin:0;'>Max file Size: </p></td>
                                <td><select id='user_MaxFsize_new' class='selectBoxNew'>
                                    <?php

                                            $devider = 0.1;
                                            for ($i = 0; $i<10; $i++) {
                                                $byte_size = round($max_size_ge*$devider);
                                                $devider += 0.1;
                                                echo "<option value='".$byte_size."'>".humanFileSize($byte_size)."</option>";
                                            }
                                    ?>
                                    </select>                            
                                </td>
                                <td><p style='margin:0;'>Max files: </p></td>
                                <td><select id='user_MaxFnum_new' class='selectBoxNew'> 
                                    <?php
                                        for ($i = 0; $i<$max_files_ge; $i++) {
                                            echo "<option value='".($i+1)."'>".($i+1)."</option>";
                                        }
                                    ?>
                                    </select>
                                </td>                            
                            </tr>   
                            <tr>
                                <td><p style='margin:0;'>Max recipients: </p></td>
                                <td><input id='user_MaxRec_new' type='text' value='' class='new_user_add' placeholder='Max recipients' /></td>
                                <td colspan='2' align='right'  style='text-align:right;'><div class='css5button' id='but_submit_new_user' style='margin:0px 0px 0px 75px;'>Add user&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>                            
                            </tr>                           
                        </table>
                    </div>
                <p class='admin_head2 row_backcolor1'>Manage Users:</p>
                    <?php
                        $sqlstatus99 = "SELECT * FROM ".$table6." ORDER BY `username` ASC";
                        $rs_result99 = mysqli_query($linkos, $sqlstatus99) or die (); 
                        $users_id = array();
                            while ($idr = mysqli_fetch_array($rs_result99)) {
                            
                                $users_id[]             = $idr['id'];
                                $users_username[]       = $idr['username'];
                                $users_password[]       = $idr['password'];
                                $users_fullname[]       = $idr['fullname'];
                                $users_maxfiles[]       = $idr['maxfiles'];
                                $users_maxsize[]        = $idr['maxsize'];
                                $users_maxrec[]         = $idr['maxrec'];
                                $users_usermail[]       = $idr['usermail'];
                                $users_added[]          = $idr['added'];
                                $users_active[]         = $idr['active'];

                            }
                        
                    ?>  
                    <table class='storage_table3' border='0' cellpadding='0' cellspacing='0' style='border:0;'>
                        <tr>
                            <td class='table_head1' style='text-align:center;'>User name</td>
                            <td class='table_head1' style='text-align:center;'>Full name </td>
                            <td class='table_head1' style='text-align:center;'>E-mail</td>
                            <td class='table_head1' style='text-align:center;'>actions</td>
                        </tr>
                    <?php
                        foreach ($users_id as $key => $value) {
                            
                            if($users_active[$key] == 'yes') $pic_active = 'active.png'; else $pic_active = 'inactive.png';
                        echo "<tr>";
                            echo "<td style='position:relative'><img src='../img/".$pic_active."' width='15px' height='15px'  style='position:relative; top:3px' alt='Activation mode' title='Activation mode' />&nbsp;&nbsp;".$users_username[$key]."</td>";
                            echo "<td>".$users_fullname[$key]."</td>";
                            echo "<td>".$users_usermail[$key]."</td>";
                            echo "<td width='124px'><div class='wrap_buttons_users'><div class='update_row_user'>Update</div><div class='delete_row_user' rowId='".$users_id[$key]."' >Delete</div></div></td>";
                        echo "</tr>";
                        echo "<tr>";
                            echo "<td colspan='4' style='padding:2px 5px 2px 5px; border:0; border-bottom:1px solid white !important;'>";
                                echo "<div class='row_info_users' style='display:none'>
                                        <table border='0' cellspacing='5px' width='90%'>
                                            <tr>
                                                <td><p style='margin:0;'>User Name: </p></td>
                                                <td><input id='user_name_update' type='text' value='".$users_username[$key]."' class='new_user_add' placeholder='user name -> Min 5 chars' /></td>
                                                <td><p style='margin:0;'>Password: </p></td>
                                                <td><input id='user_pass_update' type='text' value='' class='new_user_add' placeholder='Reset password' /></td>                            
                                            </tr>
                                            <tr>
                                                <td><p style='margin:0;'>Full name: </p></td>
                                                <td><input id='user_Fname_update' type='text' value='".$users_fullname[$key]."' class='new_user_add' placeholder='User full name' /></td>
                                                <td><p style='margin:0;'>User mail: </p></td>
                                                <td><input id='user_Email_update' type='text' value='".$users_usermail[$key]."' class='new_user_add' placeholder='Valid user Email' /></td>                            
                                            </tr>                          
                                            <tr>
                                                <td><p style='margin:0;'>Max file Size: </p></td>
                                                <td><select id='user_MaxFsize_update' class='selectBoxNew'>";
                                                            $devider = 0.1;
                                                            for ($i = 0; $i<10; $i++) {
                                                                $byte_size = round($max_size_ge*$devider);
                                                                $devider += 0.1;
                                                                if ($users_maxsize[$key] == $byte_size) $selected = "selected"; else $selected = "";
                                                                echo "<option value='".$byte_size."' ".$selected.">".humanFileSize($byte_size)."</option>";
                                                            }
                                          echo "</select>                            
                                                </td>
                                                <td><p style='margin:0;'>Max files: </p></td>
                                                <td><select id='user_MaxFnum_update' class='selectBoxNew'>";
                                                        for ($i = 0; $i<$max_files_ge; $i++) {
                                                            if ($users_maxfiles[$key] == $i+1) $selected = "selected"; else $selected = "";
                                                            echo "<option value='".($i+1)."' ".$selected.">".($i+1)."</option>";
                                                        }
                                                    echo "</select>
                                                </td>                            
                                            </tr>   
                                            <tr>";
                                                if ($users_active[$key] == 'yes') { $activate_yes = "selected"; $activate_no = ""; } else { $activate_yes = ""; $activate_no = "selected"; }
                                            echo "<td><p style='margin:0;'>Max recipients: </p></td>
                                                <td><input id='user_MaxRec_update' type='text' value='".$users_maxrec[$key]."' class='new_user_add' placeholder='Max recipients' /></td>
                                                <td><p style='margin:0;'>Activation: </p></td>
                                                <td>
                                                    <select id='user_activate_status' class='selectBoxNew'>
                                                        <option value='yes' ".$activate_yes.">Active</option>
                                                        <option value='no' ".$activate_no.">Inactive</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan='4'><div class='css6button but_submit_update_user' id='but_submit_update_user' rowId='".$users_id[$key]."' style='margin:0px 0px 0px 165px;'>update user&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>                            
                                            </tr>                           
                                        </table>                                
                                      </div>";
                            echo "</td>";
                        echo "</tr>";
                        
                        }
                    ?>
                    </table>
            </div>            
            
            
            <div class='tab_page' id='page_logfiles'><p class='admin_head2'>Files log:</p>
                    <table class='storage_table2'><tr><td class='table_head1'>Starting Date</td><td class='table_head1'>End Date</td><td class='table_head1'>Search log</td></tr>
                        <tr><td><input type='text' id='search_start_date' placeholder='yyyy-mm-dd' /></td><td><input type='text' id='search_end_date' placeholder='yyyy-mm-dd' /></td><td style='text-align:center;' align='center'><div class='search_but' id='search_log'><img src='../img/glyphicons/png/glyphicons_029_notes_2.png' /></div></td></tr>
                    </table>
                    <div id='log_page_error' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct Check carefully the dates format (yyyy-mm-dd)</div>
                    <div id='log_page_res_conn' style='position:absolute; display:none; width:100%;' >
                    </div>
            </div>
            <div class='tab_page' id='page_searchfile'><p class='admin_head2'>Search by E-mail (sender): </p>
                    <table class='storage_table2'><tr><td class='table_head1'>User E-mail</td><td class='table_head1'>Submit</td></tr>
                        <tr><td><input type='text' id='search_mail_input' placeholder='example@mail.com' /></td><td style='text-align:center;' align='center'><div class='search_but' id='search_user'><img src='../img/glyphicons/png/glyphicons_029_notes_2.png' /></div></td></tr>
                    </table>
                    <div id='search_page_error' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct Check carefully the Email format.</div>
                    <div id='search_page_res_conn' style='position:absolute; display:none; width:100%;' >
                    </div>
            </div>
            <div class='tab_page' id='page_exclude'><p class='admin_head2'>Exclude a user:</p>
                    <table class='storage_table2'><tr><td class='table_head1'>User E-mail</td><td class='table_head1'>Admin note</td><td class='table_head1'>Submit</td></tr>
                        <tr><td><input type='text' id='email_ex_input' placeholder='example@mail.com' /></td><td><input type='text' id='com_ex_input' placeholder='write the reason' /></td><td style='text-align:center;' align='center'><div class='remove_but' id='add_ex'><img src='../img/glyphicons/png/glyphicons_006_user_add.png' /></div></td></tr>
                    </table>
                    <div id='ex_page_error' style='color:red; font-size:0.8em; display:none;'>&nbsp;&nbsp;>&nbsp;Your Input is not correct Check carefully the Email format.</div>
                    <p class='admin_head2'>Excluded users</p>
<?php
    $sqlstatus3 = "SELECT * FROM ".$table5." ORDER BY `when_added` DESC";
    $rs_result3 = mysqli_query($linkos, $sqlstatus3) or die (); 
        while ($idr = mysqli_fetch_array($rs_result3)) {
            $id_ex[]    = $idr['id'];
            $email_ex[] = $idr['email_address'];
            $com_ex[]   = $idr['comment'];
            $time_ex[]  = $idr['when_added'];
        }
        echo "<table class='storage_table2'><tr><td class='table_head1'>".
             "User excluded</td>".
             "<td class='table_head1'>Admin note</td><td class='table_head1'>".
             "Excluded date</td><td class='table_head1'>Remove</td></tr>";
                        
        if (isset($id_ex)) {
            foreach ($id_ex as $key => $value) {
                echo "<tr><td>".$email_ex[$key]."</td><td>".$com_ex[$key].
                     "</td><td>".$time_ex[$key]."</td><td style='text-align".
                     ":center;' align='center'><div class='remove_but remove_ex_but' ".
                     "id='remove_ex'><img src='../img/glyphicons/png/glyphicons_207".
                     "_remove_2.png' /></div><input type='hidden' id='id_ex' value='".
                     $value."' /></td></tr>";
            }
        }
        
        echo "</table>";
?>
            </div>
            <div class='tab_page' id='page_blocked'><p class='admin_head2'>Blocked users:</p>
<?php
    $sqlstatus4 = "SELECT * FROM ".$table3." ORDER BY `when_blocked` DESC";
    $rs_result4 = mysqli_query($linkos, $sqlstatus4) or die (); 
    while ($idr = mysqli_fetch_array($rs_result4)) {
        $id_block[]     = $idr['id'];
        $ip_block[]     = $idr['ip_user'];
        $ua_block[]     = $idr['user_agent'];
        $time_block[]   = $idr['when_blocked'];
    }
    
    echo "<table class='storage_table2'><tr><td class='table_head1'>IP blocked".
         "</td><td class='table_head1'>User Agent</td><td class='table_head1'>".
         "Blocking date</td><td class='table_head1'>Remove</td></tr>";
    
    if (isset($id_block)) {
        foreach ($id_block as $key => $value) {
            echo "<tr><td>".$ip_block[$key]."</td><td><span alt='".
                 $ua_block[$key]."' title='".$ua_block[$key]."'>".
                 substr($ua_block[$key],0,30)."<span></td><td>".
                 $time_block[$key]."</td><td style='text-align:center;' align='".
                 "center'><div class='remove_but remove_block_but' id='remove_bl".
                 "ocked'><img src='../img/glyphicons/png/glyphicons_207_remove_2".
                 ".png' /></div><input type='hidden' id='id_block' value='".
                 $value."' /></td></tr>";
        }
    }
    
    echo "</table>";
?>
            </div>
            <div class='tab_page' id='page_storage'>
            
                <p class='admin_head2'>CleanUp Task:</p>
                    <div class='error_catcher2' id='errorMessageOfCleanUp' style='display:none'>
                        <div class='closeMessageVal2'></div>
                        Can't Run CleanUp Something went wrong! Code:87623
                    </div>
                    <div id='cleanUpSetParams' style='margin:0'>
                        <table border="0">
                            <tr>
                                <td>
                                    <p style='margin:0;'>Delete files & records older then:</p>
                                </td>
                                <td>
                                    <select id="selectWeeksInterval" class="selectBoxNew smaller_selectBoxNew">
                                        <option value='1'>1 week old</option>
                                        <option value='2'>2 weeks old</option>
                                        <option value='3'>3 weeks old</option>
                                        <option value='4'>1 month old</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="css5button cleanUpButton" id="but_cleanUp" style="margin:0px 0px 0px 30px;">
                                        Strat CleanUp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>                            
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id='runningCleanUp' style='text-align:center; display:none'>
                        <span class='progressClean'>processing...</span>
                        <br />
                        <img src='../img/bar.gif' />
                    </div>                    
                    
                <p class='admin_head2'>Storage summary:</p>
<?php
    $scanned_directory = array_diff(scandir($files_path), array('..', '.'));
                
    $total_files                        = array();
    $total_files['total_files_all']     = count($scanned_directory);
    $total_files['total_files_size']    = 1;
                
    foreach($scanned_directory as $key => $value ) {
        // file type
        $type = explode('.', $value);
        $type = $type[count($type)-1];
        
        // file size
        $size = filesize($files_path.'/'.$value);
                    
        if (isset($total_files[$type])) { 
            $total_files[$type]['count']++; 
            $total_files[$type]['size'] += $size;
        
        } else { 
        
            $total_files[$type] = array(
                'count' => 0,
                'size' => 0
            ); 
            
            $total_files[$type]['count']++; 
            $total_files[$type]['size'] += $size; 
        }		
        
        $total_files['total_files_size'] += $size;
    }
    
    echo "<table class='storage_table1'><tr><td class='table_head1'>Files count".
         "</td><td class='table_head1'>Files size</td></tr><tr><td>".
         $total_files['total_files_all']."</td><td>".
         humanFileSize($total_files['total_files_size']).
         "</td></tr></table>";
          
    echo "<p class='admin_head2'>Files types & sizes:</p>";
    echo "<table class='storage_table2'><tr><td class='table_head1'>File type".
         "</td><td class='table_head1'>Files count</td><td class='table_head1'>".
         "Files size</td><td class='table_head1'>Storage Percentage</td></tr>";
    
    foreach ($total_files as $key => $value) {
        if ($key!='total_files_all' && $key!='total_files_size') {
            echo "<tr><td>".$key."</td><td>".$value['count']."</td><td>".
                 humanFileSize($value['size'])."</td><td>".
                 round(($value['size']/$total_files['total_files_size']*100), 1).
                 "%</td></tr>";
        }
    }
    
    echo "</table>";
?>
            </div>
        
            </div>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3' class='button_td'><div class='button_td'><img src='../img/glyphicons/png/glyphicons_387_log_out.png' class='envelop' width='18px' height='16px' style='left:60%'  /><input class='css3button gradient' type='button' id='but1out' value='Log Out&nbsp;&nbsp;&nbsp;&nbsp;' /></div></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
            exit;
        } else {
?>
<div class='outerConatainer'  style="margin:0 auto;">
<form action='admin.php' method='post'  class='hiddder'>
<table border='0'>
    <tr>
        <td colspan='3'>
            <div class='logoTakeThat'><img src='../img/takethatlogo.png' /></div>
            <div class='logoBrand'><span class='Brand' id='Brand'><?php echo $brand; ?></span></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3'><p class='admin_head1'>Administration panel:</p></div></td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div2'></div></td>
    </tr>
    <tr>
        <td colspan='3'>
                        <p class='sec_form'>User name:</p>
                        <input name='access_user' type='text' />
                        <br />
                        <p class='sec_form' style='margin-top:5px;'>Password:</p>
                        <input name='access_password' type='password' style='margin-bottom:8px;' />
                        <?php echo gettokenfield(); ?>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3' class='button_td'><div class='button_td'><img src='../img/glyphicons/png/glyphicons_386_log_in.png' class='envelop' width='18px' height='16px' /><input class='css3button gradient' type='submit' id='but1in' value='Log In&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' /></div></td>
    </tr>
</table>
</form>
</div>
</body>
</html>
<?php
            exit;			
        }
    } else {
?>
<div class='outerConatainer'  style="margin:0 auto;">
<form action='admin.php' method='post'  class='hiddder'>
<table border='0'>
    <tr>
        <td colspan='3'>
            <div class='logoTakeThat'><img src='../img/takethatlogo.png' /></div>
            <div class='logoBrand'><span class='Brand' id='Brand'><?php echo $brand; ?></span></div>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3' style='background-color:#585858'>
            <p class='admin_head1'>Administration panel:</p>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div2'></div></td>
    </tr>
    <tr>
        <td colspan='3'>
                        <p class='sec_form'>User name:</p>
                        <input name='access_user' type='text' />
                        <br />
                        <p class='sec_form' style='margin-top:5px;'>Password:</p>
                        <input name='access_password' type='password' style='margin-bottom:8px;' />
                        <?php echo gettokenfield(); ?>
        </td>
    </tr>
    <tr>
        <td colspan='3'><div class='border_div'></div></td>
    </tr>
    <tr>
        <td colspan='3' class='button_td'>
            <div class='button_td'>
                <img src='../img/glyphicons/png/glyphicons_386_log_in.png' class='envelop' width='18px' height='16px' />
                <input class='css3button gradient' type='submit' id='but1in' value='Log In&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' />
            </div>
        </td>
    </tr>
</table>
</form>
</div>
</body>
</html>
<?php
    }
    mysqli_close($linkos);	

    /* functions */
    function humanFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }
    
?>
