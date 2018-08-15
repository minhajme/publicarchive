function print(){
	content1=document.getElementById("Content1").innerHTML
	content2=document.getElementById("data").innerHTML
	content3=document.getElementById("data2").innerHTML
	count=document.getElementById("count").innerHTML
	count2=document.getElementById("count2").innerHTML
	newwin=window.open('','printwin','left=-100,top=100,width=700,height=400')
	newwin.document.write('<html>\n<head>\n<title>Print</title><link href="css/style.css" rel="stylesheet" type="text/css">')
	newwin.document.write('<script>\nfunction checkstate(){\nif(document.readyState=="complete"){\nwindow.close()\n}else{setTimeout("checkstate()",2000)\n}}function printthis(){window.print();\ncheckstate();}<\/script>\n</head><body onload="printthis()">\n<img src="css/images/logo.jpg"/>')
	newwin.document.write('<div id="user-data-barP">')
	newwin.document.write(content1) 
	newwin.document.write('</div><span class="totalRecords top20">')
	newwin.document.write(count) 
	newwin.document.write('</span><h3 class="title">Incomes/Expenses:</h3><ul class="user-data-listP">') 
	newwin.document.write(content2) 
	newwin.document.write('</ul><br><br><span class="totalRecords top20"><br>')
	newwin.document.write(count2) 
	newwin.document.write('</span><h3 class="title">Future Incomes/Expenses:</h3><ul class="user-data-listP">')
	newwin.document.write(content3) 
	newwin.document.write('<ul></body>\n')
	newwin.document.write('</HTML>\n')
	newwin.document.close()
}