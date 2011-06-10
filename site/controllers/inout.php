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
if(!checkusermin('rep')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerInout extends JController
{
	function display() //display list of all priorities
	{
		$view = JRequest::getVar('view');
		$type = JRequest::getVar('type');
		if(!$view)
		{
			JRequest::setVar('view', 'inout');
		}
		if(!$type)
		{
			JRequest::setVar('type', 'rep');
		}
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

$controller = new HuruHelpdeskControllerInout();
$controller->execute($task);
$controller->redirect();

