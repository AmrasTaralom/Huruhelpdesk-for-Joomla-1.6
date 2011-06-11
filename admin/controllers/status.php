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

class HuruHelpdeskControllerStatus extends JController
{
	function add()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','statusedit');
		$this->displaySingle('new');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','statusedit');
		$this->displaySingle('old');
	}
	
	function display() //display list of all statuses
	{
		JToolBarHelper::editList();
		JToolBarHelper::deleteList();
		JToolBarHelper::addNew();
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'status');
		}
		parent::display();
	}	
	
	function displaySingle($type) //display a single status that can be edited
	{
		JRequest::setVar('view', 'statusedit');
		if($type='new') JRequest::setVar('task','add');
		parent::display();
	}	
	
	function remove()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$cid = JRequest::getVar('cid', array(0));
		$row =& JTable::getInstance('Status', 'Table');
		
		foreach ($cid as $id)
		{
			$id = (int) safe($id);

			//get the closed status for comparison
			$query = "SELECT closestatus FROM #__huruhelpdesk_config";
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$config = $db->loadRow();
			
			if ($id != $config[0]) //we cannot remove the closed status for operational reasons
			{
				if (!$row->delete($id))
				{
					JError::raiseError(500, $row->getError());
				}
			}
		}
		
		$s='';
		
		if(count($cid)>1)
		{
			$s = 'es';
		}
		else
		{
			$s = '';
		}
		
		$this->setRedirect('index.php?option=' . $option . '&view=status', 'Status' . $s . ' deleted.');
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

$controller = new HuruHelpdeskControllerStatus();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

