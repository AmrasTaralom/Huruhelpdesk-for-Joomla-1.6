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
if(!checkusermin('user') && !config('allowanonymous')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

class TableDetail extends JTable
{
	var $id = null;
	var $uid = null;
	var $uemail = null;
	var $ulocation = null;
	var $uphone = null;
	var $rep = null;
	var $status = null;
	var $time_spent = null;
	var $category = null;
	var $close_date = null;
	var $department = null;
	var $title = null;
	var $description = null;
	var $solution = null;
	var $start_date = null;
	var $priority = null;
	var $entered_by = null;
	var $kb = null;
	
	function __construct(&$db)
	{
		parent::__construct('#__huruhelpdesk_problems','id',$db);
	}
}
