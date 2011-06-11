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

class HuruHelpdeskControllerCategorypredeftext extends JController
{
	function add()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','categorypredeftextedit');
		$this->displaySingle('new');
	}
	
	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','categorypredeftextedit');
		$this->displaySingle('old');
	}
	
	function display() //display list of all categories
	{
		JToolBarHelper::editList();
		JToolBarHelper::deleteList();
		JToolBarHelper::addNew();
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'categorypredeftext');
		}
		parent::display();
	}	
	
	function displaySingle($type) //display a single category that can be edited
	{
		JRequest::setVar('view', 'categorypredeftextedit');
		if($type='new') JRequest::setVar('task','add');
		parent::display();
	}	
	
	function remove()
	{
		JRequest::checkToken() or jexit('Invalid Token');
		$option = JRequest::getCmd('option');

		$cid = JRequest::getVar('cid', array(0));
		$row =& JTable::getInstance('Categorypredeftext', 'Table');
		
		foreach ($cid as $category_id)
		{
			$category_id = (int) safe($category_id);
			if (!$row->delete($category_id))
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
		
		$this->setRedirect('index.php?option=' . $option . '&view=categorypredeftext', count($cid).' Categorytext' . $s . ' deleted.');
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

$controller = new HuruHelpdeskControllerCategorypredeftext();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

