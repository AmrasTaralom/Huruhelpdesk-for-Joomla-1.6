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
if(!checkusermin('user')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.view');

class HuruHelpDeskViewEditInfo extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');
		$row =& JTable::getInstance('EditInfo','Table');
		$id = currentuserinfo('huru_id');
		$row->load($id);
		$this->assignRef('row',$row);
		$this->assignRef('phone',$phone);
		$this->assignRef('phonemobile',$phonemobile);
		$this->assignRef('pageraddress',$pageraddress);
		$this->assignRef('phonehome',$phonehome);
		$this->assignRef('location1',$location1);
		$this->assignRef('location2',$location2);
		$this->assignRef('department',$department);
		$this->assignRef('language',$language);

		parent::display($tpl);
	}
}