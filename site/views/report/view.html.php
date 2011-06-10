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
if(!checkuser('reports')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

jimport('joomla.application.component.view');

class HuruHelpDeskViewReport extends JView
{
	function display($tpl = null)
	{
		$rows =& $this->get('data');
		$this->assignRef('rows',$rows);
		parent::display($tpl);
	}
}