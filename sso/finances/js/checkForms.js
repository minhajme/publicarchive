
function Check(){
	document.getElementById("FormMessages").style.opacity=0;
	var name= document.getElementById("name").value;
	var description=document.getElementById("des").value;
	var price=document.getElementById("price").value;
	var date=document.getElementById("date").value;
			
	if(name==''||price==''|| date==''){
		document.getElementById("FormMessages").innerHTML="Please fill all fields.";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	
	
	if(!isNumber(price)){
		document.getElementById("FormMessages").innerHTML="Please Enter A Number For Amount.";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	
	if(Number(price)<0.1){
		document.getElementById("FormMessages").innerHTML="Amount can't be 0";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
		
	if(!document.getElementById('p').checked && !document.getElementById('p2').checked){
		document.getElementById("FormMessages").innerHTML="Please select one option.";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	

	if(!testDate(date) && !safari()){
		document.getElementById("FormMessages").innerHTML="Please select a correct date.";	
		document.getElementById("FormMessages").style.opacity=1;
		return false;
	}
	
	checkFuture();
	document.getElementById("FormMessages").style.opacity=0;
	return true;
	
}

function testDate(str){
    var t = str.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
    if(t===null) return false;
    var d=parseInt(t[1]), m=parseInt(t[2],10), y=parseInt(t[3],10);
    //below should be more acurate algorithm
    if(d>=1 && d<=12 && m>=1 && m<=31){
            return true;   
    }
    return false;
}

function fadeout(){
	document.getElementById("FormMessages").style.opacity=0;
}

function isNumber (o) {
  return ! isNaN (o-0) && o !== null && o.replace(/^\s\s*/, '') !== "" && o !== false;
}

function safari(){
var ua = navigator.userAgent.toLowerCase(); 
 if (ua.indexOf('safari')!=-1){ 
   if(ua.indexOf('chrome')  > -1){
    return false;
   }else{
   	return true;
   }
  }	
	return false;
}

function checkFuture(){
	var x=new Date();
	var date=document.getElementById("date").value;
	var dSplit = date.split("/");
	x.setFullYear(dSplit[2],(dSplit[0]-1),dSplit[1]);
	var today = new Date();
	if (x>today)
		document.getElementById('f').value="1";
	else
		document.getElementById('f').value="0";
}