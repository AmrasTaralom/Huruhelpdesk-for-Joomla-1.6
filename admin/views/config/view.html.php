<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
//check user auth level
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'auth.php'; //sends us to the core helper files
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.view');

class HuruHelpDeskViewConfig extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');

		//get config data
		$row =& JTable::getInstance('Config','Table');
		$id = 1; //there is only one config record, with the id=1 (set during installation) so we don't need to look at the cid even though it might be sent
		$row->load($id);
		$this->assignRef('row',$row);
		$this->assignRef('id',$id);
		$this->assignRef('hdnotifyname',$hdnotifyname);
		$this->assignRef('hdreply',$hdreply);
		$this->assignRef('hdurl',$hdurl);
		$this->assignRef('notifyuser',$notifyuser);
		$this->assignRef('enablekb',$enablekb);
		$this->assignRef('defaultpriority',$defaultpriority);
		$this->assignRef('pagerpriority',$pagerpriority);
		$this->assignRef('defaultstatus',$defaultstatus);
		$this->assignRef('closestatus',$closestatus);
		$this->assignRef('defaultdepartment',$defaultdepartment);
		$this->assignRef('defaultcategory',$defaultcategory);
		$this->assignRef('allowanonymous',$allowanonymous);
		$this->assignRef('userselect',$userselect);
		$this->assignRef('defaultlang',$defaultlang);
		$this->assignRef('fileattach_allow',$fileattach_allow);
		$this->assignRef('fileattach_allowedextensions',$fileattach_allowedextensions);
		$this->assignRef('fileattach_maxsize',$fileattach_maxsize);
		$this->assignRef('fileattach_download',$fileattach_download);
		$this->assignRef('fileattach_maxage',$fileattach_maxage);
		$this->assignRef('notifyadminonnewcases',$notifyadminonnewcases);

//		$this->assignRef('show_username',$show_username);
//		$this->assignRef('show_email',$show_email);
		$this->assignRef('show_department',$show_department);
		$this->assignRef('show_location',$show_location);
		$this->assignRef('show_phone',$show_phone);
		$this->assignRef('show_category',$show_category);
		$this->assignRef('show_status',$show_status);
		$this->assignRef('show_priority',$show_priority);
		$this->assignRef('show_rep',$show_rep);
		$this->assignRef('show_timespent',$show_timespent);

//		$this->assignRef('set_username',$set_username);
//		$this->assignRef('set_email',$set_email);
		$this->assignRef('set_department',$set_department);
		$this->assignRef('set_location',$set_location);
		$this->assignRef('set_phone',$set_phone);
		$this->assignRef('set_category',$set_category);
		$this->assignRef('set_status',$set_status);
		$this->assignRef('set_priority',$set_priority);
		$this->assignRef('set_rep',$set_rep);
		$this->assignRef('set_timespent',$set_timespent);
		
		parent::display($tpl);
	}
}