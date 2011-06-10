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

class HuruHelpDeskViewUserEdit extends JView
{
	function display($tpl = null)
	{
		$task = JRequest::getVar('task', '');
		$j_id = safe(JRequest::getVar('j_id'));
		$row =& JTable::getInstance('User','Table');
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$id = safe($cid[0]);
		$row->load($id);
		$this->assignRef('row',$row);
		$this->assignRef('joomla_id',$joomla_id);
		$this->assignRef('isuser',$isuser);
		$this->assignRef('isrep',$isrep);
		$this->assignRef('isadmin',$isadmin);
		$this->assignRef('phone',$phone);
		$this->assignRef('phonemobile',$phonemobile);
		$this->assignRef('pageraddress',$pageraddress);
		$this->assignRef('phonehome',$phonehome);
		$this->assignRef('location1',$location1);
		$this->assignRef('location2',$location2);
		$this->assignRef('department',$department);
		$this->assignRef('language',$language);
		$this->assignRef('viewreports',$viewreports);

		parent::display($tpl);
	}
}