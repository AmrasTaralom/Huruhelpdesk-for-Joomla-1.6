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
?>
<div class="pagetitle"><?php echo lang('PageTitle');?></div>
<?php 

//report type
$rtype = JRequest::getVar('rtype');

//setup toolbar
toolbar('closereport','refresh','home');

//add up the totals
$total_problems = 0;
$total_time = 0;
for($i=0,$n=count($this->rows); $i<$n; $i++)
{
	$row =& $this->rows[$i];

	$total_problems += $row->total;
	$total_time += $row->total_time;
}
		
//build google chart URL
$baseURL = 'http://chart.apis.google.com/chart?cht=p3&chs=700x200';
$chartDataBase = '&amp;chd=t:';
$chartLabelsBase = '&amp;chl=';
$chartColors = '&amp;chco=ff0000,880000,00ff00,008800,0000ff,000088';

?>
<form action="?" method="post" name="report">
	<table class="reporttable">
		<thead>
			<tr>
				<th align="center" class="report">
				<?php
				switch ($rtype)
				{
					case 'department':
						echo lang('Department');
						break;
					case 'category':
						echo lang('Category');
						break;
					case 'rep':
						echo lang('Rep');
						break;
				}
				?>
				</th>
				<th align="center" class="report"><?php echo lang('Problems');?></th>
				<th align="center" class="report"><?php echo lang('Time').' ('.lang('min').')';?></th>
				<th align="center" class="report"><?php echo lang('AverageTime').' ('.lang('min').')';?></th>
				<th align="center" class="report"><?php echo lang('PercentProblemTotal');?></th>
				<th align="center" class="report"><?php echo lang('PercentTimeTotal');?></th>
			</tr>
		</thead>
		
		<?php 
		
		if($total_problems > 0)
		{
			for($i=0,$n=count($this->rows); $i<$n; $i++)
			{
				$row =& $this->rows[$i];
				?>
				
				<tr>
					<td align="center" class=""> 
						<?php 
						//##my201004080406 { Fix warning
						if (!isset($chartLabels)) {
							$chartLabels = null;
						}
						//##my201004080406 }
						if(strlen($row->name)>0) echo $row->name; else echo lang('Unknown');
						if(strlen($chartLabels) > 0) $chartLabels = $chartLabels.'|'; //add delimiter if this is not the first label
						if(strlen($row->name)>0) $chartLabels = $chartLabels.$row->name; else $chartLabels = $chartLabels.lang('Unknown');
						?>
					</td>
					<td align="center" class=""> 
						<?php echo number_format($row->total);?>
					</td>
					<td align="center" class=""> 
						<?php echo number_format($row->total_time);?>
					</td>
					<td align="center" class=""> 
						<?php echo number_format($row->total_time/$row->total);?>
					</td>
					<td align="center" class=""> 
						<?php 
						//##my201004080406 { Fix warning
						if (!isset($chartData)) {
							$chartData = null;
						}
						//##my201004080406 }
						echo number_format(100*$row->total/$total_problems,1);
						if(strlen($chartData) > 0) $chartData = $chartData.','; //add a delimiter if this is not the first data point
						$chartData = $chartData.number_format(100*$row->total/$total_problems,1);
						?>
					</td>
					<td align="center" class=""> 
						<?php 
						if($total_time >0)
						{
							echo number_format(100*$row->total_time/$total_time,1);
							if(strlen($chart2Data) > 0) $chart2Data = $chart2Data.','; //add a delimiter if this is not the first data point
							$chart2Data = $chart2Data.number_format(100*$row->total_time/$total_time,1);
						}
						?>
					</td>
				</tr>
				<?php 
			}
			?>
			<tr class="totals">
				<td align="center" class="totals">
					<?php echo lang('Total');?>
				</td>
				<td align="center" class="totals"> 
					<?php echo number_format($total_problems);?>
				</td>
				<td align="center" class="totals"> 
					<?php echo number_format($total_time);?>
				</td>
				<td align="center" class="totals"> 
					<?php echo number_format($total_time/$total_problems);?>
				</td>
				<td align="center" class="totals"> 
				</td>
				<td align="center" class="totals"> 
				</td>
			</tr>
			<?php 
		}
		else
		{
			?>
			<tr><td colspan="6"><?php echo lang('NoResultsFound');?></td></tr>
			<?php 
		}
		?>
				
	</table>
	
	<?php 
	if($total_problems > 0)
	{
		$chartURL = $baseURL.$chartDataBase.$chartData.$chartLabelsBase.$chartLabels.$chartColors;
		?>
		<p class="charttitle"><?php echo lang('PercentProblemTotal');?>
		<img src="<?php echo $chartURL;?>" alt="<?php echo lang('PercentProblemTotal');?>" align="center"/></p>

		<?php 
		if($total_time >0)
		{
			$chartURL = $baseURL.$chartDataBase.$chart2Data.$chartLabelsBase.$chartLabels.$chartColors;
			?>
			<p class="charttitle"><?php echo lang('PercentTimeTotal');?>
			<img src="<?php echo $chartURL;?>" alt="<?php echo lang('PercentTimeTotal');?>" align="center"/></p>
			<?php
		}
	}
	?>

	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="type" value="<?php echo JRequest::getVar('type',''); ?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>


	

		