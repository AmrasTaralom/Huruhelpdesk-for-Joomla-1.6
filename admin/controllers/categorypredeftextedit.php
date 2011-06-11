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

class HuruHelpdeskControllerCategorypredeftextEdit extends JController
{
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$row =& JTable::getInstance('Categorypredeftext', 'Table');
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		
		$row->predeftext = safe(JRequest::getVar('predeftext','','post','string',JREQUEST_ALLOWRAW));
		$row->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
		
		if(!$row->store())
		{
			JError::raiseError(500, $row->getError());
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=categorypredeftext&task=save', 'Categorytext Saved');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','categorypredeftextedit');
		parent::display();
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'categorypredeftext');

		//call up the list screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'categorypredeftext.php');
	}
}
	
$controller = new HuruHelpdeskControllerCategorypredeftextEdit();
$controller->execute($task);
$controller->redirect();
