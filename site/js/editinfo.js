function validateform()
{
	if(document.getElementById('phoneid').value.length > 0 && !isPhone(document.getElementById('phoneid').value)) {alert('Please enter a valid phone number in the Phone field.');return false;}
	if(document.getElementById('phonehomeid').value.length > 0 && !isPhone(document.getElementById('phonehomeid').value)) {alert('Please enter a valid phone number in the Home Phone field.');return false;}
	if(document.getElementById('phonemobileid').value.length > 0 && !isPhone(document.getElementById('phonemobileid').value)) {alert('Please enter a valid phone number in the Mobile Phone field.');return false;}
	if(document.getElementById('pageraddressid').value.length > 0 && !isEmail(document.getElementById('pageraddressid').value)) {alert('Please enter a valid email address in the Pager Address field.');return false;}
	return true;
}