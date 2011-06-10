<?php
/**
 * @package Huru Helpdesk
 * @copyright Copyright (c)2009-2010 Huru Helpdesk Developers
 * @license GNU General Public License version 2, or later
 */

defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm">

	<p><b>About Huru Helpdesk</b></p>
	
	<p>Huru Helpdesk is based on the ASP application Liberum Helpdesk (<a href="http://www.liberum.org">www.liberum.org</a>). 
	Many thanks to the developers of Liberum - I have used their application for several years and have come to rely upon its 
	features and simplicity.  So much so that, when my Intranet converted from IIS/ASP to Apache/Joomla, I was forced to port
	Liberum to the new platform.  No existing Joomla! helpdesk/support ticket extension had what I needed.  While Huru Helpdesk 
	is not a direct port of Liberum (little or no code was actually reused/translated and several changes 
	were made to the database tables), the concepts behind Liberum, along with most of its features, were incorporated into 
	Huru Helpdesk.
	</p>
	<p><b>Features:</b>
	<ul>
		<li>Integrated Knowledgebase - cases can be sent to the knowledgebase by setting a flag on the case</li>
		<li>Users can view progress on cases and submit additional information</li>
		<li>Users can upload and attach file to cases</li>
		<li>Built-in reporting shows time spent on problem categories, departments</li>
		<li>Login and authorization integrated with Joomla! users table</li>
		<li>Plug-in available to automatically synchronize Joomla and Huru user tables</li>
		<li>Non-registered users can open support cases (optional)</li>
		<li>All case management and reporting is done via front-end, meaning support reps do not have to have back-end access</li>
		<li>Integrated with Joomla mail function for communication with reps and users</li>
		<li>Can assign default support rep to problem categories</li>
	</ul>
	</p>

	<p><b>Future plans:</b><br />
	These are some features that I would like to add to Huru Helpdesk in the future. If you have other suggestions, I'd love to hear them:
	<ul>
		<li>Plug-in to integrate knowledgebase search with Joomla search function</li>
		<li>Module to list new cases (similar to mod_latestnews)</li>
		<li>Convert internal language support to Joomla!</li>
		<li>Additional language support</li>
		<li>Ability to have more than one copy of the helpdesk installed on each Joomla site</li>
	</ul>
	</p>

	<p><b>Acknowledgements</b></p>
	<p>As mentioned above, we need to acknowledge the authors of Liberum Helpdesk (<a href="http://www.liberum.org">www.liberum.org</a>) for
	making such a useful and easy-to-use system.</p>
	<p>The icons used in Huru Helpdesk were created by <a href="http://www.wefunction.com">Function Design & Development Studio</a> and are available at <a href="http://www.wefunction.com/function-free-icon-set">http://www.wefunction.com/function-free-icon-set</a>. 
	Some minor modifications were made to make them look better resized.</p>
	<p>The reports use the <a href="http://code.google.com/apis/chart/">Google Charts API</a> for charting.</p>
	
	<p><b>What does 'Huru' mean?</b></p>
	<p>The helpdesk application upon which Huru Helpdesk is based is called "Liberum" which means "free"/"liberated" in Latin. 
	Since I was porting the application to Joomla! (which is a form of a Swahili word) I chose the Swahili word for "free"/"liberated"  - "huru" - 
	as the name for the project.  According to <a href="http://en.wiktionary.org/wiki/huru">Wiktionary</a>, 'huru' also means "how" in Swedish, which ties nicely with the knowledgebase feature of Huru Helpdesk.</p>

	
	<input type="hidden" name="j_id" value="<?php echo $j_id; ?>" />
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>

