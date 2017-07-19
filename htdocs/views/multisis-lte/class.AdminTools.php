<?php
/**
 * Implementation of AdminTools view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
/**
 * Include parent class
 */
require_once("class.Bootstrap.php");
/**
 * Class which outputs the html page for AdminTools view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_AdminTools extends SeedDMS_Bootstrap_Style {
	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$logfileenable = $this->params['logfileenable'];
		$enablefullsearch = $this->params['enablefullsearch'];

		$this->htmlStartPage(getMLText("my_account"), "skin-blue sidebar-mini");
		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();
		$this->contentStart();

		

?>
<div class="gap-20"></div>
<div class="row">
<div class="col-md-12">

	<div id="admin-tools">
	<div class="row">
	<?php if ($user->_comment != "client-admin") { ?>
		<a href="../out/out.UsrMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-user"></i><br /><?php echo getMLText("user_management")?></a>
		<a href="../out/out.GroupMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-group"></i><br /><?php echo getMLText("group_management")?></a>
	<?php } ?>
	</div>
	<div class="row">
		<a href="../out/out.BackupTools.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-hdd"></i><br /><?php echo getMLText("backup_tools")?></a>
<?php		
		if ($logfileenable)
			echo "<a href=\"../out/out.LogManagement.php\" class=\"col-md-3 btn btn-medium btn-default\"><i class=\"fa fa-list\"></i><br />".getMLText("log_management")."</a>";
?>
	</div>
	<div class="row">
		<a href="../out/out.DefaultKeywords.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-reorder"></i><br /><?php echo getMLText("global_default_keywords")?></a>
		<a href="../out/out.Categories.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-columns"></i><br /><?php echo getMLText("global_document_categories")?></a>
		<a href="../out/out.AttributeMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-tags"></i><br /><?php echo getMLText("global_attributedefinitions")?></a>
	</div>
<?php
	if($this->params['workflowmode'] == 'advanced') {
?>
	<div class="row">
		<a href="../out/out.WorkflowMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-sitemap"></i><br /><?php echo getMLText("global_workflows"); ?></a>
		<a href="../out/out.WorkflowStatesMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-star"></i><br /><?php echo getMLText("global_workflow_states"); ?></a>
		<a href="../out/out.WorkflowActionsMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-bolt"></i><br /><?php echo getMLText("global_workflow_actions"); ?></a>
	</div>
<?php
		}
		if($enablefullsearch) {
?>
	<div class="row">
		<a href="../out/out.Indexer.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-refresh"></i><br /><?php echo getMLText("update_fulltext_index")?></a>
		<a href="../out/out.CreateIndex.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-search"></i><br /><?php echo getMLText("create_fulltext_index")?></a>
		<a href="../out/out.IndexInfo.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-info-sign"></i><br /><?php echo getMLText("fulltext_info")?></a>
	</div>
<?php
		}
?>
	<div class="row">
		<a href="../out/out.Statistic.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-tasks"></i><br /><?php echo getMLText("folders_and_documents_statistic")?></a>
		<a href="../out/out.Charts.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-bar-chart"></i><br /><?php echo getMLText("charts")?></a>
		<a href="../out/out.ObjectCheck.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-check"></i><br /><?php echo getMLText("objectcheck")?></a>
		<a href="../out/out.Timeline.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-chart"></i><br /><?php echo getMLText("timeline")?></a>
	</div>
	<div class="row">
	<?php if ($user->_comment != "client-admin") { ?>
		<a href="../out/out.Settings.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-wrench"></i><br /><?php echo getMLText("settings")?></a>
		<a href="../out/out.ExtensionMgr.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-cogs"></i><br /><?php echo getMLText("extension_manager")?></a>
		<a href="../out/out.Info.php" class="col-md-3 btn btn-medium btn-default"><i class="fa fa-info-circle"></i><br /><?php echo getMLText("version_info")?></a>
	<?php } ?>	
	</div>
	</div>
	</div>
	</div>
<?php
		echo "</div>";

		

		$this->mainFooter();		
		$this->containerEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>