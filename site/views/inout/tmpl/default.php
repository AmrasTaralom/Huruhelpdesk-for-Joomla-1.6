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
if(!checkusermin('rep')) $mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));

?>
<head>
	<link rel="stylesheet" type="text/css" href="components/com_huruhelpdesk/css/huruhelpdesk.css" />
	<script type="text/javascript" language="javascript" src="components/com_huruhelpdesk/js/head.js"></script>
</head>
<?php
$userlvl = userlevel();

//build link for re-sorting table
$sortlink = JFilterOutput::ampReplace('?option='.JRequest::getCmd('option').'&view='.JRequest::getVar('view','').'&type='.JRequest::getVar('type','').'&Itemid='.JRequest::getVar('Itemid',''));

if(JRequest::getVar('sort')=='a') $sortlink = $sortlink.'&sort=d';
else $sortlink = $sortlink.'&sort=a';

//Pagination
// Get data from the model
$items =& $this->get('Data');      
$pagination =& $this->get('Pagination');
// push data into the template
$this->assignRef('items', $items);     
$this->assignRef('pagination', $pagination);

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
if(JRequest::getVar('type')=='rep') toolbar('inoutall','refresh','home');
else toolbar('inoutrep','refresh','home');
?>
<form action="?" method="post" name="adminForm">
	<table class="userlist">
		<thead>
			<tr>
				<th align="center"><?php echo lang('In');?></th>
				<th align="center"><?php echo lang('Out');?></th>
				<th align="center"><a href="<?php echo $sortlink;?>&order=username" class="listhead"><?php echo lang('Username');?></a></th>
				<th align="center"><a href="<?php echo $sortlink;?>&order=name" class="listhead"><?php echo lang('Name');?></a></th>
				<th align="center"><a href="<?php echo $sortlink;?>&order=email" class="listhead"><?php echo lang('Email');?></a></th>
				<th align="center"><?php echo lang('Rep');?></th>
				<th align="center"><?php echo lang('Admin');?></th>
			</tr>
		</thead>
		
		<?php 
		$k = 0;
		
		for($i=0,$n=count($this->rows); $i<$n; $i++)
		{
			$row =& $this->rows[$i];
			?>
			
			<tr>
				<?php 
				$query = "SELECT username FROM #__session WHERE guest<>1 AND username='".$row->username."'";
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$online = $db->loadRow();
				?>
				
				<td align="center" class="problemlist"> 
					<?php 
					if(isset($online[0]) && strlen($online[0])>0)//##my201004080318 Fix warning. It was: if(strlen($online[0])>0)
					{
						?>
						<img src="components/com_huruhelpdesk/images/circle_green_16.png" class="userstatusicon"/>
						<?php 
					}
					?>
				</td>
				<td align="center" class="problemlist"> 
					<?php 
					if(isset($online[0]) && strlen($online[0])<=0) //##my201004080318 Fix warning. It was: if(strlen($online[0])<=0)
					{
						?>
						<img src="components/com_huruhelpdesk/images/circle_red_16.png" class="userstatusicon"/>
						<?php 
					}
					?>
				</td>
				<td align="center" class="problemlist"> 
					<?php echo $row->username; ?>
				</td>
				<td align="center" class="problemlist"> 
					<?php echo $row->name; ?>
				</td>
				<td align="center" class="problemlist"> 
					<?php echo $row->email; ?>
				</td>
				<td align="center" class="problemlist"> 
					<?php 
					if($row->isrep == 1)
					{
						?>
						<img src="components/com_huruhelpdesk/images/circle_blue_16.png" class="userstatusicon"/>
						<?php 
					}
					?>
				</td>
				<td align="center" class="problemlist"> 
					<?php 
					if($row->isadmin == 1)
					{
						?>
						<img src="components/com_huruhelpdesk/images/circle_blue_16.png" class="userstatusicon"/>
						<?php 
					}
					?>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
		}
		?>
	</table>
	<table class="pagination" align="center">
		<tr>
			<td class="pagination" colspan="6"><?php echo $this->pagination->getListFooter();?></td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option'); ?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view',''); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="type" value="<?php echo JRequest::getVar('type',''); ?>" />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',''); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<script language="javascript">displayMessage('<?php if(JRequest::getVar('type')=='rep') echo lang('RepsAdmins');else echo lang('AllUsers');?>');</script>
	
	

		