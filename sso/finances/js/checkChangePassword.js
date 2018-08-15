function CheckPass(){
	document.getElementById("FormMessages").style.opacity=0;
	var p1=document.getElementById("p1").value;
	var p2= document.getElementById("p2").value;

	if(p1==''||p2==''){
		document.getElementById("FormMessages").innerHTML="Please fill all fields";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	if(p1.length<5||p2.length<5){
		document.getElementById("FormMessages").innerHTML="Passwords must be at least 5 characters";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	
	if(p1!=p2){
		document.getElementById("FormMessages").innerHTML="Passwords do not match";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	document.getElementById("FormMessages").style.opacity=0;
	return true;
	
}

function fadeout(){
	document.getElementById("FormMessages").style.opacity=0;
}

