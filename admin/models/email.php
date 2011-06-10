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

class HuruHelpdeskModelEmail extends JModel
{
	var $data = null;
	function getData()
	{
		if(empty($this->data))
		{
			$query = "SELECT * FROM #__huruhelpdesk_emailmsg ORDER BY type";
			$this->data = $this->_getList($query);
		}
		
		return $this->data;
	}
}