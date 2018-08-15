function CheckRegister(){
	document.getElementById("FormMessages").style.opacity=0;
	var username= document.getElementById("name").value;
	var password=document.getElementById("password").value;
	var email= document.getElementById("email").value;
	var currency=document.getElementById("currency").value;
	var math=document.getElementById("math").value;
	if(username==''||password==''||email==''||currency==''||math==''){
		document.getElementById("FormMessages").innerHTML="Please fill all fields";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	var filter=/^(.+)@(.+).(.+)$/i;
	if (!( filter.test( email )))
	{
		document.getElementById("FormMessages").innerHTML="Please enter a valid Email";
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	if(password.length<5){
		document.getElementById("FormMessages").innerHTML="Password must be at least 5 characters";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	document.getElementById("FormMessages").style.opacity=0;
	return true;
	
}

function fadeout(){
	document.getElementById("FormMessages").style.opacity=0;
}

