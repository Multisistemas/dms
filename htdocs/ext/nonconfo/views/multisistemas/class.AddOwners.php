<?php
/**
 * Implementation of AddOwners view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Herson Cruz <herson@multisistemas.com.sv>
 * @author     Luis Medrano <lmedrano@multisistemas.com.sv>
 * @copyright  Copyright (C) 2011-2017 Multisistemas,
 * @version    Release: @package_version@
 */

/**
 * Include parent class
 */
require_once("../../../views/$theme/class.Bootstrap.php");

/**
 * Class which outputs the html page for AddEvent view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_AddOwners extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */
		header('Content-Type: application/javascript; charset=UTF-8');
?>
function checkForm() {
	msg = new Array();
	if (document.form1.name.value == "") msg.push("<?php printMLText("js_no_name");?>");

	if (msg != "") {
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}

$(document).ready(function() {
	$('body').on('submit', '#form1', function(ev){
		if(checkForm()) return;
		ev.preventDefault();
	});

	$('body').on('click', 'a.delete-process-btn', function(ev){
		id = $(ev.currentTarget).attr('rel');
		confirmmsg = $(ev.currentTarget).attr('confirmmsg');
		msg = $(ev.currentTarget).attr('msg');
		formtoken = "<?php echo createFormKey('removeprocess'); ?>";
		bootbox.dialog(confirmmsg, [{
		"label" : "<i class='icon-remove'></i><?php echo getMLText("nonconfo_rm_process"); ?>",
		"class" : "btn-danger",
		"callback": function() {
			$.get('../op/op.DeleteProcess.php',
				{ command: 'deleteprocess', id: id, formtoken: formtoken },
							function(data) {
								if(data.success) {
									$('#table-row-process-'+id).hide('slow');
									noty({
										text: data.message,
										type: 'success',
										dismissQueue: true,
										layout: 'topRight',
										theme: 'defaultTheme',
										timeout: 1500,
									});
								} else {
									noty({
										text: data.message,
										type: 'error',
										dismissQueue: true,
										layout: 'topRight',
										theme: 'defaultTheme',
										timeout: 3500,
									});
								}
							},
							'json'
						);
					}
				}, {
					"label" : "<?php echo getMLText("cancel"); ?>",
					"class" : "btn-cancel",
					"callback": function() {
					}
				}]);
			});

			$("#ownerid").change(function() {
				$('div.ajax').trigger('update', {processid: $(this).val()});
			});

});

<?php
	} /* }}} */

	function showForm($selProcess) { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$allUsers = $this->params['allusers'];

		if($selProcess) {
			
		

		// <table class="table-condensed"> 
		
			/*$members = $group->getUsers();
			if (count($members) == 0)
				print "<tr><td>".getMLText("no_group_members")."</td></tr>";
			else {
			
				foreach ($members as $member) {
				
					print "<tr>";
					print "<td><i class=\"icon-user\"></i></td>";
					print "<td>" . htmlspecialchars($member->getFullName()) . "</td>";
					print "<td>" . ($group->isMember($member,true)?getMLText("manager"):"&nbsp;") . "</td>";
					print "<td>";
					print "<form action=\"../op/op.GroupMgr.php\" method=\"post\" class=\"form-inline\" style=\"display: inline-block; margin-bottom: 0px;\"><input type=\"hidden\" name=\"action\" value=\"rmmember\" /><input type=\"hidden\" name=\"groupid\" value=\"".$group->getID()."\" /><input type=\"hidden\" name=\"userid\" value=\"".$member->getID()."\" />".createHiddenFieldWithKey('rmmember')."<button type=\"submit\" class=\"btn btn-mini\"><i class=\"icon-remove\"></i> ".getMLText("delete")."</button></form>";
					print "&nbsp;";
					print "<form action=\"../op/op.GroupMgr.php\" method=\"post\" class=\"form-inline\" style=\"display: inline-block; margin-bottom: 0px;\"><input type=\"hidden\" name=\"groupid\" value=\"".$group->getID()."\" /><input type=\"hidden\" name=\"action\" value=\"tmanager\" /><input type=\"hidden\" name=\"userid\" value=\"".$member->getID()."\" />".createHiddenFieldWithKey('tmanager')."<button type=\"submit\" class=\"btn btn-mini\"><i class=\"icon-random\"></i> ".getMLText("toggle_manager")."</button></form>";
					print "</td></tr>";
				}
			}*/
		
		// </table> 
		

		$this->contentSubHeading(getMLText("nonconfo_add_owners"));
?>
		
		<form class="form-inline" action="../op/op.AddOwners.php" method="POST" name="form_2" id="form_2">
			<?php echo createHiddenFieldWithKey('addowner'); ?>
			<input type="Hidden" name="action" value="addowner">
			<input type="Hidden" name="processId" value="<?php print $selProcess['id']; ?>">
			<table class="table-condensed">
				<tr>
					<td>
						<select name="userid" id="userid">
							<option value="-1"><?php printMLText("select_one");?></option>
							<?php
								foreach ($allUsers as $user)
									//if (!$group->isMember($currUser)) // TODO : Obtener los usuarios de un proceso
										print "<option value=\"".$user->getID()."\">" . htmlspecialchars($user->getLogin()." - ".$user->getFullName()) . "</option>\n";
							?>
						</select>
					</td>
					<td>
						<input type="submit" class="btn" value="<?php printMLText("add"); ?>">
					</td>
				</tr>
			</table>
		</form>
<?php
		}
	} /* }}} */

	function form() { /* {{{ */
		$selProcess = $this->params['selProcess'];

		$this->showForm($selProcess);
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$processes = $this->params['processes'];

		$this->htmlAddHeader('<script type="text/javascript" src="/styles/'.$this->theme.'/bootbox/bootbox.min.js"></script>'."\n", 'js');

		$this->htmlStartPage(getMLText("nonconfo_title"));
		$this->globalNavigation();
		$this->contentStart();
		$this->pageNavigation("nonconfo_title", "nonconfo_view_navigation", "");

?>

<div class="row-fluid">

	<div class="span4">
		<?php $this->contentHeading(getMLText("nonconfo_add_owners")); ?>
		<div class="well">
			<form class="form-horizontal" action="../op/op.AddOwners.php" id="form1" name="form1" method="post">
					<?php echo createHiddenFieldWithKey('addowners'); ?>
					<div class="control-group">
						<label class="control-label"><?php printMLText("nonconfo_select_process");?>:</label>
						<div class="controls">
							<select class="chzn-select" name="ownerid">
								<?php
									foreach ($processes as $process) {
										print "<option value=\"".$process['id']."\" data-subtitle=\"\"";
										print ">" . htmlspecialchars($process['name']) . "</option>\n";
									}
								?>
							</select>
						</div>
						<label class="control-label"><?php printMLText("nonconfo_select_owners");?>:</label>
						<div class="controls">

						</div>
					</div>
					<div class="controls">
						<input class="btn btn-success" type="submit" value="<?php printMLText("nonconfo_add_process");?>">
					</div>
			</form>
		</div>
	</div>

	<div class="span8">
		<?php $this->contentHeading(getMLText("nonconfo_owners_details")); ?>
		<div class="well">
			<div class="ajax" data-view="GroupMgr" data-action="form" <?php echo ($process ? "data-query=\"processid=".$process['id']."\"" : "") ?>></div>
	</div>
	</div>
</div>

<?php

		$this->contentContainerEnd();
		$this->contentEnd();
		$this->htmlEndPage();

	} /* }}} */
}
?>
