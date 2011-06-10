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

class HuruHelpdeskControllerStrings extends JController
{
	function add()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','stringsedit');
		$this->displaySingle('new');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','stringsedit');
		$this->displaySingle('old');
	}
	
	function display() //display list of all strings
	{
		JToolBarHelper::editList();
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'strings');
		}
		parent::display();
	}	
	
	function displaySingle($type) //display a single strings that can be edited
	{
		JRequest::setVar('view', 'stringsedit');
		if($type='new') JRequest::setVar('task','add');
		parent::display();
	}	
	
	function remove()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$cid = JRequest::getVar('cid', array(0));
		$row =& JTable::getInstance('Strings', 'Table');
		
		foreach ($cid as $id)
		{
			$id = (int) safe($id);
			if (!$row->delete($id))
			{
				JError::raiseError(500, $row->getError());
			}
		}
		
		$s='';
		
		if(count($cid)>1)
		{
			$s = 's';
		}
		else
		{
			$s = '';
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=strings', 'String' . $s . ' deleted.');
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view','language');

		//call up the cpanel screen controller
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'language.php');
	}
}

$controller = new HuruHelpdeskControllerStrings();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

