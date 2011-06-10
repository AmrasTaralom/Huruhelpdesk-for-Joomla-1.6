<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files


//Pagination
// Get data from the model
$items =& $this->get('Data');      
$pagination =& $this->get('Pagination');
// push data into the template
$this->assignRef('items', $items);     
$this->assignRef('pagination', $pagination);

?>

<form action="index.php" method="post" name="adminForm">
	<table>
		<tr>
			<td>
				<b><?php echo lang('ManageUsers');?></b>
			</td>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo lang('Search');?>: <input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
				<input type="submit" value="<?php echo lang('Go');?>" />
			</td>
			<td align="right">
				<input type="button" name="importusers" id="importusers" value="<?php echo lang('SyncJoomlaUsers');?>" style="cursor:pointer;"
				onclick="if(window.confirm('<?php echo lang('SyncJoomlaUsersConfirm');?>')) submitbutton('importusers');" />
			</td>
		</tr>
	</table>
	<p> </p>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th><?php echo lang('Name');?></th>
				<th><?php echo lang('Username');?></th>
				<th><?php echo lang('Email');?></th>
				<th><?php echo lang('User');?></th>
				<th><?php echo lang('Rep');?></th>
				<th><?php echo lang('Admin');?></th>
				<th width="50"><?php echo lang('HuruID');?></th>
				<th width="50"><?php echo lang('JoomlaID');?></th>
			</tr>
		</thead>
		
		<?php 
		$k = 0;
		
		for($i=0,$n=count($this->rows); $i<$n; $i++)
		{
			$row =& $this->rows[$i];
			$checked = JHTML::_('grid.id', $i, $row->id);
			$link = JFilterOutput::ampReplace('index.php?option=' . JRequest::getCmd('option') . '&view=user&task=edit&cid[]=' . $row->id.'&j_id=' . $row->j_id);
			?>
			
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
				</td>
				<td align="center"> 
					<?php echo $row->username; ?>
				</td>
				<td align="center"> 
					<?php echo $row->email; ?>
				</td>
				<td align="center"> 
					<?php if($row->isuser) echo "<img src='images/tick.png' width='16' height='16' border='0' />"; ?>
				</td>
				<td align="center"> 
					<?php if($row->isrep) echo "<img src='images/tick.png' width='16' height='16' border='0' />"; ?>
				</td>
				<td align="center"> 
					<?php if($row->isadmin) echo "<img src='images/tick.png' width='16' height='16' border='0' />"; ?>
				</td>
				<td align="center"> 
					<?php echo $row->id; ?>
				</td>
				<td align="center"> 
					<?php echo $row->j_id; ?>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
		}
		?>
		<tfoot>
			<tr>
				<td class="" colspan="9"><?php echo $this->pagination->getListFooter();?></td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="j_id" value="<?php echo $j_id; ?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
		