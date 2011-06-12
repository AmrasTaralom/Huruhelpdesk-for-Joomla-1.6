<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die('Restricted Access');

/*
Checks to see if the current user is authorized to view the case they are trying to open
They are authorized if they are a user and entered the case, or if they are anonymous, anonymous is allowed,
and they entered an email address as a verification check
*/
function caseauthor($caseuid, $caseemail, $verification)
{
	//check to see if the user is the person who created the case
	if($caseuid == currentuserinfo('username')) return true;
	
	//check email address entry to see if an anonymous user entered email address to view their case
	//this should only work for anonymous users
	if($caseemail == $verification && strlen($verification)>0 && userlevel()==USER_LEVEL_NONE) return true;
	
	return false; //default condition
}

/*
Checks the authority level of the currently logged in user against the requeste authority level.
Returns true if user authority MATCHES the requested authority. false if not.
See checkusermin() function to find user authority equal to or greater than that requested
*/
function checkuser($usertype)
{

	$user =& JFactory::getUser();
	$uid = $user->id;
	$db =& JFactory::getDBO();
	$query = 'SELECT group_id FROM #__user_usergroup_map WHERE user_id='.$uid;
	$db->setQuery($query);
	$res = $db->loadRow();
	
	//usergroup 8 = super admin in 1.6
	if(!defined('JOOMLAADMIN')) define('JOOMLAADMIN', 8);
	
	switch($usertype)
	{
		case 'admin':
			//check to see if the user is a Joomla Super Admin - who is always a Huru admin
			if($res[0] == JOOMLAADMIN) return true;
			
			//check to see if the user is defined as a Huru admin in our user table
			$query = 'SELECT isadmin FROM #__huruhelpdesk_users WHERE joomla_id='.$uid;
			$db =& JFactory::getDBO();
			$db->setQuery($query);

			$isadmin = $db->loadRow();

			//the returned value will be either 1 (meaning the user is an admin), 0 (meaning the user is not an admin),
			//or null, meaning the user is not defined in the huru users table - and is therefore not an admin
			if(isset($isadmin[0]) && $isadmin[0] > 0) return true;  //##my201004071507. Remove warning. It was: if($isadmin[0] > 0) return true; 
			
			return false; //default action
			break;
			
		case 'reports':
			
			//check to see if the user is a Joomla Super Admin - who is always a Huru admin
			if($res[0] == JOOMLAADMIN) return true;
			
			//check to see if the user is defined as being allowed to view reports in our user table
			$query = 'SELECT viewreports FROM #__huruhelpdesk_users WHERE joomla_id='.$uid;
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			
			$viewreports = $db->loadRow();
			
			//the returned value will be either 1 (meaning the user can viewreports), 0 (meaning the user can not view reports),
			//or null, meaning the user is not defined in the huru users table
			if($viewreports[0] > 0) return true; 
			
			return false; //default action
			break;
			
		case 'rep':
			
			//check to see if the user is a Joomla Super Admin - who is always a Huru admin
			if($res[0] == JOOMLAADMIN) return true;
			
			//check to see if the user is defined as a rep in our user table
			$query = 'SELECT isrep FROM #__huruhelpdesk_users WHERE joomla_id='.$uid;
			$db =& JFactory::getDBO();
			$db->setQuery($query);

			$isrep = $db->loadRow();

			//the returned value will be either 1 (meaning the user is a rep), 0 (meaning the user is not a rep),
			//or null, meaning the user is not defined in the huru users table
			if(isset($isrep[0])  && $isrep[0] > 0) return true; //##my201004071507. Remove warning. It was: if($isrep[0] > 0) return true; 
			
			return false; //default action
			break;
			
		case 'user':
			
			//check to see if the user is a Joomla Super Admin - who is always a Huru admin
			if($res[0] == JOOMLAADMIN) return true;
			
			//check to see if the user is defined as a Huru user in our user table
			$query = 'SELECT isuser FROM #__huruhelpdesk_users WHERE joomla_id='.$uid;
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			
			$isuser = $db->loadRow();
			
			//the returned value will be either 1 (meaning the user is a user), 0 (meaning the user is not a user),
			//or null, meaning the user is not defined in the huru users table
			if(isset($isuser[0]) && $isuser[0] > 0) return true; //##my201004071507. Remove warning. It was: if($isuser[0] > 0) return true; 
			
			return false; //default action
			break;
			
		default:
			return false;
			break;
	}
}

/*
Checks to see if the currently logged in user has AT LEAST the requested authority level.
Returns true if user had equal or greater authority than requested. false if not.
See checkuser() function to find user authority EQUAL TO that requested
*/
function checkusermin($usertype)
{
	switch($usertype)
	{
		case 'admin':
			return checkuser('admin');
			break;
		case 'rep':
			if(checkuser('rep') || checkuser('admin')) return true;
			else return false;
			break;
		case 'user':
			if(checkuser('user') || checkuser('rep') || checkuser('admin')) return true;
			else return false;
			break;
		default:
			return false;
			break;
	}
	
}

/*
Returns the precise authority level of the currently logged in user
*/
function userlevel()
{
	require_once JPATH_COMPONENT.DS.'helpers'.DS.'head.php';

	if(checkuser('admin')) return USER_LEVEL_ADMIN;
	if(checkuser('rep')) return USER_LEVEL_REP;
	if(checkuser('user')) return USER_LEVEL_USER;
	
	return USER_LEVEL_NONE;
}

/*
Determines if the currently opened case is editable or not based upon 
the users authority and the status of the case
*/
function editable($userlvl, $status)
{
	//if case is closed, it is not editable by anyone
	if(closed($status)) return false;
	
	//admins and reps can edit all fields in all non-closed cases
	if($userlvl == USER_LEVEL_ADMIN || $userlvl == USER_LEVEL_REP) return true;
		
	return false; //default action
}

/*
Determines if the currently viewed case is closed or not based upon 
the status and the closed status in the config
*/
function closed($status)
{
	if(config('closestatus') == $status) return true;
	return false; //default action
}

/*
Determines if the currently opened case was submitted by the current user
*/
function submitted($by)
{
	//get username of current user
	$user =& JFactory::getUser();
	$uid = $user->id;

	//get the huru id of the current user
	$query = 'SELECT id FROM #__huruhelpdesk_users WHERE joomla_id='.$uid;
	$db =& JFactory::getDBO();
	$db->setQuery($query);

	$currentuser = $db->loadRow();
	//##my201004080258 {added to hide warning
	if (empty ($currentuser)) {
		$currentuser = null;
	}
	//##my201004080258 }

	if($currentuser[0] == $by) return true; 
	
	return false; //default action
}

