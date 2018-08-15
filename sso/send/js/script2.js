/******************************************************************************/
// Created by: shlomo hassid.
// Release Version : 2.1
// Creation Date: 12/09/2013
// Updated To V.2.1 : 05/01/2014
// Mail: Shlomohassid@gmail.com
// require: jquery latest version SQL 4+ PHP 5.3+ .	
// Copyright 2013, shlomo hassid.
/******************************************************************************/

$(document).ready(function(){

		//Logout button:
		$('#but1out').click(function(){ window.location = "admin.php?logout"; });
		
		//Tab button:		
		$('.tab_select').click(function(){ var pager = $(this).attr('id'); $('.tab_page').css({'z-index':'2'}); $('#page_'+pager).css({'z-index':'4'}); });

 		//General Settings:
			$('.button_save_ge').click(function(){
				$('div.error_page_ge').slideUp('fast');
				var object_ge_error = $(this).closest('tr').next('tr').find('div.error_page_ge').eq(0);
                var object_ge_error2 = $(this).closest('tr').next('tr').next('tr').find('div.error_page_ge').eq(0);
				var what_save = $(this).attr('id');
					switch(what_save){
                    
						case 'theme_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													theme_selected: $('select#theme_select').val()
													
												 };

 									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 
								break;
                                
						case 'admin_change':
                                    
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													userName: $('input#general_userName').val(),
                                                    userPass: $('input#general_userPass').val()
													
												 };
          
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
                                                
												if (response=='OK!'||response=='die5') window.location.href="admin.php";
												else {
                                                    console.log(response);
													$(object_ge_error2).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});
								break; 
                                
						case 'brand_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													brand_name_ge: $('input#general_brand').val()
													
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});
								break;
								
						case 'max_files_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													max_files_ge: $('input#general_max_files').val()
													
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});								
								break;
								
						case 'max_size_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													max_size_ge: $('input#general_max_file_size').val(),
													max_size_num_files: $('input#general_max_files').val()
													
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													console.log(response);
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});									
								break;
						case 'types_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													Text: $('input#1').is(':checked'),
													Data: $('input#2').is(':checked'),
													Audio: $('input#3').is(':checked'),
													Video: $('input#4').is(':checked'),
													eBook: $('input#5').is(':checked'),
													image3d: $('input#6').is(':checked'),
													Raster: $('input#7').is(':checked'),
													Vector: $('input#8').is(':checked'),
													Camera: $('input#9').is(':checked'),
													Layout: $('input#10').is(':checked'),
													Spreadsheet: $('input#11').is(':checked'),
													Database: $('input#12').is(':checked'),
													Executable: $('input#13').is(':checked'),
													Game: $('input#14').is(':checked'),
													CAD: $('input#15').is(':checked'),
													GIS: $('input#16').is(':checked'),
													Web: $('input#17').is(':checked'),
													Plugin: $('input#18').is(':checked'),
													Font: $('input#19').is(':checked'),
													System: $('input#20').is(':checked'),
													Settings: $('input#21').is(':checked'),
													Encoded: $('input#22').is(':checked'),
													Compressed: $('input#23').is(':checked'),
													Disk: $('input#24').is(':checked'),
													Developer: $('input#25').is(':checked'),
													Backup: $('input#26').is(':checked'),
													Misc: $('input#27').is(':checked')
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){ 
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});									
								break;
						case 'max_rec_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													max_rec_ge: $('input#general_max_rec').val()
													
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});									
								break;
						case 'max_email_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													email_ge: $('input#general_email').val()
													
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});								
								break;
						case 'folder_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													files_folder: $('input#general_folder').val()
													
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});									
								break;
						case 'mes_rec_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													mes_rec_title: $('input#general_mes_rec_title').val(),
													mes_rec_body: $('textarea#general_mes_rec_body').val()
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});									
								break;
						case 'mes_cop_s':
									var inputs = { 
													what_save: what_save,
													user_token: $('input#get').val(), 
													mes_cop_title: $('input#general_mes_cop_title').val(),
													mes_cop_body: $('textarea#general_mes_cop_body').val()
												 };
									$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response=='OK!'||response=='die5') window.location.href="admin.php?pager=1";
												else {
													$(object_ge_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}});										
								break;
					}
			});
			
		//LOG SEARCH:
		$('div#search_log').click(function(){
				var object_log_error = $('div#log_page_error');
				$(object_log_error).slideUp('slow');
				$('div#log_page_res_conn').slideUp('fast',function(){
					$('div#log_page_res_conn').empty();
					var what_save = 'search_log';
					var inputs = { 
									what_save: what_save,
									user_token: $('input#get').val(), 
									s_date_log: $('input#search_start_date').val(),
									e_date_log: $('input#search_end_date').val()
													
								};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response == 'die5') location.reload();
												if (response.substring(0,3)=='OK!') $('div#log_page_res_conn').html(response.substring(3)).slideDown('fast');
												else {
													$(object_log_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 
				});	
		});
	
		$(document).on('click','.expand_log',function(){
			$(this).closest('tr').next('tr').find('div.row_log_details').eq(0).slideToggle();
		});

		//SEARCH USER ACTIVITY:
		$('div#search_user').click(function(){
				var object_log_error = $('div#search_page_error');
				$(object_log_error).slideUp('slow');
				$('div#search_page_res_conn').slideUp('fast',function(){
					$('div#search_page_res_conn').empty();
					var what_save = 'search_mail';
					var inputs = { 
									what_save: what_save,
									user_token: $('input#get').val(), 
									user_search: $('input#search_mail_input').val()
													
								};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response == 'die5') location.reload();
												if (response.substring(0,3)=='OK!') $('div#search_page_res_conn').html(response.substring(3)).slideDown('fast');
												else {
													$(object_log_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 
				});	
		});
	
		$(document).on('click','.expand_search',function(){
			$(this).closest('tr').next('tr').find('div.row_search_details').eq(0).slideToggle();
		});

		//Exclude a user:
		$('div#add_ex').click(function(){
				var object_log_error = $('div#ex_page_error');
				$(object_log_error).slideUp('slow');
					var what_save = 'ex_add';
					var inputs = { 
									what_save: what_save,
									user_token: $('input#get').val(), 
									ex_email: $('input#email_ex_input').val(),
									ex_note: $('input#com_ex_input').val()
													
								};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response == 'die5') location.reload();
												if (response.substring(0,3)=='OK!') window.location.href="admin.php?pager=4";
												else {
													$(object_log_error).slideDown('slow');
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 			
		});
		
		//remove Exclude:
		$('div.remove_ex_but').click(function(){
			var id_to_remove_ex = $(this).next('input#id_ex').val();
			var what_save = 'ex_rem';
			var inputs = { 
							what_save: what_save,
							user_token: $('input#get').val(), 
							id_to_remove_ex: id_to_remove_ex
						};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response == 'die5') location.reload();
												if (response.substring(0,3)=='OK!') window.location.href="admin.php?pager=4";
												else {
													location.reload();
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 
		});
		
		//remove Blocked:
		$('div.remove_block_but').click(function(){
			var id_to_remove_blocked = $(this).next('input#id_block').val();
			var what_save = 'block_rem';
			var inputs = { 
							what_save: what_save,
							user_token: $('input#get').val(), 
							id_to_remove_blocked: id_to_remove_blocked
						};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response == 'die5') location.reload();
												if (response.substring(0,3)=='OK!') window.location.href="admin.php?pager=5";
												else {
													location.reload();
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 

		});

		//remove From log search:
		$(document).on('click','div.remove_log_search_but',function(){
			var row_log_search1 = $(this).closest('tr');
			var row_log_search2 = $(row_log_search1).next('tr');
			var id_to_remove_log = $(this).next('input#row_log_id').val();
			var what_save = 'log_rem';
			var inputs = { 
							what_save: what_save,
							user_token: $('input#get').val(), 
							id_to_remove_log: id_to_remove_log
						};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response.substring(0,3)=='OK!') {
													$(row_log_search2).remove();
													$(row_log_search1).remove();												
												}
												else 
												{
													location.reload();
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 

		});	

		//remove From user search:
		$(document).on('click','div.remove_user_search_but',function(){
			var row_user_search1 = $(this).closest('tr');
			var row_user_search2 = $(row_user_search1).next('tr');
			var id_to_remove_search = $(this).next('input#row_search_id').val();
			var what_save = 'log_rem';
            
			var inputs = { 
							what_save: what_save,
							user_token: $('input#get').val(), 
							id_to_remove_log: id_to_remove_search
						};
 						$.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
											,success: function(response){
												if (response.substring(0,3)=='OK!') {
													$(row_user_search2).remove();
													$(row_user_search1).remove();
												}
												else 
												{
													location.reload();
												}
											}
											,error: function(thrownError){
												console.log(thrownError);
											}}); 

		});
        
         //error catcher hide:
        $(document).off('click','.closeMessageVal').on('click','.closeMessageVal',function() {
            $(this).parent('div').slideUp(function(){
                $(this).html("<div class='closeMessageVal'></div>");
            });
        });
        //expand users tools:
        $('img.expand_users_action_display').click(function(){
            $(this).parent('p').next('div').slideToggle('slow');
        });
        
         //expand users row for update:
        $(document).off('click','.update_row_user').on('click','.update_row_user',function(event){
            event.preventDefault();
            $(this).closest('tr').next('tr').find('.row_info_users').slideToggle('slow');
        });  
        
        //set users Mode:
         $('#but_submit_users_mode').click(function(){
            //get:
                var users_mode         = $('#users_mode_select').val();
				var what_save           = 'users_mode';
				var user_token          = $('input#get').val(); 
                var inputs = { 
                                    what_save        : what_save,
                                    user_token       : user_token, 
                                    users_mode       : users_mode
                                };
                                $.ajax({ type: "POST", url: "users.php", dataType: 'html', data: inputs
                                                    ,success: function(response){
                                                        if (response == 'OK!') {
                                                            alert('users mode updated successfully!');
                                                        } else {
                                                        
                                                            alert('Something went wrong please contact administrator!');
                                                            console.log(response);
                                                            
                                                        }
                                                    }
                                                    ,error: function(thrownError){
                                                        alert('Something went wrong please contact administrator!');
                                                        console.log(thrownError);
                                                    }});                
        });
        //add user:
        $('#but_submit_new_user').click(function(){ 
            //get:
                var new_userName        = $('#user_name_new').val();
                var new_userPassword    = $('#user_pass_new').val();
                var new_fullName        = $('#user_Fname_new').val();
                var new_userMail        = $('#user_Email_new').val();
                var new_maxFiles        = $('#user_MaxFnum_new').val();
                var new_maxSize         = $('#user_MaxFsize_new').val();
                var new_maxRec          = parseInt($('#user_MaxRec_new').val());
				var what_save           = 'add_user_new';
				var user_token          = $('input#get').val(); 
                
            //validate:
                var valCatch = {};
                //username:
                if (new_userName.length     < 5)                              { valCatch['username'] = 'Please select a valid user of minimum 5 chars.'; }
                if (new_userPassword.length < 5)                              { valCatch['userpass'] = 'Please select a valid password of minimum 5 chars.'; }
                if (typeof new_maxRec == 'number' && new_maxRec > 0) { } else { valCatch['maxrec'] = 'Please select a valid recipients limit of minimum 1.'; }
                if (!validateEmail(new_userMail))                             { valCatch['useremail'] = 'Please enter a valid email address for this account.'; }
                
                if (Object.keys(valCatch).length < 1) {
                    //save new user:
                    var inputs = { 
                                    what_save        : what_save,
                                    user_token       : user_token, 
                                    new_userName     : new_userName,
                                    new_userPassword : new_userPassword,
                                    new_fullName     : new_fullName,
                                    new_userMail     : new_userMail,
                                    new_maxSize     : new_maxSize,
                                    new_maxFiles     : new_maxFiles,
                                    new_maxRec       : new_maxRec

                                };
                                $.ajax({ type: "POST", url: "users.php", dataType: 'html', data: inputs
                                                    ,success: function(response){
                                                        if (response == 'OK!') {
                                                            alert('user: ' + new_userName + ' successfuly added!');
                                                            window.location.href="admin.php?pager=7";
                                                            return false;
                                                        } 
                                                        else if (response == 'Taken!') {
                                                            
                                                            strAppend2 = "&bull;&nbsp;&nbsp;&nbsp;Uesrname: <b>" + new_userName + "</b>&nbsp; is not available!<br />";
                                                            $('.error_catcher').slideUp(function(){
                                                                $('#valid_of_new_user').html("<div class='closeMessageVal'></div>" + strAppend2).delay(300).slideDown();
                                                            });                                                            
                                                        
                                                        } else {
                                                        
                                                            alert('Something went wrong please contact administrator!');
                                                            console.log(response);
                                                            
                                                        }
                                                    }
                                                    ,error: function(thrownError){
                                                        alert('Something went wrong please contact administrator!');
                                                        console.log(thrownError);
                                                    }});                     
                } else {
                    //output errors:
                    strAppend = '';
                    for (var key in valCatch) {
                        strAppend += "&bull;&nbsp;&nbsp;&nbsp;" + valCatch[key] + "<br />";
                    }
                    
                    $('#valid_of_guest_user').slideUp();
                    $('#valid_of_new_user').slideUp(function(){  console.log($('.error_catcher').length);
                       $('#valid_of_new_user').html("<div class='closeMessageVal'></div>" + strAppend);
                       $('#valid_of_new_user').slideDown();
                    });
                }
        });
        //delete user:
           $('.delete_row_user').click(function(){
           
                //get:
                    var rowMaster = $(this).closest('tr').eq(0);
                    var rowMaster_next = $(rowMaster).next('tr').eq(0);
                    var what_save    = 'delete_user';
                    var user_token   = $('input#get').val(); 
                    var user_name    = $(this).closest('tr').find('td').eq(0).text();
                    var rowId        = parseInt($(this).attr('rowId')); 
                    
            //validate:
                var valCatch2 = {};
                if (typeof rowId == 'number' && rowId > 0) { } else { valCatch2['idRow']       = 'SomeThing went wrong - contact administrator.';   }
                
                if (Object.keys(valCatch2).length < 1) {
                
                     var inputs = { 
                                    rowId               : rowId,
                                    what_save           : what_save,
                                    user_token          : user_token
                                };                   
                                $.ajax({ type: "POST", url: "users.php", dataType: 'html', data: inputs
                                                    ,success: function(response){
                                                        if (response == 'OK!') {
                                                            alert('user: ' + user_name + ' successfuly deleted!');
                                                            $(rowMaster).fadeOut(function(){ $(this).remove(); });
                                                            $(rowMaster_next).fadeOut(function(){ $(this).remove(); });
                                                            
                                                        } else {
                                                        
                                                            alert('Something went wrong please contact administrator!');
                                                            console.log(response);
                                                            
                                                        }
                                                    }
                                                    ,error: function(thrownError){
                                                        alert('Something went wrong please contact administrator!');
                                                        console.log(thrownError);
                                                    }});                       
                } else {
                
                    //output errors:
                    strAppend2 = '';
                    for (var key in valCatch2) {
                        strAppend2 += "- " + valCatch2[key] + "\n";
                    }
                    alert(strAppend2);                
                }
           });
       //update user:
        $('.but_submit_update_user').click(function(){
            //get:
                var parent                 = $(this).closest('table'); 
                var clickUpdate            = $(parent).closest('tr').prev('tr').find('.update_row_user').eq(0); 
                var update_userName        = $(parent).find('#user_name_update').val();
                var update_userPassword    = $(parent).find('#user_pass_update').val();
                var update_fullName        = $(parent).find('#user_Fname_update').val();
                var update_userMail        = $(parent).find('#user_Email_update').val();
                var update_maxFiles        = $(parent).find('#user_MaxFnum_update').val();
                var update_maxSize         = $(parent).find('#user_MaxFsize_update').val();
                var update_maxRec          = parseInt($(parent).find('#user_MaxRec_update').val());
                var update_active          = $(parent).find('#user_activate_status').val();
				var what_save              = 'update_user';
				var user_token             = $('input#get').val(); 
                var rowId                  = $(this).attr('rowId');
                
            //validate:
                var valCatch2 = {};
                //username:
                if (update_userName.length     < 5)                                 { valCatch2['username']     = 'Please select a valid user of minimum 5 chars.';         }
                if (update_userPassword != "" && update_userPassword.length < 5)    { valCatch2['userpass']     = 'Please select a valid password of minimum 5 chars.';     }
                if (typeof update_maxRec == 'number' && update_maxRec > 0) { } else { valCatch2['maxrec']       = 'Please select a valid recipients limit of minimum 1.';   }
                if (!validateEmail(update_userMail))                                { valCatch2['useremail']    = 'Please enter a valid email address for this account.';   }
                
                if (Object.keys(valCatch2).length < 1) { 
                    //save new user:
                    var inputs = { 
                                    rowId               : rowId,
                                    what_save           : what_save,
                                    user_token          : user_token, 
                                    update_userName     : update_userName,
                                    update_userPassword : update_userPassword,
                                    update_fullName     : update_fullName,
                                    update_userMail     : update_userMail,
                                    update_maxSize      : update_maxSize,
                                    update_maxFiles     : update_maxFiles,
                                    update_maxRec       : update_maxRec,
                                    update_active       : update_active

                                };
                                $.ajax({ type: "POST", url: "users.php", dataType: 'html', data: inputs
                                                    ,success: function(response){
                                                        if (response == 'OK!') {
                                                            alert('user: ' + update_userName + ' successfuly updated!');
                                                            $(clickUpdate).trigger( "click" );
                                                            
                                                            //update Row: display:
                                                            if(update_active == 'yes') $(clickUpdate).closest('tr').find('td').eq(0).html("<img src='../img/active.png' width='15px' height='15px'  style='position:relative; top:3px' alt='Activation mode' title='Activation mode' />&nbsp;&nbsp;" + update_userName);
                                                            else                       $(clickUpdate).closest('tr').find('td').eq(0).html("<img src='../img/inactive.png' width='15px' height='15px'  style='position:relative; top:3px' alt='Activation mode' title='Activation mode' />&nbsp;&nbsp;" + update_userName);
                                                            
                                                            $(clickUpdate).closest('tr').find('td').eq(1).text(update_fullName);
                                                            $(clickUpdate).closest('tr').find('td').eq(2).text(update_userMail);
                                                        
                                                        } 
                                                        else if (response == 'Taken!') {
                                                        
                                                            alert('New user name ( ' + update_userName + ' ) is taken - operation aborted!');                                                           
                                                        
                                                        } else {
                                                        
                                                            alert('Something went wrong please contact administrator!');
                                                            console.log(response);
                                                            
                                                        }
                                                    }
                                                    ,error: function(thrownError){
                                                        alert('Something went wrong please contact administrator!');
                                                        console.log(thrownError);
                                                    }});                     
                } else {
                    //output errors:
                    strAppend2 = '';
                    for (var key in valCatch2) {
                        strAppend2 += "- " + valCatch2[key] + "\n";
                    }
                    alert(strAppend2);
                }
        });
        
      //submit guests:
        $('#but_submit_guest').click(function(){
            //get:
                var parent                 = $(this).closest('table'); 
                var update_guest_maxFiles  = $(parent).find('#guest_MaxFnum_new').val();
                var update_guest_maxSize   = $(parent).find('#guest_MaxFsize_new').val();
                var update_guest_maxRec    = parseInt($(parent).find('#guest_MaxRec_new').val());
				var what_save              = 'update_guest';
				var user_token             = $('input#get').val(); 
                
            //validate:
                var valCatch3 = {};
                //username:
                if (typeof update_guest_maxRec == 'number' && update_guest_maxRec > 0) { } else { valCatch3['maxrec']       = 'Please select a valid recipients limit of minimum 1.';   }

                
                if (Object.keys(valCatch3).length < 1) { 
                    //save new user:
                    var inputs = { 
                                    what_save                 : what_save,
                                    user_token                : user_token, 
                                    update_guest_maxSize      : update_guest_maxSize,
                                    update_guest_maxFiles     : update_guest_maxFiles,
                                    update_guest_maxRec       : update_guest_maxRec

                                };
                                $.ajax({ type: "POST", url: "users.php", dataType: 'html', data: inputs
                                                    ,success: function(response){
                                                        if (response == 'OK!') {
                                                            alert('Guest account successfuly updated!');
                                                        
                                                        }  else {
                                                        
                                                            alert('Something went wrong please contact administrator!');
                                                            console.log(response);
                                                            
                                                        }
                                                    }
                                                    ,error: function(thrownError){
                                                        alert('Something went wrong please contact administrator!');
                                                        console.log(thrownError);
                                                    }});                     
                } else {
                    //output errors:
                    strAppend = '';
                    for (var key in valCatch3) {
                        strAppend += "&bull;&nbsp;&nbsp;&nbsp;" + valCatch3[key] + "<br />";
                    }
                    $('#valid_of_new_user').slideUp();
                    $('#valid_of_guest_user').slideUp(function(){
                        $('#valid_of_guest_user').html("<div class='closeMessageVal'></div>" + strAppend).delay(300).slideDown();
                    });
                }
        });
        
        
      //error catcher2 hide:
        $(document).off('click','.closeMessageVal2').on('click','.closeMessageVal2',function() { $(this).parent('div').slideUp(function(){ }); });        
      
      //Run CleanUp:
        $('#but_cleanUp').click(function(){
            
            //hide previous errors:
                $('#errorMessageOfCleanUp').slideUp('fast',function(){
                
                    //get:
                        var parent                 = $(this).closest('table'); 
                        var intervalweeks          = parseInt($(parent).find('#selectWeeksInterval').val());
                        var what_save              = "cleanup_rem";
                        var user_token             = $('input#get').val(); 
                        
                        console.log(intervalweeks,what_save);
                        
                    //validate:
                        var valCatch5 = {};
                        //username:
                        if (typeof intervalweeks == 'number' && intervalweeks > 0) { } else { valCatch5['weeksInterval'] = 'Please select a valid weeks interval.'; }

                        if (Object.keys(valCatch5).length < 1) { 
                            
                            //expose processing bar:
                            if ($('#cleanUpSetParams').is(":visible")) { $('#cleanUpSetParams').slideUp('slow'); }
                            if (!$('#runningCleanUp').is(":visible")) {$('#runningCleanUp').slideDown('slow'); }
                            
                            setTimeout(function(){
                            
                                var inputs = { 
                                                what_save     : what_save,
                                                user_token    : user_token, 
                                                intervalweeks : intervalweeks
                                            };                                
                                $.ajax({ type: "POST", url: "doit.php", dataType: 'html', data: inputs
                                
                                                    ,success: function(response){
                                                        if (response == 'OK!') {
                                                        
                                                            alert('CleanUp Done!');
                                                            window.location.href="admin.php?pager=6";
                                                            
                                                        }  else {
                                                        
                                                            if (!$('#cleanUpSetParams').is(":visible")) { $('#cleanUpSetParams').slideDown('slow'); }
                                                            $('#errorMessageOfCleanUp').slideDown('slow');
                                                            $('#runningCleanUp').slideUp('slow');
                                                            console.log(response);
                                                            
                                                        }
                                                    }
                                                    ,error: function(thrownError){
                                                    
                                                        if (!$('#cleanUpSetParams').is(":visible")) { $('#cleanUpSetParams').slideDown('slow'); }
                                                        $('#errorMessageOfCleanUp').slideDown('slow');
                                                        $('#runningCleanUp').slideUp('slow');
                                                        console.log(thrownError);
                                                    }}); 
                                                    
                            },1500);
                        } else {
                        
                            //output errors:
                            if ($('#runningCleanUp').is(":visible"))    { $('#runningCleanUp').slideUp('fast'); }
                            if (!$('#cleanUpSetParams').is(":visible")) { $('#cleanUpSetParams').slideDown('fast'); }
                            $('#errorMessageOfCleanUp').slideDown();
                        }
                });
            });
});

function isNumeric(num){
    return !isNaN(num)
}
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 