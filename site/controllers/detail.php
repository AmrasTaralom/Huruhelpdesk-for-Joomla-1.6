<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

//check user auth level
require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';
require_once JPATH_COMPONENT.DS.'helpers'.DS.'auth.php';
$mainframe = &JFactory::getApplication();
if(!checkusermin('user') && !config('allowanonymous')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
jimport('joomla.application.component.controller');

class HuruHelpdeskControllerDetail extends JController
{
	function deleteattachment()
	{
		$option = JRequest::getCmd('option');
		$mainframe = &JFactory::getApplication();
		
		//get page variables for use on redirect
		$id = JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW);
		$type = JRequest::getVar('type','','post','string',JREQUEST_ALLOWRAW);
		$itemid = JRequest::getVar('Itemid','','post','int',JREQUEST_ALLOWRAW);
		
		//check authorization (admin only)
		if(!checkusermin('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH')); //make sure user trying to delete is an admin
		
		//get attachment id from post
		$attachment_id = JRequest::getVar('attachment_id','','post','int',JREQUEST_ALLOWRAW);
		if(DEBUG) $mainframe->enqueueMessage($attachment_id);
		
		//delete attachment
		$query = 'DELETE FROM #__huruhelpdesk_attachments WHERE id='.$attachment_id;
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$result = $db->query($query);
		
		//if not successful, return to case and send failure message
		if(!$result) $this->setRedirect('index.php?option=' . $option . '&view=detail&cid[]='.$id.'&Itemid='.$itemid.'&type='.$type, lang('AttachmentNotDeleted'));

		//if successful, return to case and send success message
		$this->setRedirect('index.php?option=' . $option . '&view=detail&cid[]='.$id.'&Itemid='.$itemid.'&type='.$type, lang('AttachmentDeleted'));
	}
	
	function reopen()
	{
		$mainframe = &JFactory::getApplication();
		
		if(!checkusermin('admin')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH')); //make sure user trying to reopen is an admin

		//find default status
		JRequest::setVar('status', config('defaultstatus')); //set the status var to the default
		
		$this->save(); //call the standard save function
	}
	
	function save()
	{
		JRequest::checkToken() or jexit('Invalid Token');
	
		$option = JRequest::getCmd('option');
		$mainframe = &JFactory::getApplication();
		
		$userlvl = userlevel();
		
		$ok = true; //default condition for data validation
		$changed = false; //default - was case data changed?

		$row =& JTable::getInstance('Detail', 'Table');
		
		//get page variables for use on redirect
		$id = JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW);
		$type = JRequest::getVar('type','','post','string',JREQUEST_ALLOWRAW);
		$itemid = JRequest::getVar('Itemid','','post','int',JREQUEST_ALLOWRAW);
		
		if (!$row->bind(JRequest::get('post')))
		{
			JError::raiseError(500, $row->getError());
		}
		
		if(empty($id)) $newproblem = true;
		else $newproblem = false;
		
		//var_dump($newproblem);die();
		
		//get old record classification data so we can create notes when changes occur
		if(!$newproblem)
		{
			$query = 'SELECT rep, status, category, department, priority, uid, uemail, ulocation, uphone FROM #__huruhelpdesk_problems WHERE id='.$id;
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$old = $db->loadRow();
			$oldrep = $old[0];
			$oldstatus = $old[1];
			$oldcategory = $old[2];
			$olddepartment = $old[3];
			$oldpriority = $old[4];
			$olduid = $old[5];
			$olduemail = $old[6];
			$oldulocation = $old[7];
			$olduphone = $old[8];
		}
		
		/* Security checks*/
		//if the existing case was closed, make sure no one is trying to save to it unless it is just to reopen it
		if(!$newproblem && closed($oldstatus) && JRequest::getVar('task') != 'reopen') $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
		//make sure the user submitting the data has the right to save the case
		if(!$newproblem && userlevel() < USER_LEVEL_USER && !caseauthor($olduid, $olduemail, safe(JRequest::getVar('chk','','','string',JREQUEST_ALLOWRAW)))) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

		//get record data from submitted form
		//id, uid, entered_by, and start_date must be set for new cases and are not editable on old cases
		//for new case, id will get set automatically by database
		$newproblem = JRequest::getVar('newproblem','','post','int',JREQUEST_ALLOWRAW);
		if($newproblem == 1)
		{
			$row->id = ''; //for new case, id will get set automatically by database
			$row->entered_by = currentuserinfo('huru_id');
			$row->start_date = date('c');
		}
		
		//if we are dealing with a new problem AND the userselect var is set, then we fill the user info from the 
		//database, not from the form fields
		//only check post var if user has permission to set this field
		if($userlvl >= config('userselect')) $userselect = safe(JRequest::getVar('userselect','','post','int',JREQUEST_ALLOWRAW));
		if($newproblem == 1 && strlen($userselect)>0 && $userselect != -1 )
		{
			$row->uid = userinfo($userselect,'username');
			$row->uemail = userinfo($userselect,'email');
			$row->ulocation = userinfo($userselect,'location1');
			$row->uphone = userinfo($userselect,'phone');
			$row->department = userinfo($userselect,'department');

			//set the vars so we can use them later in notes
			$uid = $row->uid;
			$uemail = $row->uemail;
			$ulocation = $row->ulocation;
			$uphone = $row->uphone;
			$udepartment = $row->department;
		}
		else
		{
			//only set rows in record that were set on form
			//only check post var if user has permission to set this field
			$uid = safe(JRequest::getVar('uid','','post','string',JREQUEST_ALLOWRAW));
			if(strlen($uid)>0) $row->uid = $uid;
			
			$uemail = safe(JRequest::getVar('uemail','','post','string',JREQUEST_ALLOWRAW));
			if(strlen($uemail)>0) $row->uemail = $uemail;
			
			if($userlvl >= config('set_location')) $ulocation = safe(JRequest::getVar('ulocation','','post','string',JREQUEST_ALLOWRAW));
			if(strlen($ulocation)>0) $row->ulocation = $ulocation;
			
			if($userlvl >= config('set_phone')) $uphone = safe(JRequest::getVar('uphone','','post','string',JREQUEST_ALLOWRAW));
			if(strlen($uphone)>0) $row->uphone = $uphone;
			
			if($userlvl >= config('set_department')) $department = safe(JRequest::getVar('department','','post','int',JREQUEST_ALLOWRAW));
			if(strlen($department)<=0 || $department == -1)
			{
				if($newproblem)
				{
					$row->department = config('defaultdepartment');
				}
			}
			else $row->department = $department;
		}
		
		//if category is not set in the form see if we're dealing with a new problem or one that has been reopened.  
		//If so, default the value don't default the value of an existing case so as to avoid overwriting what 
		//is already defined in case
		//only check post var if user has permission to set this field
		if($userlvl >= config('set_category')) $category = safe(JRequest::getVar('category','','post','int',JREQUEST_ALLOWRAW));
		if(strlen($category)<=0 || $category == -1) 
		{
			if($newproblem)
			{
				$row->category = config('defaultcategory');
			}
		}
		else $row->category = $category; //If it was set in form, set it in record

		//if status is not set in the form see if we're dealing with a new problem or one that has been reopened.  
		//If so, default the value don't default the value of an existing case so as to avoid overwriting what 
		//is already defined in case
		//only check post var if user has permission to set this field
		if($userlvl >= config('set_status')) $status = safe(JRequest::getVar('status','','post','int',JREQUEST_ALLOWRAW));
		if(strlen($status)<=0)
		{
			if($newproblem)
			{
				$row->status = config('defaultstatus');
			}
		}
		else $row->status = $status; //If it was set in form, set it in record
		
		//if priority is not set in the form see if we're dealing with a new problem.  If so, default the value
		//don't default the value of an existing case so as to avoid overwriting what is already defined in case
		//only check post var if user has permission to set this field
		if($userlvl >= config('set_priority')) $priority = safe(JRequest::getVar('priority','','post','int',JREQUEST_ALLOWRAW));
		if(strlen($priority)<=0)
		{
			if($newproblem)
			{
				$row->priority = config('defaultpriority');
			}
		}
		else $row->priority = $priority; //If it was set in form, set it in record
		
		//if rep is not set, then set it by default based on category (if we are dealing with a new problem)
		//don't default the value of an existing case so as to avoid overwriting what is already defined in case
		//only check post var if user has permission to set this field
		if($userlvl >= config('set_rep')) $rep = safe(JRequest::getVar('rep','','post','int',JREQUEST_ALLOWRAW));
		if($rep == -1 || strlen($rep)<=0)
		{
			if($newproblem)
			{
				//get default rep for category
				$query = 'SELECT rep_id FROM #__huruhelpdesk_categories WHERE category_id='.$row->category;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$defaultrep = $db->loadRow();
				$row->rep = $defaultrep[0];
			}
		}
		else $row->rep = $rep; //If it was set in form, set it in record
		
		//close_date is only set if the case status is closed
		//get closed status
		$query = 'SELECT s.id AS sid FROM #__huruhelpdesk_config AS c JOIN #__huruhelpdesk_status AS s ON s.id=c.closestatus';
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$closed = $db->loadRow();
		if($closed[0] == $row->status)
		{
			$row->close_date = date('c');
			$caseclosing = true;
		}
		else 
		{
			$row->close_date = ''; //blank out the date in case we are reopening a case
			$caseclosing = false;
		}

		//get the rest of the form variables
		//check to see if they were set in form before binding to database record
		//only check post var if user has permission to set this field
		if($userlvl >= config('set_timespent')) $timespent = safe(JRequest::getVar('time_spent','','post','int',JREQUEST_ALLOWRAW));
		if(strlen($timespent)>0) $row->time_spent = $timespent;
		
		$title = safe(JRequest::getVar('title','','post','string',JREQUEST_ALLOWRAW));
		if(strlen($title)>0) $row->title = $title;
		
		$desc = JRequest::getVar('description','','post','string',JREQUEST_ALLOWRAW);
		if(strlen($desc)>0) $row->description = $desc;
		
		//only check post var if user has permission to set this field - only reps or admins can enter a solution
		if($userlvl >= USER_LEVEL_REP) $solution = JRequest::getVar('solution','','post','string',JREQUEST_ALLOWRAW);
		if(strlen($solution)>0) $row->solution = $solution;
		
		//only check post var if user has permission to set this field - only reps or admins can send case to knowledgebase
		if($userlvl >= USER_LEVEL_REP) $kb = safe(JRequest::getVar('kb','','post','int',JREQUEST_ALLOWRAW));
		if(strlen($kb)>0) $row->kb = $kb;

		//if everything is okay, then store the record, otherwise error out
		if($ok)
		{
			if(!$row->store())
			{
				JError::raiseError(500, $row->getError());
			}
		}
		else 
		{
			$this->setRedirect('index.php?option=' . $option . '&view=cpanel&Itemid='.$itemid, lang('ErrorSavingProblem'),'error');
			return false;
		}
		
		//create case note if case classification info changed - we don't do this for new problems
		if(!$newproblem)
		{
			$notesrow =& JTable::getInstance('Notes', 'Table');

			//get username of current user for use in notes
			$user =& JFactory::getUser();
			$uname = $user->username;
			
			if($olduid != $uid && strlen($uid)>0) //the username/uid isn't changeable on detail form, but we put it here for completeness
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Username').': '.$olduid.' --> '.$uid;
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($olduemail != $uemail && strlen($uemail)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('EMail').': '.$olduemail.' --> '.$uemail;
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($olddepartment != $department && strlen($department)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Department').': ';

				$query = 'SELECT dname FROM #__huruhelpdesk_departments WHERE department_id='.$olddepartment;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$dept = $db->loadRow();
				$olddeptname = $dept[0];

				$query = 'SELECT dname FROM #__huruhelpdesk_departments WHERE department_id='.$row->department;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$dept = $db->loadRow();
				$newdeptname = $dept[0];

				$notestr = $notestr.$olddeptname.' --> '.$newdeptname;
				
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($oldulocation != $ulocation && strlen($ulocation)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Location').': '.$oldulocation.' --> '.$ulocation;
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($olduphone != $uphone && strlen($uphone)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Phone').': '.$olduphone.' --> '.$uphone;
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($oldcategory != $category && strlen($category)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Category').': ';

				$query = 'SELECT cname FROM #__huruhelpdesk_categories WHERE category_id='.$oldcategory;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$cat = $db->loadRow();
				$oldcatname = $cat[0];

				$query = 'SELECT cname FROM #__huruhelpdesk_categories WHERE category_id='.$row->category;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$cat = $db->loadRow();
				$newcatname = $cat[0];

				$notestr = $notestr.$oldcatname.' --> '.$newcatname;
				
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($oldstatus != $status && strlen($status)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Status').': ';

				$query = 'SELECT sname FROM #__huruhelpdesk_status WHERE id='.$oldstatus;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$stat = $db->loadRow();
				$oldstatname = $stat[0];

				$query = 'SELECT sname FROM #__huruhelpdesk_status WHERE id='.$row->status;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$stat = $db->loadRow();
				$newstatname = $stat[0];

				$notestr = $notestr.$oldstatname.' --> '.$newstatname;
				
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($oldpriority != $priority && strlen($priority)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('Priority').': ';

				$query = 'SELECT pname FROM #__huruhelpdesk_priority WHERE priority_id='.$oldpriority;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$pri = $db->loadRow();
				$oldpriname = $pri[0];

				$query = 'SELECT pname FROM #__huruhelpdesk_priority WHERE priority_id='.$row->priority;
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$pri = $db->loadRow();
				$newpriname = $pri[0];

				$notestr = $notestr.$oldpriname.' --> '.$newpriname;
				
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
			if($oldrep != $rep && strlen($rep)>0)
			{
				$notesrow->note_id = "";
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
				$notesrow->adddate = date("Y/m/d H:i:s");
				$notesrow->priv = 0;
				$notesrow->ip = $_SERVER['REMOTE_ADDR'];
				$notesrow->uid = $uname;
				
				$notestr = lang('AssignedTo').': '.userinfo($oldrep,'username').' --> '.userinfo($row->rep,'username');
				$notesrow->note = $notestr;

				if($notesrow->store())
				{
					$changed = true;
				}
				else
				{
					JError::raiseError(500, $notesrow->getError());
				}
			}
		}

		//create note that case was created - only if case is new, obviously
		if($newproblem)
		{
			$notesrow =& JTable::getInstance('Notes', 'Table');

			//if this is a new case, then we don't actually know the case id yet so 
			//we need to try and find out what it is so we can record the note
			$query = "SELECT id FROM #__huruhelpdesk_problems WHERE uid = '".$row->uid."' AND DATE_FORMAT(start_date,'%Y-%m-%d-%H-%i-%s')='".date('Y-m-d-H-i-s',strtotime($row->start_date))."' AND title='".$row->title."'";
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$case = $db->loadRow();
			$notesrow->id = $case[0];

			//get username of current user for use in notes
			$user =& JFactory::getUser();
			$uname = $user->username;
			
			$notesrow->note_id = "";
			$notesrow->adddate = date("Y/m/d H:i:s");
			$notesrow->priv = 1;
			$notesrow->ip = $_SERVER['REMOTE_ADDR'];
			$notesrow->uid = $uname;

			$notestr = lang('ProblemCreated');

			$notesrow->note = $notestr;

			if($notesrow->store())
			{
				//$changed = true;  //don't need this for new cases since notifications go out anyway if configured
			}
			else
			{
				JError::raiseError(500, $notesrow->getError());
			}
		}
		
		//get data from added case note if available and save it to the notes table
		$newnote = JRequest::getVar('newnote','','post','string',JREQUEST_ALLOWRAW);
		
		//get base info about any attachment that was uploaded with the note
		$filename = $_FILES['userfile']['name']; // Get the name of the file
		$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
		$filesize = $_FILES['userfile']['size']; //Get the size of the file
		$filetype = $_FILES['userfile']['type']; //Get the mime type of the file
		$tmpname  = $_FILES['userfile']['tmp_name']; //Get the temp file name used by server to store uploaded file

		//check here to see if user wanted to upload file but left the note text blank
		//if so, set $newnote to default text so that the note and attachment will be saved.  
		//Only do this if we are allowed to upload
		if(userlevel() >= config('fileattach_allow') && strlen($filename)>0 && $filesize > 0 && strlen(trim($newnote)) == 0)
		{
			//only do this if the file conforms to type and size restrictions
			if(attachment_file_ok($filename, $ext, $filesize, $filetype, $tmpname, TRUE))
			{
				$newnote = lang('DefaultFileAttachmentNote');
			}
		}

		if(strlen(trim($newnote))>0)
		{
			$notesrow =& JTable::getInstance('Notes', 'Table');
			
			//check to see what the 'private note' value is set to and default it if necessary
			$privatenote = safe(JRequest::getVar('privatenote','','post','int',JREQUEST_ALLOWRAW));
			if(!$privatenote) $privatenote = 0;
			
			//if this is a new case, then we don't actually know the case id yet so 
			//we need to try and find out what it is so we can record the note
			if($newproblem)
			{
				$query = "SELECT id FROM #__huruhelpdesk_problems WHERE uid = '".$row->uid."' AND DATE_FORMAT(start_date,'%Y-%m-%d-%H-%i-%s')='".date('Y-m-d-H-i-s',strtotime($row->start_date))."' AND title='".$row->title."'";
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$case = $db->loadRow();
				$notesrow->id = $case[0];
			}
			else
			{
				$notesrow->id = safe(JRequest::getVar('id','','post','int',JREQUEST_ALLOWRAW));
			}

			$notesrow->note_id = ""; //auto increment set by database
			$notesrow->note = $newnote;
			$notesrow->adddate = date("Y/m/d H:i:s");
			$notesrow->priv = $privatenote;

			//get username of current user
			$notesrow->uid = currentuserinfo('username');
			
			//get the client ip address
			$notesrow->ip = $_SERVER['REMOTE_ADDR'];

			//we'll need these later for attachments
			$note_case_id = $notesrow->id;
			$note_add_date = $notesrow->adddate;
			$note_ip = $notesrow->ip;
			
			if($notesrow->store()) //store the note
			{
				if(!$privatenote) $changed = true; //don't send a notificaiton if its just for the addition of a private note
			}
			else
			{
				JError::raiseError(500, $notesrow->getError());
			}
				
		
		
		
			/************************************************************************************
			//Upload attachment to note if sent by user
			*************************************************************************************/		
			//first check to make sure we are allowed to upload and that we tried to upload something
			if(userlevel() >= config('fileattach_allow') && strlen($filename)>0 && $filesize >  0)
			{
				//we have to find the note id of the note created above
				$query = "SELECT note_id FROM #__huruhelpdesk_notes WHERE id = ".$note_case_id." AND note = '".$newnote."' AND adddate = '".$note_add_date."' AND ip = '".$note_ip."'";
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$note_id = $db->loadResult();
				
				//check the file against the allowed extensions and maximum filesize
				//if everything checks out, then call the appropriate uploader for
				//what type of storage we are using: 1=db or 2=filesystem (anything else bogus)
				if(attachment_file_ok($filename, $ext, $filesize, $filetype, $tmpname, TRUE))
				{
					if(config('fileattach_type')==1)
					{
						$fp = fopen($tmpname, 'r');
						$content = fread($fp, filesize($tmpname));
						$content = addslashes($content);
						fclose($fp);

						if(!get_magic_quotes_gpc())
						{
							$filename = addslashes($filename);
						}
						
						$query = "INSERT INTO #__huruhelpdesk_attachments (note_id, name, size, type, content ) VALUES (".$note_id.",'".$filename."', '".$filesize."', '".$filetype."', '".$content."')";
						$db =& JFactory::getDBO();
						$db->setQuery($query);
						$uploadresult = $db->query();
						if($uploadresult)
						{
							$changed = true;
						}
						else
						{
							$mainframe->enqueueMessage(lang('ErrorSavingAttachment'));
							if(DEBUG) JError::raiseError(500, lang('ErrorSavingAttachment').' - '.lang('UnknownError'), 'notice');
						}
					}
					elseif(config('fileattach_type')==2)
					{
						//This is where we put the code to move the uploaded temp file to the configured folders if we
						//want to use filesystem storage of attachments
						//not yet implemented
						$mainframe->enqueueMessage(lang('ErrorSavingAttachment').' - '.lang('NotImplemented'), 'notice');
						if(DEBUG) JError::raiseError(500, lang('ErrorSavingAttachment').' - '.lang('NotImplemented'));
					}
					else
					{
						$mainframe->enqueueMessage(lang('ErrorSavingAttachment').' - '.lang('NotImplemented'), 'notice');
						if(DEBUG) JError::raiseError(500, lang('ErrorSavingAttachment').' - '.lang('NotImplemented'));
					}
				}
			}
		}

		//get the case details for messages
		$query = "SELECT id, title, description, uid, uemail, uphone, ulocation, department, priority, category, start_date, rep, solution, entered_by FROM #__huruhelpdesk_problems ";
		//if its a new case, then we dont have an index number to work with, so we have to get it another way
		if($newproblem) $query = $query." WHERE uid = '".$row->uid."' AND DATE_FORMAT(start_date,'%Y-%m-%d-%H-%i-%s')='".date('Y-m-d-H-i-s',strtotime($row->start_date))."' AND title='".$row->title."'";
		else $query = $query." WHERE id = ".$id; 
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$case = $db->loadRow();
		
		//get rep details - have to use case info from above query in case the rep was not set on the form
		$query = "SELECT ju.email as email, ju.name as name, hh.pageraddress FROM #__users AS ju JOIN #__huruhelpdesk_users AS hh ON ju.id = hh.joomla_id WHERE hh.id=".$case[11]." ORDER BY ju.username";
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$rep = $db->loadRow();
		
		if($newproblem && !$caseclosing) //we always notify user and rep if the case is new unless it was entered as closed
		{
			hh_sendmail('usernew',$case[4],$case);
			hh_sendmail('repnew',$rep[0],$case);

			$adminaddress = config('notifyadminonnewcases');
			if(strlen($adminaddress)>0)
			{
				if(DEBUG) $mainframe->enqueueMessage('Sending to admin '.$adminaddress);
				hh_sendmail('adminnew',$adminaddress,$case);
			}
		}

		//on update, only notify user if config is set to do so
		//only notify if case was actually changed
		//don't notify user of change if the case is new since they were already notified
		if(config('notifyuser') == 1 && !$newproblem && $changed)
		{
			if($caseclosing)  //if case is closing, then send close message
			{
				hh_sendmail('userclose',$case[4],$case);
			}
			else
			{
				hh_sendmail('userupdate',$case[4],$case); //otherwise send update message
			}
		}
		
		//notify rep of updates & close unless the rep is the one doing the changes
		//only notify if case was actually changed
		if(currentuserinfo('huru_id') != $row->rep && $changed)
		{
			if($caseclosing)
			{
				hh_sendmail('repclose',$rep[0],$case);
			}
			else
			{
				//don't send rep an 'update' message for a new case because they are already getting the creation message above
				if(!$newproblem) hh_sendmail('repupdate',$rep[0],$case);
			}
		}
		
		//if the priority is pager, send a message to the rep pager address (if there is one)
		//only notify if case was actually changed
		if(config('pagerpriority') == $case[8] && strlen($rep[2])>0 && $changed)
		{
			hh_sendmail('reppager',$rep[2],$case);
		}
	
		//if the user is not anonymous or this is an existing case, send them back to the case.  If they are anonymous entering a new case, send them to the control panel
		//var_dump($row->id);die('here');
		$itemid = $row->id;
		if(JRequest::getVar('anon','','post','int',JREQUEST_ALLOWRAW) != 1) $this->setRedirect('index.php?option=' . $option . '&view=detail&cid[]='.$id.'&Itemid='.$itemid.'&type='.$type, lang('ProblemSaved'));
		else $this->setRedirect('index.php?option=' . $option . '&view=cpanel&Itemid='.$itemid, lang('ProblemSaved'));
	}
	
	function edit()
	{
		$mainframe = &JFactory::getApplication();
		
		JRequest::setVar('view','detail');
		JRequest::setVar('task','');
		JRequest::setVar('type',$mainframe->getUserStateFromRequest('hh_list.type','type',''));
		parent::display();
	}
	
	function results()
	{
		//call up the list screen controller
		require_once(JPATH_COMPONENT.DS.'controllers'.DS.'list.php');
	}

	function cancel()
	{
		//reset the parameters
		JRequest::setVar('task', '');
		JRequest::setVar('view', 'list');

		//call up the list screen controller
		require_once(JPATH_COMPONENT.DS.'controllers'.DS.'list.php');
	}
	
}
	
$controller = new HuruHelpdeskControllerDetail();
$controller->execute($task);
$controller->redirect();
