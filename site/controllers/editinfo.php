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
if(!checkusermin('user')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerEditInfo extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$row =& JTable::getInstance('EditInfo', 'Table');
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		$row->id = currentuserinfo('huru_id'); //makes sure we are editing the current user only - no hacks
		$row->phone = safe(JRequest::getVar('phone','','post','string',JREQUEST_ALLOWRAW));
		$row->phonehome = safe(JRequest::getVar('phonehome','','post','string',JREQUEST_ALLOWRAW));
		$row->phonemobile = safe(JRequest::getVar('phonemobile','','post','string',JREQUEST_ALLOWRAW));
		$row->pageraddress = safe(JRequest::getVar('pageraddress','','post','string',JREQUEST_ALLOWRAW));
		$row->location1 = safe(JRequest::getVar('location1','','post','string',JREQUEST_ALLOWRAW));
		$row->location2 = safe(JRequest::getVar('location2','','post','string',JREQUEST_ALLOWRAW));
		$row->department = safe(JRequest::getVar('department','','post','int',JREQUEST_ALLOWRAW));
		$row->language = safe(JRequest::getVar('language','','post','int',JREQUEST_ALLOWRAW));
		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=editinfo&Itemid='.JRequest::getVar('Itemid'), 'Profile Saved');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','editinfo');
		parent::display();
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','cpanel');

		//call up the cpanel screen controller
		require_once(JPATH_COMPONENT.DS.'controllers'.DS.'cpanel.php');
	}
}
	
$controller = new HuruHelpdeskControllerEditInfo();
$controller->execute($task);
$controller->redirect();
