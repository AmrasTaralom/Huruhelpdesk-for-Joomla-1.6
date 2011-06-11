<?php
/**
 * @package HuruHelpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

//check user auth level
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'auth.php'; //sends us to the core helper files
$mainframe = &JFactory::getApplication();
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

// Load framework base classes
jimport('joomla.application.component.controller');

/**
 * The Control Panel controller class
 *
 */
class HuruHelpdeskControllerCpanel extends JController 
{
	/**
	 * Displays the Control Panel (main page)
	 * Accessible at index.php?option=com_huruhelpdesk
	 */
	function display()
	{
		// Display the panel
		parent::display();
	}
}

$controller = new HuruHelpdeskControllerCPanel();
if(!isset($task)) $task = "display"; //cancel button doesn't pass task so may gen php warning on execute below
$controller->execute($task);
$controller->redirect();
