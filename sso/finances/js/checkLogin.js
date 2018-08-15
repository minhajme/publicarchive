function CheckLogin(){
	document.getElementById("FormMessages").style.opacity=0;
	var email= document.getElementById("email").value;
	var password=document.getElementById("password").value;
	if(email==''||password==''){
		document.getElementById("FormMessages").innerHTML="Please fill all fields.";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	document.getElementById("FormMessages").style.opacity=0;
	return true;
}
function fadeout(){
	document.getElementById("FormMessages").style.opacity=0;
}

