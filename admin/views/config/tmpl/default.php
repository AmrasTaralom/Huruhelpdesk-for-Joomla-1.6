<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files
?>
<style type="text/css">
	td.configsectionhead {font-weight: bold; margin-top: 10px; color: #000; height: 30px; vertical-align: bottom;}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo lang('GeneralConfiguration');?></legend>
		<table class="admintable">


			<tr>
				<td class="configsectionhead" colspan="2"><?php echo lang('Notifications');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('NotificationSenderName');?>
				</td>
				<td>
					<input class="text_area" type="text" name="hdnotifyname" id="hdnotifyname" size="50" maxlength="50" value="<?php echo $this->row->hdnotifyname;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('ReplyAddress');?>
				</td>
				<td>
					<input class="text_area" type="text" name="hdreply" id="hdreply" size="50" maxlength="50" value="<?php echo $this->row->hdreply;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('BaseURL');?>
				</td>
				<td>
					<input class="text_area" type="text" name="hdurl" id="hdurl" size="75" maxlength="255" value="<?php echo $this->row->hdurl;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('NotifyAdminOnNewCase');?>
				</td>
				<td>
					<input class="text_area" type="text" name="notifyadminonnewcases" id="notifyadminonnewcases" size="75" maxlength="255" value="<?php echo $this->row->notifyadminonnewcases;?>" /> <?php echo lang('LeaveBlank');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('NotifyUserOnCaseUpdate');?>
				</td>
				<td>
					<input type="checkbox" name="notifyuser" id="notifyuser" value="1" <?php if($this->row->notifyuser == '1') echo " checked";?> />
				</td>
			</tr>


			<tr>
				<td class="configsectionhead" colspan="2"><?php echo lang('Defaults');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('DefaultPriority');?>
				</td>
				<td>
					<select name="defaultpriority">
						<?php
						//get list of priorities
						$query = 'SELECT * FROM #__huruhelpdesk_priority ORDER BY priority_id';
						$db =& JFactory::getDBO();
						$db->setQuery($query);

						foreach($db->loadAssocList() as $prow)
						{
							?>
							<option value="<?php echo $prow['priority_id'];?>" <?php if($this->row->defaultpriority==$prow['priority_id']) echo " selected";?> ><?php echo $prow['pname'];?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('PagerPriority');?>
				</td>
				<td>
					<select name="pagerpriority">
						<?php
						//get list of priorities
						$query = 'SELECT * FROM #__huruhelpdesk_priority ORDER BY priority_id';
						$db =& JFactory::getDBO();
						$db->setQuery($query);

						foreach($db->loadAssocList() as $prow)
						{
							?>
							<option value="<?php echo $prow['priority_id'];?>" <?php if($this->row->pagerpriority==$prow['priority_id']) echo " selected";?> ><?php echo $prow['pname'];?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('DefaultStatus');?>
				</td>
				<td>
					<select name="defaultstatus">
						<?php
						//get list of statuses (will same array for closestatus below)
						$query = 'SELECT * FROM #__huruhelpdesk_status';
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						$statuses = $db->loadAssocList();
						foreach($statuses as $srow)
						{
							?>
							<option value="<?php echo $srow['id'];?>" <?php if($this->row->defaultstatus==$srow['id']) echo " selected";?> ><?php echo $srow['sname']." (".$srow['status_id'].")";?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('ClosedStatus');?>
				</td>
				<td>
					<select name="closestatus">
						<?php
						foreach($statuses as $srow)
						{
							?>
							<option value="<?php echo $srow['id'];?>" <?php if($this->row->closestatus==$srow['id']) echo " selected";?> ><?php echo $srow['sname']." (".$srow['status_id'].")";?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('DefaultDepartment');?>
				</td>
				<td>
					<select name="defaultdepartment">
						<?php
						//get list of departments
						$query = 'SELECT * FROM #__huruhelpdesk_departments';
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						$department = $db->loadAssocList();
						foreach($department as $drow)
						{
							?>
							<option value="<?php echo $drow['department_id'];?>" <?php if($this->row->defaultdepartment==$drow['department_id']) echo " selected";?> ><?php echo $drow['dname']." (".$drow['department_id'].")";?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('DefaultCategory');?>
				</td>
				<td>
					<select name="defaultcategory">
						<?php
						//get list of departments
						$query = 'SELECT * FROM #__huruhelpdesk_categories';
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						$categories = $db->loadAssocList();
						foreach($categories as $crow)
						{
							?>
							<option value="<?php echo $crow['category_id'];?>" <?php if($this->row->defaultcategory==$crow['category_id']) echo " selected";?> ><?php echo $crow['cname']." (".$crow['category_id'].")";?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('DefaultLanguage');?>
				</td>
				<td>
					<select name="defaultlang">
						<?php
						//get list of languages
						$query = 'SELECT * FROM #__huruhelpdesk_language';
						$db =& JFactory::getDBO();
						$db->setQuery($query);

						foreach($db->loadAssocList() as $lrow)
						{
							?>
							<option value="<?php echo $lrow['id'];?>" <?php if($this->row->defaultlang==$lrow['id']) echo " selected";?> ><?php echo $lrow['langname'];?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>


			<tr>
				<td class="configsectionhead" colspan="2"><?php echo lang('Permissions');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('AllowAnonymousCases');?>
				</td>
				<td>
					<input type="checkbox" name="allowanonymous" id="allowanonymous" value="1" <?php if($this->row->allowanonymous == 1) echo " checked";?> />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('AllowUserSelectOnNewCases');?>
				</td>
				<td>
					<select name="userselect">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->userselect==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->userselect==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->userselect==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->userselect==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->userselect==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
<!--					<input type="checkbox" name="userselect" id="userselect" value="1" <?php if($this->row->userselect == 1) echo " checked";?> />-->
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('KnowledgebaseViewAuthority');?>
				</td>
				<td> 
					<select name="enablekb">
						<option value="<?php echo KB_LEVEL_DISABLE ?>" <?php if($this->row->enablekb==KB_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo KB_LEVEL_REP ?>" <?php if($this->row->enablekb==KB_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo KB_LEVEL_USER ?>" <?php if($this->row->enablekb==KB_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo KB_LEVEL_ALL ?>" <?php if($this->row->enablekb==KB_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('department');?>
				</td>
				<td> 
					<select name="set_department">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_department==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_department==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_department==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_department==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_department==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('location');?>
				</td>
				<td> 
					<select name="set_location">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_location==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_location==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_location==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_location==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_location==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('phone');?>
				</td>
				<td> 
					<select name="set_phone">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_phone==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_phone==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_phone==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_phone==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_phone==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('category');?>
				</td>
				<td> 
					<select name="set_category">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_category==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_category==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_category==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_category==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_category==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('status');?>
				</td>
				<td> 
					<select name="set_status">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_status==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_status==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_status==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_status==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_status==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('priority');?>
				</td>
				<td> 
					<select name="set_priority">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_priority==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_priority==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_priority==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_priority==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_priority==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('rep');?>
				</td>
				<td> 
					<select name="set_rep">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_rep==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_rep==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_rep==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_rep==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_rep==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Set').' '.lang('timespent');?>
				</td>
				<td> 
					<select name="set_timespent">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->set_timespent==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->set_timespent==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->set_timespent==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->set_timespent==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->set_timespent==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			

			<tr>
				<td class="configsectionhead" colspan="2"><?php echo lang('DisplayedFields');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('department').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_department">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_department==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_department==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_department==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_department==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_department==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('location').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_location">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_location==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_location==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_location==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_location==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_location==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('phone').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_phone">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_phone==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_phone==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_phone==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_phone==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_phone==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('category').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_category">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_category==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_category==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_category==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_category==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_category==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('status').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_status">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_status==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_status==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_status==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_status==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_status==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('priority').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_priority">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_priority==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_priority==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_priority==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_priority==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_priority==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
					<?php echo lang('IfNotSetable');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('rep').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_rep">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_rep==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_rep==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_rep==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_rep==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_rep==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('Show').' '.lang('timespent').' '.lang('To');?>
				</td>
				<td> 
					<select name="show_timespent">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->show_timespent==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->show_timespent==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->show_timespent==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->show_timespent==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->show_timespent==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>

			
			<tr>
				<td class="configsectionhead" colspan="2"><?php echo lang('FileAttachments');?></td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('AllowFileAttachments');?>
				</td>
				<td>
					<select name="fileattach_allow">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->fileattach_allow==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->fileattach_allow==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->fileattach_allow==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->fileattach_allow==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->fileattach_allow==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('AllowedAttachmentExtensions');?>
				</td>
				<td>
					<input class="text_area" type="text" name="fileattach_allowedextensions" id="fileattach_allowedextensions" size="50" maxlength="50" value="<?php echo $this->row->fileattach_allowedextensions;?>" /> <?php echo lang('ExtensionExample');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('MaximumAttachmentSize');?>
				</td>
				<td>
					<input class="text_area" type="text" name="fileattach_maxsize" id="fileattach_maxsize" size="10" maxlength="8" value="<?php echo $this->row->fileattach_maxsize;?>" /> (<?php echo lang('Bytes').')&nbsp;&nbsp;&nbsp;*'.lang('AttachmentSizeWarning');?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('AttachmentDownloadPermissions');?>
				</td>
				<td>
					<select name="fileattach_download">
						<option value="<?php echo SEC_LEVEL_DISABLE ?>" <?php if($this->row->fileattach_download==SEC_LEVEL_DISABLE) echo " selected";?> ><?php echo lang('Disable');?></option>
						<option value="<?php echo SEC_LEVEL_ADMIN ?>" <?php if($this->row->fileattach_download==SEC_LEVEL_ADMIN) echo " selected";?> ><?php echo lang('Admin');?></option>
						<option value="<?php echo SEC_LEVEL_REP ?>" <?php if($this->row->fileattach_download==SEC_LEVEL_REP) echo " selected";?> ><?php echo lang('RepsOnly');?></option>
						<option value="<?php echo SEC_LEVEL_USER ?>" <?php if($this->row->fileattach_download==SEC_LEVEL_USER) echo " selected";?> ><?php echo lang('UsersAndReps');?></option>
						<option value="<?php echo SEC_LEVEL_ALL ?>" <?php if($this->row->fileattach_download==SEC_LEVEL_ALL) echo " selected";?> ><?php echo lang('Anyone');?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('MaximumAttachmentAge');?>
				</td>
				<td>
					<input class="text_area" type="text" name="fileattach_maxage" id="fileattach_maxage" size="10" maxlength="8" value="<?php echo $this->row->fileattach_maxage;?>" /> (<?php echo lang('days').')&nbsp;&nbsp;&nbsp;'.lang('SetToZero');?>
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
