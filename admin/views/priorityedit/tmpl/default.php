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
		<legend><?php echo lang('EditPriority');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo lang('Id');?>
				</td>
				<td>
					<?php if($this->row->priority_id >0) echo $this->row->priority_id;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('PriorityName');?>
				</td>
				<td>
					<input class="text_area" type="text" name="pname" id="pname" size="50" maxlength="50" value="<?php echo $this->row->pname;?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="priority_id" value="<?php echo $this->row->priority_id;?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
