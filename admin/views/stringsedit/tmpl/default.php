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
		<legend><?php echo lang('EditString');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo lang('Variable');?>
				</td>
				<td>
					<?php echo $this->row->variable;?>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo lang('Text');?>
				</td>
				<td>
					<input type="text" size="100" maxlength="255" name="langtext" id="langtext" value="<?php echo $this->row->langtext;?>" />
<!--					<textarea cols="80" rows="10" name="langtext" id="langtext"><?php echo $this->row->langtext;?></textarea>-->
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="variable" value="<?php echo $this->row->variable;?>" />
	<input type="hidden" name="lang_id" value="<?php echo $this->row->lang_id;?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
