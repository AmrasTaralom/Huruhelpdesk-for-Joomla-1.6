<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo lang('EditStatus');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo lang('Id');?>
				</td>
				<td>
					<?php if($this->row->id >0) echo $this->row->id;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Rank');?>
				</td>
				<td>
					<input class="text_area" type="text" name="status_id" id="status_id" size="50" maxlength="50" value="<?php echo $this->row->status_id;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('StatusName');?>
				</td>
				<td>
					<input class="text_area" type="text" name="sname" id="sname" size="50" maxlength="50" value="<?php echo $this->row->sname;?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
