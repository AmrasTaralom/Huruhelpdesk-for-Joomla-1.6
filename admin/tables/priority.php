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

class TablePriority extends JTable
{
	var $priority_id = null;
	var $pname = null;
	
	function __construct(&$db)
	{
		parent::__construct('#__huruhelpdesk_priority','priority_id',$db);
	}
}
