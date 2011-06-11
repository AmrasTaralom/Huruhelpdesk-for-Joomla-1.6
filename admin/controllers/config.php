<?php
/**
 * @package HuruHelpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

//check user auth level
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'auth.php'; //sends us to the core helper files
$mainframe = &JFactory::getApplication();
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerConfig extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$row =& JTable::getInstance('Config', 'Table');
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		$row->hdnotifyname = safe(JRequest::getVar('hdnotifyname','','post','string',JREQUEST_ALLOWRAW));
		$row->hdreply = safe(JRequest::getVar('hdreply','','post','string',JREQUEST_ALLOWRAW));
		$row->hdurl = safe(JRequest::getVar('hdurl','','post','string',JREQUEST_ALLOWRAW));
		$row->notifyuser = safe(JRequest::getVar('notifyuser','','post','int',JREQUEST_ALLOWRAW));
		$row->enablekb = safe(JRequest::getVar('enablekb','','post','int',JREQUEST_ALLOWRAW));
		$row->defaultpriority = safe(JRequest::getVar('defaultpriority','','post','int',JREQUEST_ALLOWRAW));
		$row->pagerpriority = safe(JRequest::getVar('pagerpriority','','post','int',JREQUEST_ALLOWRAW));
		$row->defaultstatus = safe(JRequest::getVar('defaultstatus','','post','int',JREQUEST_ALLOWRAW));
		$row->closestatus = safe(JRequest::getVar('closestatus','','post','int',JREQUEST_ALLOWRAW));
		$row->defaultdepartment = safe(JRequest::getVar('defaultdepartment','','post','int',JREQUEST_ALLOWRAW));
		$row->defaultcategory = safe(JRequest::getVar('defaultcategory','','post','int',JREQUEST_ALLOWRAW));
		$row->allowanonymous = safe(JRequest::getVar('allowanonymous','','post','int',JREQUEST_ALLOWRAW));
		$row->userselect = safe(JRequest::getVar('userselect','','post','int',JREQUEST_ALLOWRAW));
		$row->defaultlang = safe(JRequest::getVar('defaultlang','','post','int',JREQUEST_ALLOWRAW));
		$row->fileattach_allow = safe(JRequest::getVar('fileattach_allow','','post','int',JREQUEST_ALLOWRAW));
		$row->fileattach_allowedextensions = safe(JRequest::getVar('fileattach_allowedextensions','','post','string',JREQUEST_ALLOWRAW));
		$row->fileattach_maxsize = safe(JRequest::getVar('fileattach_maxsize','','post','int',JREQUEST_ALLOWRAW));
		$row->fileattach_download = safe(JRequest::getVar('fileattach_download','','post','int',JREQUEST_ALLOWRAW));
		$row->fileattach_maxage = safe(JRequest::getVar('fileattach_maxage','','post','int',JREQUEST_ALLOWRAW));
		$row->notifyadminonnewcases = safe(JRequest::getVar('notifyadminonnewcases','','post','string',JREQUEST_ALLOWRAW));

//		$row->show_username = safe(JRequest::getVar('show_username','','post','int',JREQUEST_ALLOWRAW));
//		$row->show_email = safe(JRequest::getVar('show_email','','post','int',JREQUEST_ALLOWRAW));
		$row->show_department = safe(JRequest::getVar('show_department','','post','int',JREQUEST_ALLOWRAW));
		$row->show_location = safe(JRequest::getVar('show_location','','post','int',JREQUEST_ALLOWRAW));
		$row->show_phone = safe(JRequest::getVar('show_phone','','post','int',JREQUEST_ALLOWRAW));
		$row->show_category = safe(JRequest::getVar('show_category','','post','int',JREQUEST_ALLOWRAW));
		$row->show_status = safe(JRequest::getVar('show_status','','post','int',JREQUEST_ALLOWRAW));
		$row->show_priority = safe(JRequest::getVar('show_priority','','post','int',JREQUEST_ALLOWRAW));
		$row->show_rep = safe(JRequest::getVar('show_rep','','post','int',JREQUEST_ALLOWRAW));
		$row->show_timespent = safe(JRequest::getVar('show_timespent','','post','int',JREQUEST_ALLOWRAW));

//		$row->set_username = safe(JRequest::getVar('set_username','','post','int',JREQUEST_ALLOWRAW));
//		$row->set_email = safe(JRequest::getVar('set_email','','post','int',JREQUEST_ALLOWRAW));
		$row->set_department = safe(JRequest::getVar('set_department','','post','int',JREQUEST_ALLOWRAW));
		$row->set_location = safe(JRequest::getVar('set_location','','post','int',JREQUEST_ALLOWRAW));
		$row->set_phone = safe(JRequest::getVar('set_phone','','post','int',JREQUEST_ALLOWRAW));
		$row->set_category = safe(JRequest::getVar('set_category','','post','int',JREQUEST_ALLOWRAW));
		$row->set_status = safe(JRequest::getVar('set_status','','post','int',JREQUEST_ALLOWRAW));
		$row->set_priority = safe(JRequest::getVar('set_priority','','post','int',JREQUEST_ALLOWRAW));
		$row->set_rep = safe(JRequest::getVar('set_rep','','post','int',JREQUEST_ALLOWRAW));
		$row->set_timespent = safe(JRequest::getVar('set_timespent','','post','int',JREQUEST_ALLOWRAW));

		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=cpanel&task=', 'Config Saved');
	}
	
	function display() //display the config for editing
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'config');
		}
		parent::display();
	}	

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','cpanel');

		//call up the cpanel screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'cpanel.php');
	}
}
	
$controller = new HuruHelpdeskControllerConfig();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();
