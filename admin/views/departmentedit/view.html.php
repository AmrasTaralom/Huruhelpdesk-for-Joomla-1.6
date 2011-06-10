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

class HuruHelpDeskViewDepartmentEdit extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');
		$row =& JTable::getInstance('Department','Table');
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$id = safe($cid[0]);
		$row->load($id);
		$this->assignRef('row',$row);
		$this->assignRef('department_id',$department_id);
		$this->assignRef('dname',$dname);
		parent::display($tpl);
	}
}