function validateform()
{
	if(!isDateYMD(document.getElementById('startdate').value)){alert('Please enter a valid start date for the report in the format YYYY-MM-DD.');return false;}
	if(!isDateYMD(document.getElementById('enddate').value)){alert('Please enter a valid end date for the report in the format YYYY-MM-DD.');return false;}
	
	return true;
}