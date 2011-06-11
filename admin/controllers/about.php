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

// Load framework base classes
jimport('joomla.application.component.controller');

/**
 * The Control Panel controller class
 *
 */
class HuruHelpdeskControllerAbout extends JController 
{
	/**
	 * Displays the About page
	 */
	function display()
	{
		JToolBarHelper::cancel();

		$view = JRequest::getVar('view');
		if(!$view)
		{
			JRequest::setVar('view', 'about');
		}
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

$controller = new HuruHelpdeskControllerAbout();
$controller->execute($task);
$controller->redirect();
