<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'head.php'; //sends us to the core helper files
?>

<div id="noticebox" style="border: 1px dotted #ccc; width: 50%; padding: 10px; float: right;">
	<p><b>Huru Helpdesk <?php echo config('version');?> (d)</b><br />
 	<p><b>Version Changes</b><br />
	See the <a href="components/com_huruhelpdesk/readme.txt" target="_blank">readme.txt</a> file for information about this update.
	</p>
	<p><b>Known Issues</b><br />
	There are some issues still to be resolved in the beta version:
	<ul>
		<li>Javascript alert messages are currently English only</li>
		<li>Various problems with PHP 4 (time stamps, email validation error)</li>
		<li>A ReFirewall conflict was reported that was caused by the reuse of function <br>
		names between front and admin side helper scripts. All helper files have now been merged,  <br>
		so function names are no longer repeated.  Given this change, the conflict needs to be retested.</li>
		<li>Huru has not been tested with LDAP or other authentications</li>
		<li>There is a reported possible conflict with Community Builder's login/authentication</li>
	</ul>
	</p>
</div>
<span style="float:left;">
	<img src="components/com_huruhelpdesk/images/logo.png" style=""></img>
	<p><b><?php echo lang('Administration');?></b></p>
	<p> </p>

	<form action="index.php" method="post" name="adminForm">
		<ul>
			<li><a href="index.php?option=com_huruhelpdesk&view=config"><?php echo lang('GeneralConfiguration');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=department"><?php echo lang('Departments');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=user"><?php echo lang('Users');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=category"><?php echo lang('Categories');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=categorypredeftext"><?php echo lang('Predeftexts');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=priority"><?php echo lang('Priorities');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=status"><?php echo lang('Statuses');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=language"><?php echo lang('Languages');?></a></li>
			<li><a href="index.php?option=com_huruhelpdesk&view=email"><?php echo lang('EmailMessages');?></a></li>
		</ul>
		<ul>
			<li><a href="index.php?option=com_huruhelpdesk&view=about"><?php echo lang('About');?></a></li>
		</ul>
		<ul>
			<li><a href="http://www.huruhelpdesk.net/" target="_blank">Project Home</a></li>
			<li><a href="http://www.huruhelpdesk.net/documentation" target="_blank">Documentation</a></li>
			<li><a href="http://www.huruhelpdesk.net/forums" target="_blank">Support Forums</a></li>
		</ul>
		<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
	</form>
</span>