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

class TableConfig extends JTable
{
	var $id = null;
	var $hdnotifyname = null;
	var $hdreply = null;
	var $hdurl = null;
	var $notifyuser = null;
	var $enablekb = null;
	var $defaultpriority = null;
	var $pagerpriority = null;
	var $defaultstatus = null;
	var $closestatus = null;
	var $defaultdepartment = null;
	var $defaultcategory = null;
	var $allowanonymous = null;
	var $userselect = null;
	var $defaultlang = null;
	var $fileattach_allow = null;
	var $fileattach_allowedextensions = null;
	var $fileattach_maxsize = null;
	var $fileattach_download = null;
	var $fileattach_maxage = null;
	var $notifyadminonnewcases = null;

//	var $show_username = null;
//	var $show_email = null;
	var $show_department = null;
	var $show_location = null;
	var $show_phone = null;
	var $show_category = null;
	var $show_status = null;
	var $show_priority = null;
	var $show_rep = null;
	var $show_timespent = null;

//	var $set_username = null;
//	var $set_email = null;
	var $set_department = null;
	var $set_location = null;
	var $set_phone = null;
	var $set_category = null;
	var $set_status = null;
	var $set_priority = null;
	var $set_rep = null;
	var $set_timespent = null;

	function __construct(&$db)
	{
		parent::__construct('#__huruhelpdesk_config','id',$db);
	}
}
