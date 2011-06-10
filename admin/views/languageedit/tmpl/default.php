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
		<legend><?php echo lang('EditLanguage');?></legend>
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
					<?php echo lang('LanguageName');?>
				</td>
				<td>
					<input class="text_area" type="text" name="langname" id="langname" size="50" maxlength="50" value="<?php echo $this->row->langname;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Localized');?>
				</td>
				<td>
					<input class="text_area" type="text" name="localized" id="localized" size="50" maxlength="50" value="<?php echo $this->row->localized;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('LanguageStrings');?>
				</td>
				<td>
					<?php $link = JFilterOutput::ampReplace('index.php?option=' . JRequest::getCmd('option') . '&view=strings&cid[]=' . $this->row->id); ?>
					<input type="button" name="editstrings" id="editstrings" value="<?php echo lang('Edit...');?>" style="cursor:pointer;" onclick="location='<?php echo $link; ?>';"/>
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
