<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
//check user auth level
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';

jimport('joomla.application.component.view');

class HuruHelpdeskViewSearch extends JView
{
	function display($tpl = null)
	{
		parent::display($tpl);
	}
	
}