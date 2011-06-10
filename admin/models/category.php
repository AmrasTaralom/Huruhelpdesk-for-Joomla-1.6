<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
//check user auth level
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'auth.php'; //sends us to the core helper files
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.model');

class HuruHelpdeskModelCategory extends JModel
{
	var $_total = null;
	var $_pagination = null;
	var $_search = null;
	var $_query = null;
	var $data = null;

	function __construct()
	{
		parent::__construct();
 
        $mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
 
        // Get pagination request variables
        $limit = safe($mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int'));
        $limitstart = safe(JRequest::getVar('limitstart', 0, '', 'int'));
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
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

			if(DEBUG) echo $query;  //for debugging
			
			$this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			$this->count = $this->getTotal();
			$mainframe->setUserState('hh_userlist.count',$this->count);
		}
		
		return $this->data;
	}
	
	function getSearch()
	{
		if(!$this->_search)
		{
			$mainframe = &JFactory::getApplication();
			$option = JRequest::getCmd('option');
			
			$search = $mainframe->getUserStateFromRequest("$option.search", 'search', '', 'string');
			$this->_search = JString::strtolower($search);
		}
		
		return $this->_search;
	}

	function _buildQuery()
	{
		if(!$this->_query)
		{
			$search = $this->getSearch();
			$this->_query = "SELECT * FROM #__huruhelpdesk_categories";
			
			if($search != '')
			{
				$fields = array('cname');
				$where = array();
				$search = $this->_db->getEscaped($search,true);
				
				foreach($fields as $field)
				{
					$where[] = $field." LIKE '%{$search}%'";
				}
				
				$this->_query .= ' WHERE '.implode(' OR ',$where);
			}

			$this->_query .= " ORDER BY cname ";
		}
		
		if(DEBUG) echo $this->_query;
		return $this->_query;
	}
}