<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Import library dependencies
jimport('joomla.event.plugin');
 
class plgUserHuruHelpdesk_User_Sync extends JPlugin
{
   /**
    * Constructor
    *
    * For php4 compatability we must not use the __constructor as a constructor for
    * plugins because func_get_args ( void ) returns a copy of all passed arguments
    * NOT references.  This causes problems with cross-referencing necessary for the
    * observer design pattern.
    */
	function onUserAfterSave($data, $isnew, $success, $msg)
	{
		//if this is a new user and was stored successfully, then import it into Huru
		if($isnew && $success)
		{
			//get default language
			$query = 'SELECT defaultlang FROM #__huruhelpdesk_config';
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$lang = $db->loadRow();

			//check plugin parameters to see if we should default things
			$isUser = $this->params->get('isUser');
			
			//import new Joomla user into Huru user table
			$query = "INSERT INTO #__huruhelpdesk_users (joomla_id, language, isuser) VALUES (".JArrayHelper::getValue($data, 'id', 0, 'int').", ".$lang[0].", ".$isUser.")";
			$db->setQuery($query);
			$db->query();
		}
		
		return true;
	}

	function onUserAfterDelete($user, $success, $msg)
	{
		//if user was deleted from Joomla successfully, then delete it from Huru
		if($success)
		{
			$query = "DELETE FROM #__huruhelpdesk_users WHERE joomla_id = ".$user['id'];
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$db->query();
		}
		
		return true;	
	}
}
?>