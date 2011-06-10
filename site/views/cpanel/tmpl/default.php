<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');

//get user type
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
</head>
<?php
$Itemid = JRequest::getVar('Itemid');
$userlvl = userlevel();

//display page title if configured
$mainframe = &JFactory::getApplication();
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

<form action="index.php" method="post" name="cpanelForm">
	<?php
	if($userlvl==USER_LEVEL_ADMIN || $userlvl==USER_LEVEL_REP || $userlvl==USER_LEVEL_USER || $userlvl==USER_LEVEL_NONE)
	{
		if($userlvl!=USER_LEVEL_NONE || config('allowanonymous'))	
		{
			?>
			<img src="components/com_huruhelpdesk/images/add_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=detail&cid[]=-1"><?php echo lang('SubmitNewProblem');?></a><br />
			<?php 
		}
	}

	if($userlvl==USER_LEVEL_USER)
	{
		?>
		<img src="components/com_huruhelpdesk/images/user_add_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=list&type=submitted"><?php echo lang('ViewSubmittedProblems');?></a><br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN || $userlvl==USER_LEVEL_REP)
	{
		?>
		<img src="components/com_huruhelpdesk/images/user_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=list&type=assigned"><?php echo lang('ViewAssignedProblems');?></a><br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN)
	{
		?>
		<img src="components/com_huruhelpdesk/images/users_two_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=list&type=all"><?php echo lang('ViewProblemList');?></a><br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN)
	{
		?>
		<img src="components/com_huruhelpdesk/images/user2_24.png" class="cpanelicon"/>
		<?php echo lang('Viewproblemsfor');?> &nbsp;
		<select name="replist" id="replist" class="cpanel">
			<?php
			//get list of reps
			$query = "SELECT ju.name as name, hh.id as hid, hh.isrep FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id WHERE hh.isrep = 1 ORDER BY ju.name";
			$db =& JFactory::getDBO();
			$db->setQuery($query);

			foreach($db->loadAssocList() as $urow)
			{
				?>
				<option class="cpanel" value="<?php echo $urow['hid'];?>"><?php echo $urow['name'];?></option>
				<?php
			}
			?>
		</select>&nbsp;
		<button class="cpanel" style="cursor:pointer;" onclick="window.location='?option=com_huruhelpdesk&Itemid=<?php echo $Itemid;?>&view=list&type=all&user=' + document.getElementById('replist').value;return false;">View</button>
		<br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN)
	{
		?>
		<img src="components/com_huruhelpdesk/images/newspaper_24.png" class="cpanelicon"/>
		<?php echo lang('ViewProblemsFromLast');?>
		<select name="days" id="days" class="cpanel">
			<option class="cpanel" value="-1"><?php echo lang('NoLimit');?></option>
			<option class="cpanel" value="1">1</option>
			<option class="cpanel" value="7">7</option>
			<option class="cpanel" value="8">8</option>
			<option class="cpanel" value="14">14</option>
			<option class="cpanel" value="30">30</option>
			<option class="cpanel" value="90">90</option>
			<option class="cpanel" value="365">365</option>
		</select>
		<?php echo lang('days').'.';?>&nbsp;&nbsp;
		<button class="cpanel" onclick="window.location='?option=com_huruhelpdesk&Itemid=<?php echo $Itemid;?>&view=list&type=all&days=' + document.getElementById('days').value;return false;"><?php echo lang('View');?></button>
		<button class="cpanel" onclick="window.location='?option=com_huruhelpdesk&Itemid=<?php echo $Itemid;?>&view=activity&days=' + document.getElementById('days').value;return false;"><?php echo lang('Activity');?></button>
		<br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN || $userlvl==USER_LEVEL_REP || $userlvl==USER_LEVEL_USER || ($userlvl==USER_LEVEL_NONE && config('allowanonymous')))
	{
		?>
		<img src="components/com_huruhelpdesk/images/folder_24.png" class="cpanelicon"/><?php echo lang('ProblemID');?>
		<input type="text" class="cpanel" name="problemid" id="problemidtext" size="6" maxlength="6"/>&nbsp;
		<?php 
		if($userlvl == USER_LEVEL_NONE)
		{
			?>
			&nbsp;<?php echo lang('EnterVerification');?>: <input type="text" class="cpanel" name="chk" id="chk" size="15" maxlength="255" value="<?php echo lang('EmailAddress');?>"/>&nbsp;
			<button class="cpanel" style="cursor:pointer;" onclick="window.location='?option=com_huruhelpdesk&Itemid=<?php echo $Itemid;?>&view=detail&cid[]=' + document.getElementById('problemidtext').value + '&chk=' + document.getElementById('chk').value;return false;"><?php echo lang('View');?></button><br />
			<?php
		}
		else
		{
		?>
			<button class="cpanel" style="cursor:pointer;" onclick="window.location='?option=com_huruhelpdesk&Itemid=<?php echo $Itemid;?>&view=detail&cid[]=' + document.getElementById('problemidtext').value;return false;"><?php echo lang('View');?></button><br />
		<?php
		}
	}

	if($userlvl==USER_LEVEL_ADMIN)
	{
		?>
		<img src="components/com_huruhelpdesk/images/cancel_24.png" class="cpanelicon"/><?php echo lang('DeleteProblem');?>
		<input type="text" class="cpanel" name="problemdeleteid" id="problemdeleteidtext" size="6" maxlength="6"/>&nbsp;
		<button class="cpanel" style="cursor:pointer;" onclick="if(confirmation(1)) window.location='?option=com_huruhelpdesk&Itemid=<?php echo $Itemid;?>&view=cpanel&task=deletecase&id=' + document.getElementById('problemdeleteidtext').value;return false;"><?php echo lang('Delete');?></button><br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN)
	{
		?>
		<img src="components/com_huruhelpdesk/images/search_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=search&stype=all"><?php echo lang('SearchProblems');?></a><br />
		<?php
	}
	
	$kblevel = config('enablekb');
	if( 	$kblevel == KB_LEVEL_ALL
		|| ($kblevel == KB_LEVEL_USER  && $userlvl >= USER_LEVEL_USER) 
		|| ($kblevel == KB_LEVEL_REP   && $userlvl >= USER_LEVEL_REP)
		|| ($kblevel == KB_LEVEL_ADMIN && $userlvl >= USER_LEVEL_ADMIN)
		)
	{
		?>
		<img src="components/com_huruhelpdesk/images/lightbulb_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=search&stype=kb"><?php echo lang('SearchtheKnowledgeBase');?></a><br />
		<?php 
	}

	if($userlvl==USER_LEVEL_ADMIN || $userlvl==USER_LEVEL_REP)
	{
		if(checkuser('reports'))
		{
			?>
			<img src="components/com_huruhelpdesk/images/paper_content_chart_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=reports"><?php echo lang('Reports');?></a><br />
			<?php 
		}
	}

	if($userlvl==USER_LEVEL_ADMIN || $userlvl==USER_LEVEL_REP)
	{
		?>
		<img src="components/com_huruhelpdesk/images/green_pin.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=inout"><?php echo lang('InOutBoard');?></a><br />
		<?php
	}

	if($userlvl==USER_LEVEL_ADMIN || $userlvl==USER_LEVEL_REP || $userlvl==USER_LEVEL_USER)
	{
		?>
		<img src="components/com_huruhelpdesk/images/mail_write_24.png" class="cpanelicon"/><a class="cpanel" href="?option=com_huruhelpdesk&view=editinfo"><?php echo lang('EditInformation');?></a>
		<?php
	}
	?>
	<input type="hidden" name="option" id="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" id="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="type" id="type" value="<?php echo JRequest::getVar('type',''); ?>" />
	<input type="hidden" name="Itemid" id="itemid" value="<?php echo JRequest::getVar('Itemid',''); ?>" />
</form>

<?php if(DEBUG) dumpdebug();?>
