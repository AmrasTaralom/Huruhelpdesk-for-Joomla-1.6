<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');

//check user auth level
$mainframe = &JFactory::getApplication();
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
if(!checkusermin('user') && !config('allowanonymous')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

//get user auth level
$userlvl = userlevel();

//check to see if we are dealing with a new problem or an existing problem
if($this->row->id > 0) $newproblem = false;
else $newproblem = true;

//if user is a user or anonymous, then they can't just view any case unless its a knowledgebase case from a kb search
if(!(	config('enablekb') > KB_LEVEL_DISABLE /*if kb is enabled*/ 
		&& $this->row->kb==1 /*and this is a kb case*/ 
		&& ($mainframe->getUserState('hh_list.stype','stype','')=='kb' ||  JRequest::getVar('sitesearch')==1) /*and we're coming from a kb search or a site search*/
	) 
	&& 	!$newproblem /*it's not a new problem*/
	&& 	$userlvl <= USER_LEVEL_USER /*the user is a user or anonymous*/
	&& 	!caseauthor($this->row->uid, $this->row->uemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW))) /*the user is not the case author*/
	) echo $mainframe->redirect('index.php?option='.JRequest::getCmd('option').'&Itemid='.JRequest::getVar('Itemid'), JText::_(lang('NotFound')));

//determine if form can be edited based on user level and case status
$editable = editable($userlvl, $this->row->status);

//determine if we are in a print view
if(JRequest::getVar('print')==1) $printing = true;
else $printing = false;

//if we are coming from a site search, then we need to set the stype user state var 
//(if it's not already set) so that the print function works correctly
//we want to make sure we do this after the security check above.
if(JRequest::getVar('sitesearch')==1)
{
	$stype = $mainframe->getUserStateFromRequest('hh_list.stype','stype','');
	if(!$stype)
	{
		$mainframe->setUserState( 'hh_list.stype', 'kb' ); //if it wasn't set before, set it now
	}
}

?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/validation.js"></script>
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/problem.js"></script>
	<?php if($newproblem && !empty($this->catdefaults)){ ?>
	<script type="text/javascript">
		function catdefault(id) {
			
			var Catdefault = new Array();
			<?php
				
				foreach($this->catdefaults as $def){
					echo 'Catdefault['.$def[1].'] = '.json_encode($def[2]).';';
				}
				
			?>
			if(Catdefault[id]){
				document.getElementById('descriptiontext').innerHTML = Catdefault[id];
			}else{
				document.getElementById('descriptiontext').innerHTML = "";
			}
		}
	</script>
	<?php } ?>
</head>
<?php
//if its a new problem, find the default status and priority
if($newproblem)
{
	$query = 'SELECT defaultpriority, defaultstatus FROM #__huruhelpdesk_config';
	$db =& JFactory::getDBO();
	$db->setQuery($query);
	$crow = $db->loadRow();
	$defaultpriority = $crow[0];	
	$defaultstatus = $crow[1];	
	
	$defaultuser = currentuserinfo('huru_id'); //this will default the user select box - not sure if we want to do this due to user confusion
}

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

//list type we are coming from
$type = $mainframe->getUserStateFromRequest('hh_list.type','type','');

//setup toolbar
//if we're in a print view, just give us the print buttons
if($printing) toolbar('printout','closeprint'); 
//if we are viewing the case via a site search, use custom buttons
elseif(JRequest::getVar('sitesearch')==1) toolbar('print','closetohome','refresh','home');
//if the case is closed (not editable) and we are admin, let us reopen it
elseif(!$editable && $userlvl == USER_LEVEL_ADMIN) toolbar('reopen','print','close','refresh','home','assigned'); 
//print additional "assigned" for admin
elseif($userlvl == USER_LEVEL_ADMIN &&(!closed($this->row->status) && ($editable || $newproblem || caseauthor($this->row->uid, $this->row->uemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW)))))) toolbar('saveproblem','print','close','refresh','home','assigned');
//normal case
elseif(!closed($this->row->status) && ($editable || $newproblem || caseauthor($this->row->uid, $this->row->uemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW))))) toolbar('saveproblem','print','close','refresh','home');
//otherwise, just give us the ability to print
else toolbar('print','close','refresh','home');

