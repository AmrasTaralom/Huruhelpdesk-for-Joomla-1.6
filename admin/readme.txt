Readme.txt for Huru Helpdesk v0.88d
October 2010

------------------------------------------------------------------------------
In this Readme file
------------------------------------------------------------------------------
- Installation
- Note about File Upload Feature
- Requirements
- Huru Plug-in/Module Compatibility
- Language Files
- Known Issues
- Changes


------------------------------------------------------------------------------
Installation
------------------------------------------------------------------------------
Version 0.88 should install over the top of any older versions 
without any data loss. The installation scripts will make several 
changes to existing Huru tables, but will preserve any existing data, 
including users, cases and custom language strings.

*** Always backup you Joomla site and Huru tables to be sure ***

Mods:
Any modifications made to the Huru source files on your site will be lost 
upon installing the new version.  If you wish to maintain these 
modifications, you must backup your modified files, and reapply the 
mods after installation of the new version.  Before reapplying mods,
you may wish to check to see if one of the newly added features 
provides the functionality you need.


------------------------------------------------------------------------------
Note about File Upload feature
------------------------------------------------------------------------------
As of Huru Helpdesk version 0.88, users can upload and attach files to 
case notes. In order for the file upload feature to function, PHP must be 
configured to allow http file uploads.  Check the php.ini file for the
parameter "file_uploads".

Huru's General Configuration allows the administrator to set the maximum size
of attachments.  Huru has a hard limit of 16MB per attachment when storing 
attachments in the MySQL database (they are stored as an MBLOB). PHP, however,
may be configured for less.  To verify of change the maximum file size allowed 
by PHP, check the php.ini file for the parameter "upload_max_filesize".

Finally, uploading large files can take considerable time if the user's 
commection to the server is over a slow link.  This could potentially cause 
the scripts to time-out.  If this occurs, you can increase the maximum
script execution time in the php.ini file by setting the
"max_execution_time" parameter.


------------------------------------------------------------------------------
Requirements
------------------------------------------------------------------------------
- MySQL version 4.0 or higher
- Joomla version 1.5.x
- PHP version 5.2 or higher


------------------------------------------------------------------------------
Huru Plug-in/Module Compatibility:
------------------------------------------------------------------------------
Huru Helpdesk v0.88 is compatible with Huru UserSync plug-in version 0.8x.yy.


------------------------------------------------------------------------------
Language files
------------------------------------------------------------------------------
For any of you that have created alternative language files, there several 
additional strings created for the new version, so you may want to add those.


------------------------------------------------------------------------------
Known Issues
------------------------------------------------------------------------------
- Various problems with PHP 4
	- time stamps
	- email validation error
	
- A ReFirewall conflict was reported that was caused by the reuse of function 
  names between front and admin side helper scripts. All helper files 
  have now been merged, so function names are no longer repeated.  
  Given this change, the conflict needs to be retested.

- Huru has not been tested with LDAP or other authentications

- There is a reported possible conflict with Community Builder's 
  login/authentication

  
------------------------------------------------------------------------------
Changes
------------------------------------------------------------------------------
v0.88c->0.88d
New: Added code in detail view to support Joomla site search plugin

v0.88->0.88c
Fixed: Invalid id passed through URL to detail view could be used for SQL injection
Fixed: Links on Admin side to Huru Helpdesk site, docs and forums
Fixed: Issue with language string selection function always returning primary language
Several fixes related to uninitialized variables causing problems with JoomFish (many thanks to Gruz for his work on the problems!)
Fixed: Undefined constant error for user on cpanel when kb authority was set to reps only (again, thanks Gruz)
Fixed: Problem uploading attachments with more than one period in filename
Fixed: Huru button CSS affecting non-Huru modules
Fixed: Input fields on detail form not showing quoted strings correctly
Fixed: View all problems for "no limit" days wasn't showing closed as it should
Fixed: View all problems for X days was displaying "All open" text instead of just "All"
Fixed: Update notifications sent out even if no case data changed
Fixed: Admin not set for report viewing sees Activity button but is denied access


v0.87->v0.88
Fixed: View Problem was still displayed on user control panel for anonymous users even with anonymous cases not allowed
Fixed: Undefined Property on General Configuration screen when PHP error reporting was set to E_ALL in php.ini
Fixed: Undefined Variable errors on various screens when PHP error reporting was set to E_ALL in php.ini
Fixed: Undefined Constant errors on various screens when PHP error reporting was set to E_ALL in php.iniFixed: Notification message reply address now pulled correctly from config
Fixed: Enter in Knowledgebase check box state now set correctly when opening existing case
Fixed: Problem classification and contact data should not be displayed when problem viewed via knowledgebase search
New: Users can now upload and attach files to case notes
New: Admins can delete attachments from individual case notes
New: System can be configured to automatically purge old attachments
New: Anonymous user can now enter notes on cases
New: IP address of note author now only visible to reps and admins
New: Notification message sender name now configurable instead of using page title
New: Merged front and back-end helper files for clarity and maintainability
New: Availability of User Select box can now be set by user level instead of globally on/off
New: Anonymous users can search the knowledgebase
New: [fullname] field added to notification message substitution options (pulls name from Joomla account)
New: Can now configure an email address	to notify for all new support tickets
New: Individual problem fields now visible and setable based on user type
New: Username, case id, and priority no longer shown on knowledgebase results list
New: Assigned rep now shown on list view (when not viewing own assigned problems)
New: Latest case mod date on shown list screen
		
v0.86->v0.87
Fixed: Report gave divide by zero warnings if no cases in given date range had time assigned to them
Fixed: Report not finding problems created on date given as search criteria end date
Fixed: Multiple notes created simultaneously don't show up in logical order (contact & classification changes should show up prior to user notes)
Fixed: Contact and classification change notes now utilize language strings
Fixed: Search criteria weren't being stored correctly between screens
New: Added pagination and filtering to admin screens where neccessary	
New: Added ability to print open ticket list and activity report
New: Default private note added on case creation (facilitates other features)
New: Client IP address recorded in case notes
New: Admins can now delete problems via control panel option
New: Changes to case contact info now recorded in notes
New: Language string search function now shows error instead of blank if no matching record found
	
v0.85->0.86
Fixed: User-level user not able to create case. Errored out with "Fatal error: Call to a member function redirect() on a non-object in .../components/com_huruhelpdesk/controllers/detail.php on line 70" 
Fixed: Problems created as closed were still sending out opened and assigned notifications. 
Fixed: Problem list being displayed instead of control panel when Home button clicked in certain situations 
Fixed: Search not finding problems created on date given as search criteria end date 
Fixed: Formatting problem with non-editable solutions text 
Fixed: Removed Joomla! page title from printout of case detail 
Fixed: All front-end views shown as available as a menu item type - should only allow CPanel 
Fixed: Escape character being stored in database as part of escaped string 
New: Re-ordered configuration links in Admin control panel to reflect typical initial configuration order 
New: Added links on Admin control panel to SourceForge home, wiki and support pages 
New: Deletion of the closed status not allowed 
New: Added label to clarify that user select overrides manually entered contact information on detail page 
New: Changed new problem form to default contact info and not default user select list (for clarity)

