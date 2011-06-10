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

class HuruHelpdeskControllerSearch extends JController
{
	function display()
	{
		$mainframe = &JFactory::getApplication();
		
		//clean up the user state vars that may have been set during a previous search
		$mainframe->setUserState('hh_list.searchuser','');
		$mainframe->setUserState('hh_list.searchdays','');
		$mainframe->setUserState('hh_list.searchtask','');
		$mainframe->setUserState('hh_list.searchusername','');
		$mainframe->setUserState('hh_list.searchproblemid','');
		$mainframe->setUserState('hh_list.searchrep','');
		$mainframe->setUserState('hh_list.searchcategory','');
		$mainframe->setUserState('hh_list.searchdepartment','');
		$mainframe->setUserState('hh_list.searchstatus','');
		$mainframe->setUserState('hh_list.searchpriority','');
		$mainframe->setUserState('hh_list.searchkeyword','');
		$mainframe->setUserState('hh_list.searchsubject','');
		$mainframe->setUserState('hh_list.searchdescription','');
		$mainframe->setUserState('hh_list.searchsolution','');
		$mainframe->setUserState('hh_list.searchstartdatefrom','');
		$mainframe->setUserState('hh_list.searchstartdateto','');
		$mainframe->setUserState('hh_list.count','');

		$view = JRequest::getVar('view');
		$stype = $mainframe->getUserStateFromRequest('hh_list.stype','stype','');
		if(!$view)
		{
			JRequest::setVar('view', 'search');
		}
		if(!$stype)
		{
			JRequest::setVar('stype', 'kb');
		}
		parent::display();
	}	

	function find()
	{
		JRequest::setVar('stype',JRequest::getVar('stype','','','string',JREQUEST_ALLOWRAW));
		require_once(JPATH_COMPONENT.DS.'controllers'.DS.'list.php');
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

$controller = new HuruHelpdeskControllerSearch();
$controller->execute($task);
$controller->redirect();

