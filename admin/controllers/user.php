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

class HuruHelpdeskControllerUser extends JController
{
	function importusers()
	{
		$option = JRequest::getCmd('option');
		
		$db =& JFactory::getDBO();
		
		//First, remove all Huru users that are not in the Joomla users table.
		//Even though they are hidden from user management due the the query design, we want to delete them
		//from the Huru table to prevent someone from getting rights they ought not to have due to a duplicate
		//joomla id number being imported. 
		$query = "DELETE FROM #__huruhelpdesk_users WHERE joomla_id NOT IN (SELECT id FROM #__users)";
		$db->setQuery($query);
		$db->query();
		
		//count the number of Joomla users we will import
		$query = "SELECT id FROM #__users WHERE id NOT IN (SELECT joomla_id FROM #__huruhelpdesk_users)";
		$db->setQuery($query);
		$db->query();
		$num_new = $db->getNumRows();
		
		//if there aren't any users to import we'll skip the insert query
		if($num_new > 0)
		{
			//get default language
			$query = 'SELECT defaultlang FROM #__huruhelpdesk_config';
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$lang = $db->loadRow();
			
			//import all Joomla users that aren't already Huru users into the Huru user table
			$query = "INSERT INTO #__huruhelpdesk_users (joomla_id, language) SELECT id, ".$lang[0]." FROM #__users WHERE id NOT IN (SELECT joomla_id FROM #__huruhelpdesk_users)";
			$db->setQuery($query);

			if($db->query()) $this->setRedirect('index.php?option=' . $option . '&view=user&task=', $num_new . ' new Joomla! users imported.');
			else $this->setRedirect('index.php?option=' . $option . '&view=user&task=', 'Error syncronizing Joomla! users','error');
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=user&task=', 'No new Joomla! users to import','notice');
		}
	}

	function edit()
	{
		JToolBarHelper::save();
		JToolBarHelper::cancel();
		
		JRequest::setVar('view','useredit');
		$this->displaySingle('old');
	}
	
	function display() //display list of all users
	{
		JToolBarHelper::editList();
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'user');
		}
		parent::display();
	}	
	
	function displaySingle($type) //display a single User that can be edited
	{
		JRequest::setVar('view', 'useredit');
		if($type='new') JRequest::setVar('task','add');
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

$controller = new HuruHelpdeskControllerUser();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();

