function validateform()
{
	if(document.getElementById('searchproblemid') && !isNum(document.getElementById('searchproblemid').value) && document.getElementById('searchproblemid').value.length >0){alert('Please enter a valid Problem # to search for.');return false;}
	if(document.getElementById('searchstartdatefrom') && !isDateYMD(document.getElementById('searchstartdatefrom').value) && document.getElementById('searchstartdatefrom').value.length >0){alert('Please enter a valid date range to search.');return false;}
	if(document.getElementById('searchstartdateto') && !isDateYMD(document.getElementById('searchstartdateto').value) && document.getElementById('searchstartdateto').value.length >0){alert('Please enter a valid date range to search.');return false;}
	
	return true;
}