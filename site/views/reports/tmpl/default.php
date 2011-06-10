<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');

//get user type
$mainframe = &JFactory::getApplication();
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/validation.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/reports.js"></script>
</head>
<?php
$Itemid = JRequest::getVar('Itemid');
$userlvl = userlevel();

//make sure we are allowed to view reports
if(!checkuser('reports')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH')); 

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
?>
<div class="pagetitle"><?php echo lang('PageTitle');?></div>
<?php 

//setup toolbar
toolbar('viewreport','reset','home');

?>
<form action="?option=<?php echo JRequest::getCmd('option');?>&view=report&Itemid=<?php echo JRequest::getVar('Itemid','')?>" method="post" name="reportForm" id="reportFormId">
	<table class="problemdetail searchtable">
		<tr>
			<td class="problemhead">
				<?php echo lang('SearchCriteria');?>
			</td>
		</tr>
		<tr>
			<td class="problemfieldname">
				<?php echo lang('AvailableReports');?>
			</td>
		</tr>
		<tr>
			<td>
				<input type="radio" name="rtype" value="department" checked><?php echo lang('Department');?><br />
				<input type="radio" name="rtype" value="category"><?php echo lang('Category');?><br />
				<input type="radio" name="rtype" value="rep"><?php echo lang('SupportRep');?><br />
			</td>
		</tr>
		<tr>
			<td class="problemfieldname">
				<?php echo lang('DateRange');?>: (YYYY-MM-DD)
			</td>
		</tr>
		<tr>
			<td>
				<?php echo lang('StartDate');?>: <?php echo JHTML::calendar(strftime("%Y-%m-%d", strtotime("-1 year")),'startdate','startdate','%Y-%m-%d');?><br />
				<?php echo lang('EndDate');?>: <?php echo JHTML::calendar(date('Y-m-d'),'enddate','enddate','%Y-%m-%d');?>
			</td>
		</tr>
	</table>
</form>
<script language="javascript">displayMessage('<?php echo lang('EnterReport');?>');</script>
