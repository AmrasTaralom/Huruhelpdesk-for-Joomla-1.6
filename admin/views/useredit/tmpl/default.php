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
		<legend><?php echo lang('EditUser');?></legend>
		<table class="admintable">
			<tr>
				<td colspan="2">
					<b><?php echo lang('JoomlaUserInfo');?>:</b>&nbsp;&nbsp;
					<input type="button" value="Edit..." style="cursor:pointer;" onclick="window.location='index.php?option=com_users&task=view';" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Name');?>
				</td>
				<td>
					<?php
					//get user info
					$query = 'SELECT name, username, email FROM #__users WHERE id='.$this->row->joomla_id;
					$db =& JFactory::getDBO();
					$db->setQuery($query);

					$name= $db->loadRow();
					echo $name[0];
					?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Username');?>
				</td>
				<td>
					<?php echo $name[1];?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Email');?>
				</td>
				<td>
					<?php echo $name[2];?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('JoomlaID');?>
				</td>
				<td>
					<?php echo $this->row->joomla_id;?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<br /><b><?php echo lang('HuruUserInfo');?>:</b>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('HuruID');?>
				</td>
				<td>
					<?php
					if($this->row->id >0) echo $this->row->id;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Phone');?>
				</td>
				<td>
					<input class="text_area" type="text" name="phone" id="phone" size="50" maxlength="50" value="<?php echo $this->row->phone;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('HomePhone');?>
				</td>
				<td>
					<input class="text_area" type="text" name="phonehome" id="phonehome" size="50" maxlength="50" value="<?php echo $this->row->phonehome;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('MobilePhone');?>
				</td>
				<td>
					<input class="text_area" type="text" name="phonemobile" id="phonemobile" size="50" maxlength="50" value="<?php echo $this->row->phonemobile;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('PagerAddress');?>
				</td>
				<td>
					<input class="text_area" type="text" name="pageraddress" id="pageraddress" size="50" maxlength="100" value="<?php echo $this->row->pageraddress;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Location1');?>
				</td>
				<td>
					<input class="text_area" type="text" name="location1" id="location1" size="50" maxlength="50" value="<?php echo $this->row->location1;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Location2');?>
				</td>
				<td>
					<input class="text_area" type="text" name="location2" id="location2" size="50" maxlength="50" value="<?php echo $this->row->location2;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Department');?>
				</td>
				<td>
					<select name="department">
						<?php
						//get list of departments
						$query = 'SELECT * FROM #__huruhelpdesk_departments ORDER BY dname';
						$db =& JFactory::getDBO();
						$db->setQuery($query);

						foreach($db->loadAssocList() as $drow)
						{
							?>
							<option value="<?php echo $drow['department_id'];?>" <?php if($this->row->department==$drow['department_id']) echo " selected";?> ><?php echo $drow['dname'];?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Language');?>
				</td>
				<td>
					<select name="language">
						<?php
						//get list of languages
						$query = 'SELECT * FROM #__huruhelpdesk_language ORDER BY langname';
						$db =& JFactory::getDBO();
						$db->setQuery($query);

						foreach($db->loadAssocList() as $lrow)
						{
							?>
							<option value="<?php echo $lrow['id'];?>" <?php if($this->row->language==$lrow['id']) echo " selected";?> ><?php echo $lrow['langname'];?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('IsUser');?>
				</td>
				<td>
					<input type="checkbox" name="isuser" id="isuser" value="1" <?php if($this->row->isuser == '1') echo " checked";?> />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('IsRep');?>
				</td>
				<td>
					<input type="checkbox" name="isrep" id="isrep" value="1" <?php if($this->row->isrep == '1') echo " checked";?> />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('IsAdmin');?>
				</td>
				<td>
					<input type="checkbox" name="isadmin" id="isadmin" value="1" <?php if($this->row->isadmin == '1') echo " checked";?> />
					<?php echo lang('UserSuperAdminNote');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('ViewReports');?>
				</td>
				<td>
					<input type="checkbox" name="viewreports" id="viewreports" value="1" <?php if($this->row->viewreports == '1') echo " checked";?> />
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="joomla_id" value="<?php echo $this->row->joomla_id;?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
