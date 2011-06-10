<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
//check user auth level
$mainframe = &JFactory::getApplication();
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
if(!checkusermin('user') && !config('allowanonymous')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.view');

class HuruHelpDeskViewDetail extends JView
{
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		
		//page variables
		$task = JRequest::getVar('task', '');
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$type = JRequest::getVar('type','','post','string',JREQUEST_ALLOWRAW);
		$Itemid = JRequest::getVar('Itemid');
		$this->assignRef('task',$task);
		$this->assignRef('cid[]',$cid);
		$this->assignRef('type',$type);
		$this->assignRef('Itemid',$Itemid);

		//record detail
		$row =& JTable::getInstance('Detail','Table');
		$id = safe($cid[0]);

		if(!is_numeric($id)) $id = -1; //stops SQL injection that can occur with cid[] var passed in URL
		
		if($id == -1 && !empty($Itemid)) $id = safe($Itemid);
		//var_dump($id);
		
		if($row->load($id) || $id == -1) //load will be true only if a record is found, $id will be -1 or new cases
		{
			$this->assignRef('row',$row);
			$this->assignRef('id',$id);
			$this->assignRef('uid',$uid);
			$this->assignRef('uemail',$uemail);
			$this->assignRef('ulocation',$ulocation);
			$this->assignRef('uphone',$uphone);
			$this->assignRef('rep',$rep);
			$this->assignRef('status',$status);
			$this->assignRef('time_spent',$time_spent);
			$this->assignRef('category',$category);
			$this->assignRef('close_date',$close_date);
			$this->assignRef('department',$department);
			$this->assignRef('title',$title);
			$this->assignRef('description',$description);
			$this->assignRef('solution',$solution);
			$this->assignRef('start_date',$start_date);
			$this->assignRef('priority',$priority);
			$this->assignRef('entered_by',$entered_by);
			$this->assignRef('kb',$kb);
			
			if($id == -1){
				$db =& JFactory::getDBO();
				$query = "SELECT * FROM #__huruhelpdesk_categories_predeftext;";
				$db->setQuery($query);
				$result = $db->query();
				$rows = $db->loadRowList();
				$this->assignRef('catdefaults',$rows);
			}
		}
		else
		{
			$mainframe->redirect('index.php?option='.$option.'&Itemid='.$Itemid, JText::_(lang('NotFound')));
		}

		parent::display($tpl);
	}
}