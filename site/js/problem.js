function checkform(closedstatus)
{
	//if this is a new problem and if userselect is set, then we don't have to check the uid or email fields - otherwise we do need to validate them
	if(document.getElementById('userselect') !=null && document.getElementById('userselect').options[document.getElementById('userselect').selectedIndex].value < 0)
	{
		//check the uid field
		if(!verifystring(document.getElementById('uid').value,1,255)){alert('Please enter a username for this problem.');return false;}
		
		//check that the email field looks like an email address
		//note that we need to override the allowance of zero length addresses in the validation function
		var email=document.getElementById('uemail').value;
		if(email.length<=0 || !isEmail(document.getElementById('uemail').value)){alert('Please enter a valid email address in the E-Mail field.');return false;}
		
		//check that phone field is either blank or looks like a phone Number
		var phone=document.getElementById('uphone').value;
		if(phone.length>0 && !isPhone(phone)){alert('Please enter a valid phone number in the Phone field.');return false;}
	}
	
	//check that, if we are closing the case, that there is a solution
	if(document.getElementById('status') != null && document.getElementById('status').value == closedstatus && document.getElementById('solutiontext').value.length<=0){alert('You must enter a solution before closing a problem.');return false;}

	//now make sure there is a title and description
	if(document.getElementById('title') != null && document.getElementById('title').value.length<=0){alert('Please enter a title for this problem.');return false;}
	if(document.getElementById('descriptiontext') != null && document.getElementById('descriptiontext').value.length <=0){alert('Please enter a description of the problem.');return false;}

	return true;
}

function setform()
{
	document.getElementById('task').value = 'save';
}

function processform(closedstatus)
{
	//if we are dealing with a new case, we will have to set some fields first
	if(!checkform(closedstatus))
	{
		return false;
	}
	
	//now that we're past our checks, continue processing the form
	setform();
	document.getElementById('problem_form').submit();
	
	//return true;
}

function searchresults()
{
	document.getElementById('view').value='list';
	document.getElementById('task').value='results';
}

function deleteattachment(attachmentid)
{
	if(confirm('Are you sure?'))
	{
		//alert('ok');
		document.getElementById('task').value = 'deleteattachment';
		document.getElementById('attachment_id').value = attachmentid;
		document.getElementById('problem_form').submit();
	}
	else return false;
}
