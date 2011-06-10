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
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.model');

class HuruHelpdeskModelActivity extends JModel
{
	var $data = null;
	
	function getData()
	{
		$mainframe = &JFactory::getApplication();
		
		//build query clause
		$select = "SELECT p.title AS title, p.start_date AS start_date, p.close_date AS close_date, s.sname AS sname, maxresults.maxdate AS maxdate FROM #__huruhelpdesk_problems AS p LEFT OUTER JOIN #__huruhelpdesk_status AS s ON p.status = s.id LEFT OUTER JOIN (SELECT max(addDate) as maxdate, id FROM #__huruhelpdesk_notes GROUP BY id) maxresults ON p.id = maxresults.id";

		//build where clause
		$days = safe(JRequest::getVar('days','','','int'));
		if($days) 
		{
			$where = ' WHERE ';
			$where = $where.' maxresults.maxdate >= DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY)';
			$where = $where.' OR (maxresults.maxdate IS NULL AND p.start_date >= DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY))';
		}
		//##my2010040321 { Fixed warning
		else {
			$where = null;
		}
		//##my2010040321 }
	
		$order = ' ORDER BY s.status_id DESC, maxresults.maxdate ASC';

		$query = $select.$where.$order;
		
		if(DEBUG) dumpdebug($query);

		$this->data = $this->_getList($query);

		return $this->data;
	}	
}
