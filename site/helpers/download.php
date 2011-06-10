<?php
/************************************************************************************************************
Because we are not loading this file via the site template or MVC architecture, 
we need to load the Joomla framework on our own so we can use the database functions below
*************************************************************************************************************/
define( '_JEXEC', 1 );

// real path depending on the type of server
if (stristr( $_SERVER['SERVER_SOFTWARE'], 'win32' )) 
{
	define( 'JPATH_BASE', realpath(dirname(__FILE__).'\..\..\..' ));
}
else define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../../..' ));

define( 'DS', DIRECTORY_SEPARATOR );

// loading framework of Joomla!
require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();
/************************************************************************************************************
End Joomla framework load
*************************************************************************************************************/

//load the huru header file
require_once JPATH_BASE.DS.'components'.DS.'com_huruhelpdesk'.DS.'helpers'.DS.'head.php';

//get the attachment id
$attachment_id = JRequest::getInt('id', '-1');
$note_id = JRequest::getInt('note_id', '-1');
$name = JRequest::getVar('name', '');

//make sure we got valid attachment info to work with before proceeding
if($attachment_id != -1 && $note_id != -1 && strlen($name)>0) 
{
	$db =& JFactory::getDBO();
	
	//verify that attachment with given id, note_id and name exists
	//the name and note_id checks are so that people can't throw random numbers at this script and
	//thereby gain access to an attachment by guessing the id
	$query = "SELECT COUNT(*) FROM #__huruhelpdesk_attachments WHERE id = ".$attachment_id." AND note_id=".$note_id." AND name='".$name."'";
	$db->setQuery($query);
	$count = $db->loadResult();

	if($count > 0)
	{
		//get the attachment
		$query = "SELECT name, type, size, content FROM #__huruhelpdesk_attachments WHERE id = ".$attachment_id." AND note_id=".$note_id." AND name='".$name."'";
		$db->setQuery($query);
		$file = $db->loadAssoc();
		
		//send the attachment to the browser
		header("Content-length: ".$file['size']);
		header("Content-type: ".$file['type']);
		header("Content-Disposition: attachment; filename=".$file['name']);
		echo $file['content'];
	}
	else echo lang('AttachmentFileNotFound');
}
?>