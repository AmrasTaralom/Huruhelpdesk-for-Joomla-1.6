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
		<legend><?php echo lang('EditEmailMessage');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo lang('Type');?>
				</td>
				<td>
					<?php echo $this->row->type;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Subject');?>
				</td>
				<td>
					<input class="text_area" type="text" name="subject" id="subject" size="80" maxlength="250" value="<?php echo $this->row->subject;?>" />
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo lang('Body');?>
				</td>
				<td>
					<textarea cols="80" rows="10" name="body" id="body"><?php echo $this->row->body;?></textarea>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo lang('AvailableSubstitutions');?>
				</td>
				<td>
					<table>
						<tr>
							<td>
								[problemid]
							</td>
							<td>
								<?php echo lang('MailProblemID');?>
							</td>
						</tr>
						<tr>
							<td>
								[title]
							</td>
							<td>
								<?php echo lang('MailTitle');?>
							</td>
						</tr>
						<tr>
							<td>
								[description]
							</td>
							<td>
								<?php echo lang('MailDescription');?>
							</td>
						</tr>
						<tr>
							<td>
								[fullname]
							</td>
							<td>
								<?php echo lang('MailFullname');?>
							</td>
						</tr>
						<tr>
							<td>
								[uid]
							</td>
							<td>
								<?php echo lang('MailUID');?>
							</td>
						</tr>
						<tr>
							<td>
								[uemail]
							</td>
							<td>
								<?php echo lang('MailUEmail');?>
							</td>
						</tr>
						<tr>
							<td>
								[phone]
							</td>
							<td>
								<?php echo lang('MailPhone');?>
							</td>
						</tr>
						<tr>
							<td>
								[location]
							</td>
							<td>
								<?php echo lang('MailLocation');?>
							</td>
						</tr>
						<tr>
							<td>
								[department]
							</td>
							<td>
								<?php echo lang('MailDepartment');?>
							</td>
						</tr>
						<tr>
							<td>
								[priority]
							</td>
							<td>
								<?php echo lang('MailPriority');?>
							</td>
						</tr>
						<tr>
							<td>
								[category]
							</td>
							<td>
								<?php echo lang('MailCategory');?>
							</td>
						</tr>
						<tr>
							<td>
								[startdate]
							</td>
							<td>
								<?php echo lang('MailStartDate');?>
							</td>
						</tr>
						<tr>
							<td>
								[url]
							</td>
							<td>
								<?php echo lang('MailURL');?>
							</td>
						</tr>
						<tr>
							<td>
								[solution]
							</td>
							<td>
								<?php echo lang('MailSolution');?>
							</td>
						</tr>
						<tr>
							<td>
								[notes]
							</td>
							<td>
								<?php echo lang('MailNotes');?>
							</td>
						</tr>
					</table>					
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="type" value="<?php echo $this->row->type;?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
