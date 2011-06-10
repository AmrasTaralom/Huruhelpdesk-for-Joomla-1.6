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
if(!checkusermin('rep')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.model');

class HuruHelpdeskModelInout extends JModel
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
			$type = JRequest::getVar('type');

			//build WHERE clause based on type
			//for each type, check that user has authority for type
			switch ($type)
			{
				case 'rep':
					//only show the login status of reps
					$where = ' WHERE hh.isrep = 1 OR hh.isadmin = 1';
					break;
				case 'all':
					//show the login status of everyone
					$where = '';
					break;
			}

			//build ORDER BY clause based on order parameter
			$order = JRequest::getVar('order');
			switch ($order)
			{
				case 'username':
					$orderby = ' ORDER BY ju.username';
					break;
				case 'name':
					$orderby = ' ORDER BY ju.name';
					break;
				case 'email':
					$orderby = ' ORDER BY ju.email';
					break;
				case 'rep':
					$orderby = ' ORDER BY hh.isrep';
					break;
				case 'admin':
					$orderby = ' ORDER BY hh.isadmin';
					break;
				default:
					$orderby = ' ORDER BY ju.username';
					break;
			}

			
			//sort order ASC or DESC depending on sort parameter
			$sort = JRequest::getVar('sort');
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
			
			$query = "SELECT ju.id as j_id, ju.name as name, ju.username as username, ju.email as email, hh.id as id, hh.isuser as isuser, hh.isrep as isrep, hh.isadmin as isadmin FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id ";
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
		if(empty($this->data))
		{
			$query = $this->_buildQuery();

			if(DEBUG) dumpdebug($query);  //for debugging
			
			$this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		
		return $this->data;
	}
	
}
