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
if(!checkuser('reports')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerReport extends JController
{
	function display() 
	{
		$mainframe = &JFactory::getApplication();
		
		$view = JRequest::getVar('view');
		$rtype = JRequest::getVar('rtype');
		
		if(!$view)
		{
			JRequest::setVar('view', 'viewreport');
		}
		if(!$rtype)
		{
			JRequest::setVar('rtype', 'department');
		}
		parent::display();
	}	

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('view','reports');

		//call up the cpanel screen controller
		require_once(JPATH_COMPONENT.DS.'controllers'.DS.'cpanel.php');
	}
}

$controller = new HuruHelpdeskControllerReport();
$controller->execute($task);
$controller->redirect();

