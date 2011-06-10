<?php
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
$mainframe = &JFactory::getApplication();
if(!checkusermin('user')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/validation.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/editinfo.js"></script>
</head>

<?php
//display page title if configured
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
<?php 

//setup toolbar
toolbar('saveprofile','refresh','close');
?>

<form action="?option=<?php echo JRequest::getCmd('option');?>&view=editinfo&task=save&Itemid=<?php echo JRequest::getVar('Itemid');?>" method="post" name="editInfoForm" id="editInfoForm">
	<table class="editInfoTable">
		<tr>
			<td colspan="2">
				<b><?php echo lang('JoomlaUserInfo');?></b>&nbsp;&nbsp;
				<input type="button" value="Edit..." class="cpanel" style="cursor:pointer;" onclick="window.location='?option=com_user&view=user&layout=form';" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('Name');?>
			</td>
			<td>
				<?php echo currentuserinfo('name');?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('UserName');?>
			</td>
			<td>
				<?php echo currentuserinfo('username');?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('Email');?>
			</td>
			<td>
				<?php echo currentuserinfo('email');?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br /><b><?php echo lang('HuruUserInfo');?></b>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('Phone');?>
			</td>
			<td>
				<input class="text_area" type="text" name="phone" id="phoneid" size="50" maxlength="50" value="<?php echo $this->row->phone;?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('HomePhone');?>
			</td>
			<td>
				<input class="text_area" type="text" name="phonehome" id="phonehomeid" size="50" maxlength="50" value="<?php echo $this->row->phonehome;?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('MobilePhone');?>
			</td>
			<td>
				<input class="text_area" type="text" name="phonemobile" id="phonemobileid" size="50" maxlength="50" value="<?php echo $this->row->phonemobile;?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('PagerAddress');?>
			</td>
			<td>
				<input class="text_area" type="text" name="pageraddress" id="pageraddressid" size="50" maxlength="100" value="<?php echo $this->row->pageraddress;?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('Location1');?>
			</td>
			<td>
				<input class="text_area" type="text" name="location1" id="location1id" size="50" maxlength="50" value="<?php echo $this->row->location1;?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('Location2');?>
			</td>
			<td>
				<input class="text_area" type="text" name="location2" id="location2id" size="50" maxlength="50" value="<?php echo $this->row->location2;?>" />
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<?php echo lang('Department');?>
			</td>
			<td>
				<select name="department" id="departmentid">
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
				<select name="language" id="languageid">
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
	</table>
	<?php echo JHTML::_('form.token'); ?>
</form>
<script language="javascript">displayMessage('<?php echo lang('UserProfile');?>');</script>
