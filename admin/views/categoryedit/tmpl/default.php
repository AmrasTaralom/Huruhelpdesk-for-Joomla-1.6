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
		<legend><?php echo lang('EditCategory');?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo lang('Id');?>
				</td>
				<td>
					<?php if($this->row->category_id >0) echo $this->row->category_id;?>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('CategoryName');?>
				</td>
				<td>
					<input class="text_area" type="text" name="cname" id="cname" size="50" maxlength="50" value="<?php echo $this->row->cname;?>" />
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo lang('DefaultRep');?>
				</td>
				<td>
					<select name="rep_id">
						<?php
						//get list of reps
						$query = "SELECT * FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id	WHERE hh.isrep=1 ORDER BY ju.name";
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						foreach($db->loadAssocList() as $rrow)
						{
							?>
							<option value="<?php echo $rrow['id'];?>" <?php if($this->row->rep_id==$rrow['id']) echo " selected";?> ><?php echo $rrow['name'];?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="category_id" value="<?php echo $this->row->category_id;?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
