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

jimport('joomla.application.component.model');

class HuruHelpdeskModelList extends JModel
{
	var $_total = null;
	var $_pagination = null;
	var $data = null;
	
	function __construct()
	{
		parent::__construct();
 
        $option = JRequest::getCmd('option');
		$mainframe = &JFactory::getApplication();

        // Get pagination request variables
        $limit = safe($mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int'));
        $limitstart = safe(JRequest::getVar('limitstart', 0, '', 'int'));
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
	}

	function _buildQuery()
	{
			$mainframe = &JFactory::getApplication();
			$type = safe($mainframe->getUserStateFromRequest('hh_list.type','type',''));
			
			//build WHERE clause based on type
			//for each type, check that user has authority for type
			switch ($type)
			{
				case 'submitted':
					if(!checkusermin('user')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
					
					//get username of current user
					$user =& JFactory::getUser();
					$uname = $user->username;
					
					//show all problems submitted by user - even ones that are closed
					$where = " WHERE p.uid='".$uname."'";
					break;
				case 'assigned':
					if(!checkusermin('rep')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

					//get closed status since we ignore closed problems for this view
					$query = 'SELECT s.id AS sid FROM #__huruhelpdesk_config AS c JOIN #__huruhelpdesk_status AS s ON s.id=c.closestatus';
					$db =& JFactory::getDBO();
					$db->setQuery($query);
					$closed = $db->loadRow();

					//get user id
					$user =& JFactory::getUser();
					$uid = $user->id;
					$query = 'SELECT id FROM #__huruhelpdesk_users WHERE joomla_id='.$uid;
					$db =& JFactory::getDBO();
					$db->setQuery($query);
					$hid = $db->loadRow();
					
					//show open problems assigned to user
					$where = ' WHERE p.status <> '.$closed[0].' AND p.rep='.$hid[0];
					break;
				case 'all':
					if(!checkusermin('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
					
					//check for filters
					$hid = safe($mainframe->getUserStateFromRequest('hh_list.user','user','','int'));
					$days = safe($mainframe->getUserStateFromRequest('hh_list.days','days','','int'));

					//when we do a days view, we want all problems, not just open ones
					if(!$days)
					{
						//get closed status since we ignore closed problems for this view when we don't have a days limit
						$query = 'SELECT s.id AS sid FROM #__huruhelpdesk_config AS c JOIN #__huruhelpdesk_status AS s ON s.id=c.closestatus';
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						$closed = $db->loadRow();

						$where = ' WHERE p.status <> '.$closed[0];
					}
					else $where = ' WHERE TRUE '; //if we have a days limit, just set a stub for the where clause
					
					//check for user var and get uid of user
					if($hid) $where = $where.' AND p.rep='.$hid;
					
					//check for days var and setup dates
					//days will be -1 if we want all cases (even closed) with no day limit
					if($days > 0) $where = $where.' AND p.start_date >= DATE_SUB(CURDATE(), INTERVAL '.$days.' DAY)';
					
					if(DEBUG) dumpdebug($where);
					
					break;
				case 'search':
					//find those search vars that are set & setup WHERE clause based on them

					$where = ' WHERE TRUE '; //stub for where clause

					$stype = safe($mainframe->getUserStateFromRequest('hh_list.stype','stype',''));
					
					//these aren't available for a kb search
					if($stype == "all")
					{
						$username = safe(strtolower(trim($mainframe->getUserStateFromRequest('hh_list.searchusername','searchusername','')))); //make search string lowercase in case the dbms is case-sensitive
						if(strlen($username)>0) $where = $where.' AND p.uid=\''.$username.'\'';
						
						$pid = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchproblemid','searchproblemid','','')));
						if(strlen($pid)>0 && $pid >= 0) $where = $where.' AND p.id='.$pid;
						
						$rep = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchrep','searchrep','','int')));
						if(strlen($rep)>0 && $rep >= 0) $where = $where.' AND p.rep='.$rep;
						
						$cat = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchcategory','searchcategory','','int')));
						if(strlen($cat)>0 && $cat >= 0) $where = $where.' AND p.category='.$cat;
						
						$dept = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchdepartment','searchdepartment','','int')));
						if(strlen($dept)>0 && $dept >= 0) $where = $where.' AND p.department='.$dept;
						
						$stat = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchstatus','searchstatus','','int')));
						if(strlen($stat)>0 && $stat >= 0) $where = $where.' AND p.status='.$stat;
						
						$prior = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchpriority','searchpriority','','int')));
						if(strlen($prior)>0 && $prior >= 0) $where = $where.' AND p.priority='.$prior;

						//startdate
						$datefrom = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchstartdatefrom','searchstartdatefrom','')));
						$dateto = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchstartdateto','searchstartdateto','')));
						if(strlen($datefrom)>0 && strlen($dateto)>0) $where = $where.' AND DATE_FORMAT(p.start_date,\'%Y-%m-%d\') >= \''.$datefrom.'\' AND DATE_FORMAT(p.start_date,\'%Y-%m-%d\') <= \''.$dateto.'\'';
					}
					
					//keywords in fields - these are OR'd to each other and AND'd to the rest of the clause
					$keyword = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchkeyword','searchkeyword','')));
					$subject = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchsubject','searchsubject','','int')));
					$desc = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchdescription','searchdescription','','int')));
					$solution = safe(trim($mainframe->getUserStateFromRequest('hh_list.searchsolution','searchsolution','','int')));
					if(strlen($keyword)>0 && ($subject==1 || $desc==1 || $solution==1))
					{
						$where = $where.' AND (false '; //stub
						if($subject==1) $where = $where.' OR p.title LIKE \'%'.$keyword.'%\'';
						if($desc==1) $where = $where.' OR p.description LIKE \'%'.$keyword.'%\'';
						if($solution==1) $where = $where.' OR p.solution LIKE \'%'.$keyword.'%\'';
						$where = $where.')';
					}
					
					//if this is a knowledgebase search, then set the kb flag
					if($stype=='kb') $where = $where.' AND p.kb=1';

					if(DEBUG) dumpdebug($where);

					break;
			}
			
			//build ORDER BY clause based on order parameter
			$order = safe(trim($mainframe->getUserStateFromRequest('hh_list.order','order','')));
			switch ($order)
			{
				case 'id':
					$orderby = ' ORDER BY p.id';
					break;
				case 'title':
					$orderby = ' ORDER BY p.title';
					break;
				case 'uid':
					$orderby = ' ORDER BY p.uid';
					break;
				case 'rep':
					$orderby = ' ORDER BY ju.username';
					break;
				case 'moddate':
					$orderby = ' ORDER BY maxresults.maxdate';
					break;
				case 'date':
					$orderby = ' ORDER BY p.start_date';
					break;
				case 'priority':
					$orderby = ' ORDER BY p.priority';
					break;
				case 'status':
					$orderby = ' ORDER BY s.status_id';
					break;
				default:
					$orderby = ' ORDER BY p.priority';
					break;
			}
			
			//sort order ASC or DESC depending on sort parameter
			$sort = safe(trim($mainframe->getUserStateFromRequest('hh_list.sort','sort','')));
			switch ($sort)
			{
				case 'a':
					$orderby = $orderby.' ASC';
					break;
				case 'd':
					$orderby = $orderby.' DESC';
					break;
				default:
					$orderby = $orderby.' DESC';
					break;
			}
			
			//always order by status secondarily
			$orderby = $orderby.', s.status_id ASC';
			
//OLD			$query = "SELECT p.id as id, p.title as title, p.uid as uid, p.start_date as start_date, pr.pname as priority, s.sname as status FROM #__huruhelpdesk_problems AS p LEFT OUTER JOIN #__huruhelpdesk_priority AS pr ON p.priority=pr.priority_id LEFT OUTER JOIN #__huruhelpdesk_status AS s ON p.status=s.id ";
//ADDS REPNAME		$query = "SELECT p.id as id, p.title as title, p.uid as uid, p.start_date as start_date, pr.pname as priority, s.sname as status, ju.username as repname FROM #__huruhelpdesk_problems AS p LEFT OUTER JOIN #__huruhelpdesk_priority AS pr ON p.priority=pr.priority_id LEFT OUTER JOIN #__huruhelpdesk_status AS s ON p.status=s.id LEFT OUTER JOIN #__huruhelpdesk_users AS u ON u.id = p.rep LEFT OUTER JOIN #__users AS ju ON ju.id = u.joomla_id";
			$query = "SELECT p.id as id, p.title as title, p.uid as uid, p.start_date as start_date, pr.pname as priority, s.sname as status, ju.username as repname, maxresults.maxdate AS maxdate FROM #__huruhelpdesk_problems AS p LEFT OUTER JOIN #__huruhelpdesk_priority AS pr ON p.priority=pr.priority_id LEFT OUTER JOIN #__huruhelpdesk_status AS s ON p.status=s.id LEFT OUTER JOIN #__huruhelpdesk_users AS u ON u.id = p.rep LEFT OUTER JOIN #__users AS ju ON ju.id = u.joomla_id LEFT OUTER JOIN (SELECT max(addDate) as maxdate, id FROM #__huruhelpdesk_notes GROUP BY id) maxresults ON p.id = maxresults.id";
			$query = $query.$where;
			$query = $query.$orderby;
			return $query;
	}
	
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);    
		}
		return $this->_total;
	}
	
	function getPagination()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
	}

	function getData()
	{
		$mainframe = &JFactory::getApplication();
		
		if(empty($this->data))
		{
			$query = $this->_buildQuery();

			if(DEBUG) dumpdebug($query);  //for debugging
			
			//determine if we are in a print view, if so, show all the problems
			if(JRequest::getVar('print')==1) $this->data = $this->_getList($query);
			else $this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

			$this->count = $this->getTotal();
			$mainframe->setUserState('hh_list.count',$this->count);
		}
		
		return $this->data;
	}
	
}
