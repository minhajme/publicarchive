function CheckReset(){
	document.getElementById("FormMessages").style.opacity=0;
	var email= document.getElementById("email").value;

	if(email==''){
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
	document.getElementById("FormMessages").style.opacity=0;
	return true;
	
}

function fadeout(){
	document.getElementById("FormMessages").style.opacity=0;
}

