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
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerUserEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$row =& JTable::getInstance('User', 'Table');
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		$row->isuser = safe(JRequest::getVar('isuser','','post','int',JREQUEST_ALLOWRAW));
		$row->isrep = safe(JRequest::getVar('isrep','','post','int',JREQUEST_ALLOWRAW));
		$row->isadmin = safe(JRequest::getVar('isadmin','','post','int',JREQUEST_ALLOWRAW));
		$row->phone = safe(JRequest::getVar('phone','','post','string',JREQUEST_ALLOWRAW));
		$row->phonemobile = safe(JRequest::getVar('phonemobile','','post','string',JREQUEST_ALLOWRAW));
		$row->pageraddress = safe(JRequest::getVar('pageraddress','','post','string',JREQUEST_ALLOWRAW));
		$row->phonehome = safe(JRequest::getVar('phonehome','','post','string',JREQUEST_ALLOWRAW));
		$row->location1 = safe(JRequest::getVar('location1','','post','string',JREQUEST_ALLOWRAW));
		$row->location2 = safe(JRequest::getVar('location2','','post','string',JREQUEST_ALLOWRAW));
		$row->department = safe(JRequest::getVar('department','','post','int',JREQUEST_ALLOWRAW));
		$row->language = safe(JRequest::getVar('language','','post','int',JREQUEST_ALLOWRAW));
		$row->viewreports = safe(JRequest::getVar('viewreports','','post','int',JREQUEST_ALLOWRAW));
		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=user&task=save', 'User Saved');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','useredit');
		parent::display();
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'user');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'user.php');
	}
}
	
$controller = new HuruHelpdeskControllerUserEdit();
$controller->execute($task);
$controller->redirect();
