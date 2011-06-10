function displayMessage(str)
{
//	document.getElementById('toolbarmessage').innerHTML = str;

	var newspan = document.createElement("span");
	newspan.innerHTML = str;
	var container = document.getElementById("toolbarmessage");
	container.appendChild(newspan);	
}

function confirmation(button)
{
	var test;
	switch (button) 
	{
		case 1: 
			test=confirm("Are you sure you want to delete problem #" + document.getElementById('problemdeleteidtext').value + "?\n\nThis cannot be undone!\n\nClick OK to continue, or Cancel to go back.");
			break;
		default:
			test=confirm("Are you sure?\nClick OK to continue, or Cancel to go back.");
			break;
	}
	if (test)
		return true;
	else
		return false;
}
