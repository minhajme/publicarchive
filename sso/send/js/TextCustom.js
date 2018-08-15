/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2.1
// Creation Date: 12/09/2013
// Updated To V.2.1 : 05/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/

/**************************** TEXT CUSTUMIZATION: *****************************/

//PLACEHOLDER FOR FILES FIELD:
    var placeholder_files_field = "select a file";
    
//PLACEHOLDER FOR RECIPIENTS FIELD:
    var placeholder_recipients_field = "recipient e-mail address";
    
//SENDING PROGRESS BAR FALLBACK TEXT:
    var progress_bar_fallback = "Sending your file please wait...";

//VALIDATION MESSAGE: MISSING FILE / NO FILE WAS CHOSEN:
    var validation_mis_file_select = "Please select a file to send.";
    
//VALIDATION MESSAGE: MISSING RECIPIENTS ADDRESS (AT LEAST ONE ADDRESS NEEDED):
    var validation_mis_recipient_select = "Please type the destination E-mail in \"Recipients\" field.";
    
//VALIDATION MESSAGE: MISSING SENDER ADDRESS:
    var validation_mis_sender_select = "Please type your E-mail address in \"From\" field.";
    
//VALIDATION MESSAGE: INVALID EMAIL ADDRESS IN RECIPIENTS FIELDS:
    var validation_invalid_recipient = "Please type a valid E-mail address in \"Recipients\" field.";
    
//VALIDATION MESSAGE: INVALID EMAIL ADDRESS IN SENDER FIELD:
    var validation_invalid_sender = "Please type a valid E-mail address in \"From\" field.";
    
//VALIDATION MESSAGE: UNAUTHORIZED FILE\S TYPE SELECTED:
    var validation_unauthorized_file_type = "Unauthorized file type.";
    
//VALIDATION MESSAGE:  UNAUTHORIZED FILE\S SIZE UPLOAD:
    var validation_unauthorized_file_size = "File size exceeded the maximum size permitted.";

//VALIDATION MESSAGE:  UNKNOWN USER BEHAVIOR MAYBE A BOT VALIDATION IS UNKNOWN:
    var validation_human_detection_behavior = "You are missing something please take a second look.";
    
//VALIDATION MESSAGE:  FILE SENT SUCCESSFULLY:
    var validation_file_sent_done = "File/s sent successfully!";
    
//UNKNOWN ERROR - CRITICAL:
    var error_unknown_critical = "That's embarrassing... something went wrong!";
    
//ERROR COULD NOT SEND A COPY MESSAGE (INTERNAL ERROR):
    var error_copy_send = "Could not send a copy to your e-mail";
    
//ERROR HEADER:
    var error_header_message = "Something went wrong: ";

//CONTACT ADMIN IN CRITICAL ERRORS FOOTER:
    var error_admin_footer = "Please contact website administrator with this error.";

//ERROR CRITICAL POSIBLE COOKIES DISABLED:    
    var error_critical_cookies = "That's embarrassing... something went wrong! it may be you cookies please enable them.";

//ERROR USER IS BLOCKED AND NOT AUTHORIZED ANY MORE:       
    var error_blocked_user = "You are not authorized to use this website any more! <br /> Contact admin for additional help.";
    
//OLD BROWSER NOTICE:
    var old_browser_notice = "Your browser is too old... please update and try again.";