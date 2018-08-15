function day(){
	var d = new Date();
    var dd = d.getDate();
    var mm = d.getMonth() + 1; //Months are zero based
    var yyyy = d.getFullYear();
	if(dd<10)
		dd="0"+dd;
	if(mm<10)
		mm="0"+mm;
    var today= yyyy + "-" + mm + "-" + dd;
	document.cookie="day" + "=" +today;
}