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

class HuruHelpdeskControllerStringsEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$row =& JTable::getInstance('Strings', 'Table');
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		$row->lang_id = safe(JRequest::getVar('lang_id','','post','string',JREQUEST_ALLOWRAW));
		$row->variable = safe(JRequest::getVar('variable','','post','string',JREQUEST_ALLOWRAW));
		$row->langtext = safe(JRequest::getVar('langtext','','post','string',JREQUEST_ALLOWRAW));
		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=strings&task=save&cid[]='.JRequest::getVar('lang_id'), 'String Saved');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','stringsedit');
		parent::display();
	}

	function cancel()
	{
		$option = JRequest::getCmd('option');
		
		$lang_id = JRequest::getVar('lang_id');

		//call up the list screen controller
		$this->setRedirect('index.php?option=' . $option . '&view=strings&task=&cid[]=' . $lang_id);
	}
}
	
$controller = new HuruHelpdeskControllerStringsEdit();
$controller->execute($task);
$controller->redirect();
