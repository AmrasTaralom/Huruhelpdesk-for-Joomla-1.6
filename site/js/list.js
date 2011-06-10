function setorder(order)
{	
	document.getElementById('order').value = order;
	document.listForm.submit();
	return true;
}

function detail(id)
{
	document.getElementById('viewid').value = 'detail';
	document.getElementById('taskid').value = 'edit';
	document.getElementById('cidid').value = id;

	document.listForm.submit();
	return true;
}

