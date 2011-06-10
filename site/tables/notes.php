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

class TableNotes extends JTable
{
	var $note_id = null;
	var $id = null;
	var $note = null;
	var $adddate = null;
	var $uid = null;
	var $ip = null;
	var $private = null;
	
	function __construct(&$db)
	{
		parent::__construct('#__huruhelpdesk_notes','note_id',$db);
	}
}
