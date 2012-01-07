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
//if(!checkusermin('user') && userlevel()<config('enablekb')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/list.js"></script>
</head>
<?php
$userlvl = userlevel();

//$type = JRequest::getVar('type',''); 
$type = trim($mainframe->getUserStateFromRequest('hh_list.type','type','')); //get list type
$stype = trim($mainframe->getUserStateFromRequest('hh_list.stype','stype','')); //get search list type if available

//determine if we are in a print view
if(JRequest::getVar('print')==1) $printing = true;
else $printing = false;

//build link for re-sorting table
//$sortlink = JFilterOutput::ampReplace('index.php?option='.$option.'&view=list&type='.$type.'&Itemid='.JRequest::getVar('Itemid',''));
$sortlink = JFilterOutput::ampReplace('?option=com_huruhelpdesk&view=list&type='.$type.'&Itemid='.JRequest::getVar('Itemid',''));

//check for limits on days & user
$days = $mainframe->getUserStateFromRequest('hh_list.days','days','','int');
if($days) $sortlink = $sortlink.'&days='.$days;
$hid = $mainframe->getUserStateFromRequest('hh_list.user','user','','int');
if($hid) $sortlink = $sortlink.'&user='.$hid;

//sort order
$sort = $mainframe->getUserStateFromRequest('hh_list.sort','sort','d');
$order = $mainframe->getUserStateFromRequest('hh_list.order','order','priority');
if($sort=='a') 
{
	$sortlink = $sortlink.'&sort=d';
	$sortimage = 'components/com_huruhelpdesk/images/uparrow.png';
}
else
{
	$sortlink = $sortlink.'&sort=a';
	$sortimage = 'components/com_huruhelpdesk/images/downarrow.png';
}

//Pagination
// Get data from the model
$items =& $this->get('Data');      
$pagination =& $this->get('Pagination');
// push data into the template
$this->assignRef('items', $items);     
$this->assignRef('pagination', $pagination);

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

//get result count
$count = $mainframe->getUserState('hh_list.count','');

