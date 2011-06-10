<?php 
defined('_JEXEC') or die('Restricted access');
define("DEBUG",false,true);

function com_install()
{
	//always try to add the version column just in case we are upgrading a really old version
	//If it's not there and we try to query it, the install may fail.  If it's already there
	//and we try to add it anyway, no harm.
	add_column_to_table('#__huruhelpdesk_config','version','TEXT NOT NULL');

	
	/////////////////
	// 0.86 ->0.87 //
	/////////////////
	
	//check to see if version is older than the functions that we need to do for an upgrade
	if(older_than('0.87 beta'))
	{
		if(DEBUG) echo "Upgrading database to v0.87<br>";

		//set the version number
		set_column_value('#__huruhelpdesk_config','version','0.87 beta');

		//add columns to tables
		add_column_to_table('#__huruhelpdesk_notes','ip','TEXT NULL AFTER `uid`');
	}


	/////////////////
	// 0.87 ->0.88 //
	/////////////////
	
	//check to see if version is lower than 0.88 beta so that we don't overwrite values
	if(older_than('0.88 beta'))
	{
		if(DEBUG) echo "Upgrading database to v0.88<br>";

		//set the version number
		set_column_value('#__huruhelpdesk_config','version','0.88 beta');

		//add columns to tables
		add_column_to_table('#__huruhelpdesk_config','show_username','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_email','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_department','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_location','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_phone','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_category','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_status','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_priority','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_rep','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','show_timespent','int(11) NOT NULL');

		add_column_to_table('#__huruhelpdesk_config','set_username','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_email','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_department','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_location','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_phone','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_category','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_status','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_priority','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_rep','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','set_timespent','int(11) NOT NULL');

		add_column_to_table('#__huruhelpdesk_config','hdnotifyname','TEXT NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','defaultdepartment','bigint(20) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','defaultcategory','bigint(20) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','defaultrep','bigint(20) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_allow','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_allowedextensions','TEXT NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_allowedmimetypes','TEXT NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_maxsize','bigint(20) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_type','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_path','TEXT NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_download','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','fileattach_maxage','int(11) NOT NULL');
		add_column_to_table('#__huruhelpdesk_config','notifyadminonnewcases','TEXT NOT NULL');
		
		//set values in new columns as appropriate
		set_column_value('#__huruhelpdesk_config','show_username','0');
		set_column_value('#__huruhelpdesk_config','show_email','0');
		set_column_value('#__huruhelpdesk_config','show_department','0');
		set_column_value('#__huruhelpdesk_config','show_location','0');
		set_column_value('#__huruhelpdesk_config','show_phone','0');
		set_column_value('#__huruhelpdesk_config','show_category','0');
		set_column_value('#__huruhelpdesk_config','show_status','0');
		set_column_value('#__huruhelpdesk_config','show_priority','0');
		set_column_value('#__huruhelpdesk_config','show_rep','0');
		set_column_value('#__huruhelpdesk_config','show_timespent','0');

		set_column_value('#__huruhelpdesk_config','set_username','0');
		set_column_value('#__huruhelpdesk_config','set_email','0');
		set_column_value('#__huruhelpdesk_config','set_department','0');
		set_column_value('#__huruhelpdesk_config','set_location','0');
		set_column_value('#__huruhelpdesk_config','set_phone','0');
		set_column_value('#__huruhelpdesk_config','set_category','0');
		set_column_value('#__huruhelpdesk_config','set_status','50');
		set_column_value('#__huruhelpdesk_config','set_priority','50');
		set_column_value('#__huruhelpdesk_config','set_rep','50');
		set_column_value('#__huruhelpdesk_config','set_timespent','50');

		set_column_value('#__huruhelpdesk_config','hdnotifyname','Huru Helpdesk');
		set_column_value('#__huruhelpdesk_config','defaultdepartment','-1');
		set_column_value('#__huruhelpdesk_config','defaultcategory','-1');
		set_column_value('#__huruhelpdesk_config','defaultrep','-1');
		set_column_value('#__huruhelpdesk_config','fileattach_allow','10000');
		set_column_value('#__huruhelpdesk_config','fileattach_allowedextensions','.jpg,.png');
		set_column_value('#__huruhelpdesk_config','fileattach_allowedmimetypes','image/jpeg,image/png');
		set_column_value('#__huruhelpdesk_config','fileattach_maxsize','1048576');
		set_column_value('#__huruhelpdesk_config','fileattach_type','1');
		set_column_value('#__huruhelpdesk_config','fileattach_path','');
		set_column_value('#__huruhelpdesk_config','fileattach_download','0');
		set_column_value('#__huruhelpdesk_config','fileattach_maxage','0');
		set_column_value('#__huruhelpdesk_config','notifyadminonnewcases','');
		
		//if field value meaning has changed, update the data to reflect the new
		//if userselect was enabled (1) then default new value to SEC_LEVEL_ALL.  if userselect was disabled (0) set value to SEC_LEVEL_DISABLE
		$db =& JFactory::getDBO();
		$query = "SELECT userselect FROM #__huruhelpdesk_config";
		$db->setQuery($query);
		$current = $db->loadRow();
		$query = "UPDATE #__huruhelpdesk_config SET userselect = ";
		if($current[0] == 1) $query = $query."0"; //SEC_LEVEL_ALL = 0
		else $query = $query."10000"; //SEC_LEVEL_DISABLE = 10000
		$db->setQuery($query);
		$new = $db->query();
		
		//add adminnew notice to email messages
		$db =& JFactory::getDBO();
		$query = "INSERT INTO #__huruhelpdesk_emailmsg (id, type, subject, body) VALUES (NULL, 'adminnew', 'HELPDESK: Problem [problemid] Created', 'The following problem has been created.  You can update the problem at [url]\r\n\r\nPROBLEM DETAILS\r\n---------------\r\nID: [problemid]\r\nDate: [startdate]\r\nTitle: [title]\r\nPriority: [priority]\r\nCategory: [category]\r\n\r\nUSER INFORMATION\r\n----------------\r\nFullname: [fullname]\r\nUsername: [uid]\r\nEmail: [uemail]\r\nPhone: [phone]\r\nLocation: [location]\r\nDepartment: [department]\r\n\r\nDESCRIPTION\r\n-----------\r\n[description]')";

		$db->setQuery($query);
		$new = $db->query();

	}
}

