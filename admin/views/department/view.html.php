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

jimport('joomla.application.component.view');

class HuruHelpDeskViewDepartment extends JView
{
	function display($tpl = null)
	{
		$rows =& $this->get('data');
		$pagination =& $this->get('pagination');
		$search = $this->get('search');
		
		$this->assignRef('rows',$rows);
		$this->assignRef('pagination', $pagination);
		$this->assign('search', $search);
		
		parent::display($tpl);
	}
}