//setup toolbar
if($printing) toolbar('printout','closeprint'); 
elseif($type == 'search') toolbar('searchagain','printlist','refresh','home');
//elseif($type == 'search') toolbar('searchagain','printlist','refresh','home','submit');
else toolbar('refresh','printlist','home','submit');
?>
<form action="<?php echo $sortlink;?>" method="post" name="listForm">
	<table class="problemlist">
		<thead>
			<tr>
				<th align="center" colspan="8" class="count">
					<span id="count">
						<?php
						if($count>0)
						{
							echo $count;
							echo " "; 
							echo lang('Problemsfound');
						}
						else echo lang('NoResultsFound');
						?>
					</span>
				</th>
			</tr>
			<?php 
			if($count>0)
			{
				if(!$printing)
				{
					?>
					<tr>
						<?php if(true && $stype!='kb'){?><th align="center"><a href="javascript:setorder('id');" class="listhead"><?php echo lang('ID');?></a><img class="sortpointer" src="<?php if($order=='id') echo $sortimage;?>"></th><?php }?>
						<th align="center"><a href="javascript:setorder('title');" class="listhead"><?php echo lang('Title');?></a><img class="sortpointer" src="<?php if($order=='title') echo $sortimage;?>"></th>
						<?php if($stype!='kb'){?><th align="center"><a href="javascript:setorder('uid');" class="listhead"><?php echo lang('User');?></a><img class="sortpointer" src="<?php if($order=='uid') echo $sortimage;?>"></th><?php }?>
						<?php if($type!='assigned'){?><th align="center"><a href="javascript:setorder('rep');" class="listhead"><?php echo lang('Rep');?></a><img class="sortpointer" src="<?php if($order=='rep') echo $sortimage;?>"></th><?php }?>
						<th align="center"><a href="javascript:setorder('date');" class="listhead"><?php echo lang('DateSubmitted');?></a><img class="sortpointer" src="<?php if($order=='date') echo $sortimage;?>"></th>
						<th align="center"><a href="javascript:setorder('moddate');" class="listhead"><?php echo lang('Updated');?></a><img class="sortpointer" src="<?php if($order=='moddate') echo $sortimage;?>"></th>
						<?php if($stype!='kb'){?><th align="center"><a href="javascript:setorder('priority');" class="listhead"><?php echo lang('Priority');?></a><img class="sortpointer" src="<?php if($order=='priority' || $order=='') echo $sortimage;?>"></th><?php }?>
						<th align="center"><a href="javascript:setorder('status');" class="listhead"><?php echo lang('Status');?></a><img class="sortpointer" src="<?php if($order=='status') echo $sortimage;?>"></th>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<?php if(true && $stype!='kb'){?><th align="center"><?php echo lang('ID');?></a></th><?php }?>
						<th align="center"><?php echo lang('Title');?></a></th>
						<?php if($stype!='kb'){?><th align="center"><?php echo lang('User');?></a></th><?php }?>
						<?php if($type!='assigned'){?><th align="center"><?php echo lang('Rep');?></a></th><?php }?>
						<th align="center"><?php echo lang('DateSubmitted');?></a></th>
						<th align="center"><?php echo lang('Updated');?></a></th>
						<?php if($stype!='kb'){?><th align="center"><?php echo lang('Priority');?></a></th><?php }?>
						<th align="center"><?php echo lang('Status');?></a></th>
					</tr>
					<?php
				}
			}
			?>
		</thead>
		
		<?php 
		$k = 0;
		
		for($i=0,$n=count($this->rows); $i<$n; $i++)
		{
			$row =& $this->rows[$i];
			//base link
			$link = JFilterOutput::ampReplace('?option=' . JRequest::getCmd('option') . '&view=detail&type='.$type.'&task=edit&cid[]=' . $row->id);

			?>
			
			<tr>
				<?php if(true && $stype!='kb'){?>
					<td align="center" class="problemlist"> 
						<?php echo $row->id; ?>
					</td>
				<?php }?>
				<td align="center" class="problemlist"> 
					<?php 
					if(!$printing)
					{
						?>
						<a href="<?php echo $link; ?>" class="listlink" onclick="javascript:detail('<?php echo $row->id;?>');"><?php echo $row->title; ?></a>
						<?php
					}
					else echo $row->title;
					?>
				</td>
				<?php if($stype!='kb'){?>
					<td align="center" class="problemlist"> 
						<?php echo $row->uid; ?>
					</td>
				<?php }?>
				<?php if($type!='assigned'){?>
					<td align="center" class="problemlist"> 
						<?php echo $row->repname;?>
					</td>
				<?php }?>
				<td align="center" class="problemlist"> 
					<?php echo $row->start_date; ?>
				</td>
				<td align="center" class="problemlist"> 
					<?php echo $row->maxdate; ?>
				</td>
				<?php if($stype!='kb'){?>
					<td align="center" class="problemlist"> 
						<?php echo $row->priority; ?>
					</td>
				<?php }?>
				<td align="center" class="problemlist"> 
					<?php echo $row->status; ?>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
		}
		?>
	</table>
	<?php 
	if($count>0 && !$printing)
	{
		?>
		<table class="pagination" align="center">
			<tr>
				<td class="pagination" colspan="6"><?php echo $this->pagination->getListFooter();?></td>
			</tr>
		</table>
		<?php 
	}
	?>
	<input type="hidden" name="option" id="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" id="viewid" value="<?php echo $mainframe->getUserStateFromRequest('hh_list.view','view',''); ?>" />
	<input type="hidden" name="task" id="taskid" value="<?php echo $mainframe->getUserStateFromRequest('hh_list.task','task',''); ?>" />
	<input type="hidden" name="type" id="type" value="<?php echo $type; ?>" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $mainframe->getUserStateFromRequest('hh_list.Itemid','Itemid',''); ?>" />
	<input type="hidden" name="order" id="order" value="" />
	<input type="hidden" name="cid[]" id="cidid" value="" />
	<input type="hidden" name="days" id="days" value="<?php echo $days;?>" />
	<input type="hidden" name="user" id="user" value="<?php echo $hid;?>" />

	<?php if(DEBUG) dumpdebug();?>

	<?php echo JHTML::_('form.token'); ?>
</form>

<?php
//setup the list table title that will be displayed above the list
switch ($type)
{
	case 'search':
		$msg = lang('SearchResults');
		break;
	case 'all':
		if($hid) $msg = lang('OpenProblems').' '.lang('for').' '.userinfo($hid,'name');
		elseif($days > 0) $msg = lang('Problems').' '.lang('forprevious').' '.$days.' '.lang('days');
		elseif($days == -1) $msg = lang('All').' '.lang('Problems');
		else $msg = lang('All').' '.lang('OpenProblemsLC');
		break;
	case 'assigned':
		$msg = lang('OpenProblemsFor');
		$msg = $msg.' '.currentuserinfo('name');
		break;
	case 'submitted':
		$msg = lang('ProblemsSubmittedBy');
		$msg = $msg.' '.currentuserinfo('name');
		break;
}
?>
<script language="javascript">displayMessage('<?php echo $msg;?>');</script>
