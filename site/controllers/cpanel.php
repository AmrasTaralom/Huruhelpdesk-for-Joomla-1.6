<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

//check user auth level
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';

// Load framework base classes
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerCpanel extends JController 
{
	//display the Control Panel (main page) based upon the user type
	function display()
	{
		$mainframe = &JFactory::getApplication();
		
		//everytime someone goes to the control panel, call the function to purge old attachments
		//the function will check to see if the feature is enabled or not
		delete_old_attachments();
		
		switch(userlevel())
		{
			case USER_LEVEL_ADMIN:
				break;
			case USER_LEVEL_REP:
				break;
			case USER_LEVEL_USER:
				break;
			case USER_LEVEL_NONE:
				//check to make sure we are allowing anonymous access at all before proceeding
				if(!config('allowanonymous') && config('enablekb') != KB_LEVEL_ALL) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
				break;
			default:
				//something is wrong, so break
				//$mainframe->redirect('index.php', JText::_('An error has occurred. (101)'));
				break;
		}
		// Display the panel
		parent::display();
	}
	
	//delete case from database
	function deletecase()
	{
		$option = JRequest::getCmd('option');
		$mainframe = &JFactory::getApplication();
		
		//verify security level of user - must be admin to delete case
		if(userlevel() < USER_LEVEL_ADMIN) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
		
		//get case number to delete
		$id = safe(JRequest::getVar('id','','','int',JREQUEST_ALLOWRAW));

		//delete case attachments
		// -> attachments not yet implemented
		
		//delete case notes
		$query = 'DELETE FROM #__huruhelpdesk_notes WHERE id='.$id;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->query($query);
		if(!$result) $this->setRedirect('index.php?option=' . $option . '&view=cpanel&Itemid='.JRequest::getVar('Itemid','','','int',JREQUEST_ALLOWRAW), lang('ProblemNotDeleted'));
		
		//delete case
		$query = 'DELETE FROM #__huruhelpdesk_problems WHERE id='.$id;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->query($query);
		if(!$result) $this->setRedirect('index.php?option=' . $option . '&view=cpanel&Itemid='.JRequest::getVar('Itemid','','','int',JREQUEST_ALLOWRAW), lang('ProblemNotDeleted'));

		//return to cpanel screen
		$this->setRedirect('index.php?option=' . $option . '&view=cpanel&Itemid='.JRequest::getVar('Itemid','','','int',JREQUEST_ALLOWRAW), lang('ProblemDeleted'));
	}
}

//clean up the user state vars whenever we come to control panel screen
cleanuserstatevars();

$controller = new HuruHelpdeskControllerCPanel();
$controller->execute($task);
$controller->redirect();
