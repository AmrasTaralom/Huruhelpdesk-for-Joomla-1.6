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

class HuruHelpdeskControllerEmailEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$row =& JTable::getInstance('Email', 'Table');
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		$row->type = safe(JRequest::getVar('type','','post','string',JREQUEST_ALLOWRAW));
		$row->subject = safe(JRequest::getVar('subject','','post','string',JREQUEST_ALLOWRAW));
		$row->body = JRequest::getVar('body','','post','string',JREQUEST_ALLOWRAW);
		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=email&task=save', 'Email Saved');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','emailedit');
		parent::display();
	}

	function cancel()
	{
		$option = JRequest::getCmd('option');
		
		//call up the list screen controller
		$this->setRedirect('index.php?option=' . $option . '&view=email&task=&cid[]=');
	}
}
	
$controller = new HuruHelpdeskControllerEmailEdit();
$controller->execute($task);
$controller->redirect();