?>
<form action="?option=<?php echo JRequest::getCmd('option');?>" method="post" name="problem_form" id="problem_form" enctype="multipart/form-data">
	<table class="problemdetail">
		
		
		<?php
		if($mainframe->getUserState('hh_list.stype','stype','')!='kb')
		{
			?>
			<tr>
				<td colspan="2" class="problemhead">
					<?php
					if($newproblem) echo lang('NewProblem');
					else echo lang('ProblemNumber').$this->row->id;
					?>
				</td>
			</tr>
			<?php
			if($editable && !$printing)
			{
				?>
				<tr>
					<td colspan="2" align="right" class="problemdetail"><font color="red">*</font> = <?php echo lang('Required');?></td>
				</tr>
				<?php 
			}
			?>
		
			<tr>
				<td class="problemfieldname problemcolumnhead">
				<?php
				if($userlvl >= config('show_username') || $userlvl >= config('show_email') || $userlvl >= config('show_department') || $userlvl >= config('show_location') || $userlvl >= config('show_phone') || $userlvl >= config('userselect'))
				{
					echo lang('ContactInformation');
				}
				?>
				</td>
				
				<td class="problemfieldname problemcolumnhead">
				<?php
				//don't show the header if all the fields under it are hidden
				if($userlvl >= config('show_category') || $userlvl >= config('show_status') || $userlvl >= config('show_priority') || $userlvl >= config('show_rep') || $userlvl >= config('show_timespent'))
				{
					echo lang('Classification');
				}
				?>
				</td>
			</tr>
			
			<tr>
				<td class="problemdetail">
					<table>
						<?php
						if($userlvl >= config('show_username'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('UserName');?>: </td>
								<?php 
								if($userlvl >= config('set_username') && $newproblem && !$printing)
								{
									?>
									<td>
										<input type="text" class="detail" name="uid" id="uid" size="25" maxlength="255" value="<?php echo currentuserinfo('username');?>" /><font color="red">*</font>
									</td>
									<?php 
								}
								else
								{
									?>
									<td>
										<?php 
										echo $this->row->uid . ' (J! ID: ' . $this->joomla_id . ')';
										?>
										<input type="hidden" class="detail" name="uid" id="uid" value="<?php echo $this->row->uid;?>" />
									</td>
									<?php 
								}
								?>
							</tr>
							<?php
						}

						if($userlvl >= config('show_email'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Email');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_email') && $newproblem && !$printing)
									{
										?>
										<input type="text" class="detail" name="uemail" id="uemail" size="25" maxlength="255" value="<?php echo currentuserinfo('email');?>" /><font color="red">*</font>
										<?php 
									}
									elseif($userlvl >= config('set_email') && $editable && !$printing)
									{
										?>
										<input type="text" class="detail" name="uemail" id="uemail" size="25" maxlength="255" value="<?php echo $this->row->uemail;?>" /><font color="red">*</font>
										<?php 
									}
									else echo $this->row->uemail;
									?>
								</td>
							</tr>
							<?php
						}

						if($userlvl >= config('show_department'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Department');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_department') && $newproblem && !$printing)
									{
										?>
										<select class="detail" name="department" id="department">
											<option class="detail" value="-1"><?php echo lang('SelectDepartment');?></option>
											<?php
											//get list of departments
											$query = "SELECT * FROM #__huruhelpdesk_departments ORDER BY dname";
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											foreach($db->loadAssocList() as $drow)
											{
												?>
												<option class="detail" value="<?php echo $drow['department_id'];?>" <?php if(currentuserinfo('department')==$drow['department_id']) echo " selected";?> ><?php echo $drow['dname'];?></option>
												<?php
											}
											?>
										</select><font color="red">*</font>
										<?php 
									}
									elseif($userlvl >= config('set_department') && $editable && !$printing)
									{
										?>
										<select class="detail" name="department" id="department">
											<?php
											if($newproblem)
											{
												?>
												<option class="detail" value="-1"><?php echo lang('SelectDepartment');?></option>
												<?php
											}
											//get list of departments
											$query = "SELECT * FROM #__huruhelpdesk_departments ORDER BY dname";
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											foreach($db->loadAssocList() as $drow)
											{
												?>
												<option class="detail" value="<?php echo $drow['department_id'];?>" <?php if($this->row->department==$drow['department_id']) echo " selected";?> ><?php echo $drow['dname'];?></option>
												<?php
											}
											?>
										</select>
										<?php 
									}
									else
									{
										//get departments
										
										if($this->row->department){
											$query = "SELECT * FROM #__huruhelpdesk_departments WHERE department_id=".$this->row->department;
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											$department = $db->loadRow();
										}
										//##my201004071556 {added to hide warning
										if (!isset ($department[1]) ) {
											$department[1] = null;
										}
										//##my201004071556 }
										echo $department[1];
									}
									?>
								</td>
							</tr>
							<?php
						}

						if($userlvl >= config('show_location'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Location');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_location') && $newproblem && !$printing)
									{
										?>
										<input class="detail" type="text" name="ulocation" id="ulocation" size="25" maxlength="255" value="<?php echo htmlspecialchars(currentuserinfo('location1'));?>" />
										<?php 
									}
									elseif($userlvl >= config('set_location') && $editable && !$printing)
									{
										?>
										<input class="detail" type="text" name="ulocation" id="ulocation" size="25" maxlength="255" value="<?php echo htmlspecialchars($this->row->ulocation);?>" />
										<?php 
									}
									else echo $this->row->ulocation;
									?>
								</td>
							</tr>
							<?php
						}

						if($userlvl >= config('show_phone'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Phone');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_phone') && $newproblem && !$printing)
									{
										?>
										<input class="detail" type="text" name="uphone" id="uphone" size="25" maxlength="255" value="<?php echo htmlspecialchars(currentuserinfo('phone'));?>" />
										<?php 
									}
									elseif($userlvl >= config('set_phone') && $editable && !$printing)
									{
										?>
										<input class="detail" type="text" name="uphone" id="uphone" size="25" maxlength="255" value="<?php echo htmlspecialchars($this->row->uphone);?>" />
										<?php 
									}
									else echo $this->row->uphone;
									?>
								</td>
							</tr>
							<?php
						}
						
						//if(($newproblem && $userlvl > USER_LEVEL_NONE && userselect()) && !$printing) //dont show the user select drop down for anonymous
						if(($newproblem && $userlvl >= config('userselect')) && !$printing) //dont show the user select drop down for anonymous
						{
							//if *any* of the user fields are shown, show the 'or' text - otherwise don't
							if($userlvl >= config('show_username') || $userlvl >= config('show_email') || $userlvl >= config('show_department') || $userlvl >= config('show_location') || $userlvl >= config('show_phone'))
							{
								?>
								<tr>
									<td>
									</td>
									<td class="problemfieldname">
										--- <?php echo lang('Or');?> ---
									</td>
								</tr>
								<?php
							}
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('SelectUser');?>: </td>
								<td>
									<select class="detail" name="userselect" id="userselect">
										<option class="detail" value="-1"><?php echo lang('SelectUser');?></option>
										<?php
										//get list of users
										$query = "SELECT ju.username as username, ju.name as name, hh.id as id FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id ORDER BY ju.username";
										$db =& JFactory::getDBO();
										$db->setQuery($query);
										foreach($db->loadAssocList() as $urow)
										{
											?>
											<option class="detail" value="<?php echo $urow['id'];?>"><?php echo $urow['username'].' ('.$urow['name'].')';?></option>
											<?php
										}
										?>
									</select><font color="red">*</font><font size="1"> 
									<?php
									//if *any* of the user fields are shown, show the 'override' text - otherwise don't
									if(config('show_username') || config('show_email') || config('show_department') || config('show_location') || config('show_phone'))
									{
										echo lang('SelectOverride');
									}
									?>
									</font>
								</td>
							</tr>
							<?php 
						}

						if(!$newproblem && !$printing)
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('EnteredBy');?>: </td>
								<td>
									<?php 
									//get user
									if($this->row->entered_by){
										$query = "SELECT ju.name FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id WHERE hh.id = ".$this->row->entered_by;
										$db =& JFactory::getDBO();
										$db->setQuery($query);
										$by = $db->loadRow();
									}
									//##my201004071556 {added to hide warning
									if (!isset ($by[0]) ) {
										$by[0] = null;
									}
									//##my201004071556 }
									echo $by[0];
									?>
								</td>
							</tr>
							<?php 
						}
						?>
					</table>
				</td>
				
				<td class="problemdetail">
					<table>
						<?php
						if($userlvl >= config('show_category'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Category');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_category') && ($editable || $newproblem) && !$printing)
									{
										?>
										<select class="detail" name="category" id="category" <?php if($newproblem && !empty($this->catdefaults)){ ?>onChange="javascript:catdefault(this.options[this.selectedIndex].value);"  <?php } ?>>
											<?php
											if($newproblem)
											{
												?>
												<option value="-1"><?php echo lang('SelectCategory');?></option>
												<?php
											}
											//get list of categories
											$query = "SELECT * FROM #__huruhelpdesk_categories ORDER BY cname";
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											foreach($db->loadAssocList() as $crow)
											{
												?>
												<option class="detail" value="<?php echo $crow['category_id'];?>" <?php if($this->row->category==$crow['category_id']) echo " selected";?> ><?php echo $crow['cname'];?></option>
												<?php
											}
											?>
										</select>
										<?php 
									}
									else
									{
										if($this->row->category){
											$query = "SELECT cname FROM #__huruhelpdesk_categories WHERE category_id=".$this->row->category;
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											$category = $db->loadRow();
										}
										//##my201004071556 {added to hide warning
										if (!isset ($category[0]) ) {
											$category[0] = null;
										}
										//##my201004071556 }
										echo $category[0];
									}							
									?>
								</td>
							</tr>
							<?php 
						}

						if($userlvl >= config('show_status'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Status');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_status') && ($editable || $newproblem) && !$printing)
									{
										?>
										<select class="detail" name="status" id="status">
											<?php
											//get list of statuses
											$query = "SELECT * FROM #__huruhelpdesk_status ORDER BY status_id";
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											foreach($db->loadAssocList() as $srow)
											{
												?>
												<option class="detail" value="<?php echo $srow['id'];?>"<?php /*##my201004080356. Fix incorrect array. It was: <option value="<?php echo $srow[id];?>"  */?>
												<?php if($this->row->status==$srow['id'] || ($newproblem && $defaultstatus==$srow['id'])) echo " selected";?> 
												>
												<?php echo $srow['sname'];?></option>
												<?php
											}
											?>
										</select>
										<?php 
									}
									else
									{
										//##my201004071556 {added to hide warning
										if (!isset ($this->row->status) ) {
											$this->row->status = 0;
										}
										//##my201004071556 }
										$query = "SELECT sname FROM #__huruhelpdesk_status WHERE id=".$this->row->status;
										$db =& JFactory::getDBO();
										$db->setQuery($query);
										$status = $db->loadRow();
										//##my201004071556 {added to hide warning
										if (!isset ($status[0] )) {
											$status[0] = null;
										}
										//##my201004071556 }
										echo $status[0];
									}							
									?>
								</td>
							</tr>
							<?php
						}
						
						if($userlvl >= config('show_priority'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('Priority');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_priority') && ($editable || $newproblem) && !$printing)
									{
										?>
										<select class="detail" name="priority" id="priority">
											<?php
											//get list of priorities
											$query = "SELECT * FROM #__huruhelpdesk_priority ORDER BY priority_id";
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											foreach($db->loadAssocList() as $prow)
											{
												?>
												<option class="detail" value="<?php echo $prow['priority_id'];?>" 
												<?php if($this->row->priority==$prow['priority_id'] || ($newproblem && $defaultpriority==$prow['priority_id'])) echo " selected";?> 
												>
												<?php echo $prow['pname'];?></option>
												<?php
											}
											?>
										</select>
										<?php 
									}
									else
									{
										//##my201004071556 {added to hide warning
										if (!isset ($this->row->priority) ) {
											$this->row->priority = 0;
										}
										//##my201004071556 }
										$query = "SELECT pname FROM #__huruhelpdesk_priority WHERE priority_id=".$this->row->priority;
										$db =& JFactory::getDBO();
										$db->setQuery($query);
										$priority = $db->loadRow();
										//##my201004071556 {added to hide warning
										if (!isset ($priority[0]) ) {
											$priority[0] = null;
										}
										echo $priority[0];
									}							
									?>
								</td>
							</tr>
							<?php
						}
						
						if($userlvl >= config('show_rep'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('AssignedTo');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_rep') && ($editable || $newproblem) && !$printing)
									{
										?>
										<select class="detail" name="rep" id="rep">
											<?php
											if($newproblem)
											{
												?>
												<option class="detail" value="-1"><?php echo lang('DefaultAssignment');?></option>
												<?php
											}
											//get list of reps
											$query = "SELECT * FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id	WHERE hh.isrep=1 ORDER BY ju.name";
											$db =& JFactory::getDBO();
											$db->setQuery($query);
											foreach($db->loadAssocList() as $rrow)
											{
												?>
												<option class="detail" value="<?php echo $rrow['id'];?>" <?php if($this->row->rep==$rrow['id']) echo " selected";?> ><?php echo $rrow['name'];?></option>
												<?php
											}
											?>
										</select>
										<?php 
									}
									else
									{
										//##my201004071556 {added to hide warning
										if (!isset ($this->row->rep) ) {
											$this->row->rep = 0;
										}
										//##my201004071556 }
										$query = "SELECT ju.name FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id WHERE hh.id=".$this->row->rep;
										$db =& JFactory::getDBO();
										$db->setQuery($query);
										$rep = $db->loadRow();
										//##my201004071556 {added to hide warning
										if (!isset ($rep[0]) ) {
											$rep[0] = null;
										}
										//##my201004071556 }
										echo $rep[0];
									}							
									?>
								</td>
							</tr>
							<?php
						}
						
						if($userlvl >= config('show_timespent'))
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('TimeSpent');?>: </td>
								<td>
									<?php 
									if($userlvl >= config('set_timespent') && ($editable || $newproblem) && !$printing)
									{
										?>
										<input class="detail" type="text" name="time_spent" id="time_spent" size="15" maxlength="255" value="<?php echo htmlspecialchars($this->row->time_spent);?>" /><?php echo ' ('.lang('minutes').')';?>
										<?php 
									}
									else echo $this->row->time_spent.' ('.lang('minutes').')';
									?>
								</td>
							</tr>
							<?php 
						}

						if(!$newproblem) //don't show dates for new cases
						{
							?>
							<tr>
								<td class="problemfieldname"><?php echo lang('StartDate');?>: </td>
								<td>
									<?php echo date('D, j M Y  g:i A',strtotime($this->row->start_date));?>
								</td>
							</tr>
							<tr>
								<td class="problemfieldname"><?php echo lang('CloseDate');?>: </td>
								<td>
									<?php if($this->row->close_date >= $this->row->start_date) echo date('D, j M Y  g:i A',strtotime($this->row->close_date));?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
			<?php 
		}
		?>
		
		<tr>
			<td colspan="2" class="problemhead"><?php echo lang('ProblemInformation');?></td>
		</tr>
		
		<tr>
			<td colspan="2" class="">
				<span class="problemfieldname"><?php echo lang('Title');?>:</span> 
				<?php 
				if(($editable || $newproblem) && !$printing)
				{
					?>
					<input class="detail" type="text" name="title" id="title" size="100" maxlength="255" value="<?php echo htmlspecialchars($this->row->title);?>" />
					<?php 
				}
				else echo $this->row->title;
				?>
			</td>
		</tr>

		<tr>
			<td colspan="2" class="problemfieldname"><?php echo lang('Description');?>: </td>
		</tr>
		<tr>
			<td colspan="2" class="">
				<?php 
				if(($editable || $newproblem) && !$printing)
				{
					?>
					<textarea id="descriptiontext" name="description" cols="85" rows="8" class="problemtext"><?php echo $this->row->description;?></textarea>
					<?php 
				}
				else echo formatROText($this->row->description);
				?>
			</td>
		</tr>

		<?php 
//		//don't show the notes & solution for new problems unless the user is a rep or above
//		//allowing it for reps & admins lets them quick enter cases that may be closed as they are entered.
//		if(!$newproblem || $userlvl >= USER_LEVEL_REP) 
		if(true) 
		{
		?>
			<tr>
				<td colspan="2" class="problemhead"><?php echo lang('Notes');?></td>
			</tr>

					<?php
					//get all the notes for the case
					if($this->row->id){
						$query = "SELECT * FROM #__huruhelpdesk_notes WHERE id = ".$this->row->id;
						if(!checkusermin('rep')) $query = $query." AND priv <> 1"; //keeps private notes from users - reps & admins can see all notes
						$query = $query." ORDER BY adddate ASC, note_id ASC";
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						foreach($db->loadAssocList() as $nrow)
						{
							?>
							<tr>
								<td colspan="2" class="problemnote">
									<div class="notetext"><b><?php echo $nrow['adddate'].' '.$nrow['uid']; if(checkusermin('rep')) echo ' - ['.$nrow['ip'].'] '; if($nrow['priv']==1) echo ' (Private)';?></b><br/ >
									<?php 
									if($userlvl >= config('fileattach_download'))
									{
										$attachment_id = get_attachment_id($nrow['note_id']);
										$attachment_name = get_attachment_name($attachment_id);
										$attachment_url = 'components/com_huruhelpdesk/helpers/download.php?id='.$attachment_id.'&name='.$attachment_name.'&note_id='.$nrow['note_id'];
										if(strlen($attachment_id) > 0) 
										{
											if(checkuser('admin')) echo '<span class="delete" name="delattach" id="delattach" onclick="deleteattachment('.$attachment_id.');">X</span>';
											echo '<b>'.lang('Attachment').':</b> <a href="'.$attachment_url.'">'.$attachment_name."</a>";
											echo '<br />';
										}
									}
									?>
									<?php echo formatROText($nrow['note']);?></div>
								</td>
							</tr>
							<?php 
						}
					}
			//all users are allowed to add notes to cases if they submitted the case, we are not closed, and we are not printing
			if($newproblem || ($editable || submitted($this->row->entered_by) || caseauthor($this->row->uid, $this->row->uemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW)))) && !$printing && !closed($this->row->status))
			{
				?>
				<tr>
					<div><td colspan="2" class="problemhead"><?php echo lang('EnterAdditionalNotes');?></td></div>
				</tr>
				<tr>
					<td colspan="2">
						<textarea name="newnote" id="newnotetext" cols="85" rows="8" class="problemtext"></textarea>
					</td>
				</tr>
				<tr>
					<?php
					//but only reps & admins can make notes private
					if($editable && !$printing)
					{
						?>
						<td>
								<input type="checkbox" name="privatenote" id="privatenote" value="1" />
								<?php echo lang('HideFromEndUser');?>
						</td>
						<?php 
					}
					else echo "<td>&nbsp;</td>";//this is here for formatting reasons
					
					//only show the file input if file upload is enabled for this user
					if(userlevel() >= config('fileattach_allow'))
					{
						?>
						<td>
							<span class="attachtext"><?php echo lang('AttachFileToNote');?>:&nbsp;<input type="file" name="userfile" id="file" class="fileupload"></span>
						</td>
						<?php
					}
					?>
				</tr>
				<?php 
			}
			?>

			<tr>
				<td colspan="2" class="problemhead"><?php echo lang('Solution');?></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php 
					if($editable && !$printing)
					{
						?>
						<textarea name="solution" id="solutiontext" cols="85" rows="8" class="problemtext"><?php echo $this->row->solution;?></textarea>
						<?php 
					}
					else echo formatROText($this->row->solution);
					?>
				</td>
			</tr>

			<?php 
			if($editable && !$printing)
			{
				?>
				<tr>
					<td>
						<input type="checkbox" name="kb" id="kb" value="1" <?php if($this->row->kb==1) echo 'checked';?> />
						<?php echo lang('EnterinKnowledgeBase');?>
					</td>
				</tr>
				<?php 
			}
		}
		?>
	</table>
	<input type="hidden" name="id" id="id" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="cid[]" id="cid[]" value="<?php echo $this->row->id; ?>" />
	<input type="hidden" name="option" id="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="task" id="task" value="save" /><!-- this is here to make up for bad IE behavior-->
	<input type="hidden" name="view" id="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="type" id="type" value="<?php echo JRequest::getVar('type',''); ?>" />
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo JRequest::getVar('Itemid',''); ?>" />
	<input type="hidden" name="chk" id="chkid" value="<?php echo JRequest::getVar('chk',''); ?>" />
	<input type="hidden" name="newproblem" id="newproblem" value="<?php echo $newproblem; ?>" />
	<input type="hidden" name="anon" id="anon" value="<?php if($userlvl == USER_LEVEL_NONE) echo '1'; ?>" />
	<input type="hidden" name="attachment_id" id="attachment_id" value="" />

	<?php echo JHTML::_('form.token'); ?>

</form>
<?php 
//setup toolbar
//if we're in a print view, just give us the print buttons
if($printing) toolbar('printout','closeprint'); 
//if we are viewing the case via a site search, use custom buttons
elseif(JRequest::getVar('sitesearch')==1) toolbar('print','closetohome','refresh','home');
//if the case is closed (not editable) and we are admin, let us reopen it
elseif(!$editable && $userlvl == USER_LEVEL_ADMIN) toolbar('reopen','print','close','refresh','home','assigned'); 
//print additional "assigned" for admin
elseif($userlvl == USER_LEVEL_ADMIN &&(!closed($this->row->status) && ($editable || $newproblem || caseauthor($this->row->uid, $this->row->uemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW)))))) toolbar('saveproblem','print','close','refresh','home','assigned');
//normal case
elseif(!closed($this->row->status) && ($editable || $newproblem || caseauthor($this->row->uid, $this->row->uemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW))))) toolbar('saveproblem','print','close','refresh','home');
//otherwise, just give us the ability to print
else toolbar('print','close','refresh','home');
?>

<?php if(DEBUG) dumpdebug();?>
