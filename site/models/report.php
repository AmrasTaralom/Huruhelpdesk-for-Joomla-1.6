<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
//check user auth level
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
if(!checkuser('reports')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.model');

class HuruHelpdeskModelReport extends JModel
{
	var $data = null;
	
	function getData()
	{
		$mainframe = &JFactory::getApplication();
		$rtype = JRequest::getVar('rtype');
		
		//stub for where clause
		$where = ' WHERE TRUE';
		
		//setup for date restrictions
		$startdate = safe(trim(JRequest::getVar('startdate','','','string',JREQUEST_ALLOWRAW)));
		$enddate = safe(trim(JRequest::getVar('enddate','','','string',JREQUEST_ALLOWRAW)));
		if(strlen($startdate)>0 && strlen($enddate)>0) $where = $where.' AND DATE_FORMAT(p.start_date,\'%Y-%m-%d\') >= \''.$startdate.'\' AND DATE_FORMAT(p.start_date,\'%Y-%m-%d\') <= \''.$enddate.'\'';
		
		if(DEBUG) dumpdebug($rtype);
		
		//build query clause based on type
		switch ($rtype)
		{
			case 'department':
				$select = "SELECT d.dname AS name, Count(*) AS total, sum(p.time_spent) AS total_time  FROM #__huruhelpdesk_problems AS p INNER JOIN #__huruhelpdesk_departments AS d ON p.department = d.department_id";
				$group = " GROUP BY dname ORDER BY dname ASC";
				break;
			case 'category':
				$select = "SELECT c.cname AS name, Count(*) AS total, sum(p.time_spent) AS total_time FROM #__huruhelpdesk_problems AS p INNER JOIN #__huruhelpdesk_categories AS c ON p.category = c.category_id ";
				$group = " GROUP BY cname ORDER BY cname ASC";
				break;
			case 'rep':
				$select = "SELECT ju.name AS name, Count(*) AS total, sum(p.time_spent) AS total_time FROM  #__huruhelpdesk_problems AS p LEFT OUTER JOIN  #__huruhelpdesk_users AS hh ON p.rep = hh.id LEFT OUTER JOIN  #__users AS ju ON ju.id = hh.joomla_id";
				$group = " GROUP BY ju.name ORDER BY ju.name ASC";
				break;
		}
		
		$query = $select.$where.$group;
		if(DEBUG) dumpdebug($query);

		$this->data = $this->_getList($query);

		return $this->data;
	}	
}