function older_than($reqversion)
{
	//find current version from databse
	$db =& JFactory::getDBO();
	$query = "SELECT version FROM #__huruhelpdesk_config";
	$db->setQuery($query);
	$currentversion = $db->loadRow();

	//if the current version is blank, then we have a really old version and we definitely need to upgrade
	if(strlen($currentversion[0])<=0) return true;
	
	//if we got a returned version, check it against the version in the request and see if it is older
	//make each version a float so we can do a numerical comparison
	$currentversion_float = floatval($currentversion[0]);
	$reqversion_float = floatval($reqversion);
	
	//if either version is NOT beta, then add a bit to it make it better than the beta version with the same version number
	if(!stripos($currentversion[0],'beta')) $currentversion_float = $currentversion_float + 0.000001;
	if(!stripos($reqversion,'beta')) $reqversion_float = $reqversion_float + 0.000001;

	//if the requested version is newer than the old version, return true	
	if($reqversion_float > $currentversion_float) return true;
	
	return false; //default
}

function add_column_to_table($table,$column,$params)
{
	$db =& JFactory::getDBO();
	
	//we just try to add the column here without checking to see if column exists first
	//because it's easier and php does not error out if the column already exists (unlike the sql install script)
	$query = "ALTER TABLE `".$table."` ADD `".$column."` ".$params;
	$db->setQuery($query);
	$dbupdateresult = $db->query();
	if(DEBUG)
	{
		if($dbupdateresult) echo "Successfully added '".$column."' column to table '".$table."'.<br>";
		else "Error adding '".$column."' column to table '".$table."'.<br>";
	}
}

function set_column_value($table, $column, $value)
{
	$db =& JFactory::getDBO();
	$query = "UPDATE ".$table." SET ".$column." = ".$db->Quote($value);
	$db->setQuery($query);
	$dbupdateresult = $db->query();
}