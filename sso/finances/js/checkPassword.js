function CheckPass(){
	document.getElementById("FormMessages").style.opacity=0;
	var old= document.getElementById("old").value;
	var n1=document.getElementById("new1").value;
	var n2= document.getElementById("new2").value;

	if(old==''||n1==''||n2==''){
		document.getElementById("FormMessages").innerHTML="Please fill all fields";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	if(n1.length<5||n2.length<5){
		document.getElementById("FormMessages").innerHTML="Passwords must be at least 5 characters";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	
	if(n1!=n2){
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

