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
if(!checkuser('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
</head>
<?php

//display page title if configured
$params	=& $mainframe->getParams('com_content');
$this->assignRef('params' , $params);
if ($this->params->get('show_page_title',1))
{
	?>
	<div class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
		<?php echo $this->escape($this->params->get('page_title')); ?>
	</div>
	<?php
}

//report type
$days = JRequest::getVar('days','','','int');

//determine if we are in a print view
if(JRequest::getVar('print')==1) $printing = true;
else $printing = false;

//setup toolbar
if($printing) toolbar('printout','closeprint'); 
else toolbar('printactivity','close','refresh','home');

//build message string
$startdate = date('n/d/Y', time() - $days*86400);
$enddate = date('n/d/Y');
$msg = lang('ActivitySummary').' '.$startdate.' '.lang('through').' '.$enddate;

echo '<span class="toolbarmessage">'.$msg.'</span>';
?>

<form action="index.php" method="post" name="report">
	<ul>
		<?php 
		for($i=0,$n=count($this->rows); $i<$n; $i++)
		{
			$row =& $this->rows[$i];
			//find last mod date
			if(strlen($row->maxdate)>0) $moddate = date('n/d/Y',strtotime($row->maxdate)); //if there are notes for the case, use the newest as the latest mod date
			else $moddate = date('n/d/Y',strtotime($row->start_date)); //if there are no notes, then use the startdate as the mod date
			echo '<li>'.$row->title.'('.lang('Modified').':'.$moddate.' - '.lang('Status').':'.$row->sname.')</li>';
		}
		?>
	</ul>

	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="type" value="<?php echo JRequest::getVar('type',''); ?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>


	

		