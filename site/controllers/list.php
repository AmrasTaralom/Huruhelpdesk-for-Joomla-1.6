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

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerList extends JController
{
	function display() 
	{
		$mainframe = &JFactory::getApplication();
		
		$view = $mainframe->getUserStateFromRequest('hh_list.view','view','');
		$type = $mainframe->getUserStateFromRequest('hh_list.type','type','');
		
		if(!$view)
		{
			JRequest::setVar('view', 'list');
		}
		if(!$type)
		{
			JRequest::setVar('type', 'assigned');
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

$controller = new HuruHelpdeskControllerList();
$controller->execute($task);
$controller->redirect();

