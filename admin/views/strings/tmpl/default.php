<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files
?>

<b><?php echo lang('ManageLanguageStrings');?></b><p> </p>
<?php echo lang('LanguageID');?>: <?php $cid = JRequest::getVar('cid', array(0)); echo $cid[0];?>

<form action="index.php" method="post" name="adminForm">
	<table>
		<tr>
			<td>
				<?php echo lang('Search');?>: <input type="text" name="search" value="<?php echo $this->search ?>" id="search" />
				<input type="submit" value="<?php echo lang('Go');?>" />
			</td>
		</tr>
	</table>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows); ?>);" />
				</th>
				<th><?php echo lang('Variable');?></th>
				<th><?php echo lang('Text');?></th>
			</tr>
		</thead>
		
		<?php 
		$k = 0;
		for($i=0,$n=count($this->rows); $i<$n; $i++)
		{
			$row =& $this->rows[$i];
			$checked = JHTML::_('grid.id', $i, $row->id);
			$link = JFilterOutput::ampReplace('index.php?option=' . JRequest::getCmd('option') . '&view=strings&task=edit&cid[]=' . $row->id);
			?>
			
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<?php echo $checked; ?>
				</td>
				<td>
					<a href="<?php echo $link; ?>"><?php echo $row->variable; ?></a>
				</td>
				<td> 
					<?php echo $row->langtext; ?>
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
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $cid[0];?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
		