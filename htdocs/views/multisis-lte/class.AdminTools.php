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
    <div class="gap-10"></div>
    <div class="row-fluid">
    <div class="col-md-12">
    <?php 
    $this->startBoxSuccess(getMLText("admin_tools"));


?>
<div class="row">
	<div class="col-md-3">
	<?php if ($user->_comment != "client-admin") { ?>
		<a href="../out/out.UsrMgr.php" class="btn btn-medium"><i class="fa fa-user"></i><br /><?php echo getMLText("user_management")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">	
		<a href="../out/out.GroupMgr.php" class="btn btn-medium"><i class="fa fa-group"></i><br /><?php echo getMLText("group_management")?></a>
	<?php } ?>
	</div> <!--Ends -->
</div>

<div class="row">
	<div class="col-md-3">
		<a href="../out/out.BackupTools.php" class="btn btn-medium"><i class="fa fa-hdd"></i><br /><?php echo getMLText("backup_tools")?></a>
	</div> <!--Ends -->
<?php		
		if ($logfileenable)
			echo "<div class=\"col-md-3\">";
			echo "<a href=\"../out/out.LogManagement.php\" class=\"btn btn-medium\"><i class=\"fa fa-list\"></i><br />".getMLText("log_management")."</a>";
			echo "</div>";
?>
</div>

<div class="row">
	<div class="col-md-3">
		<a href="../out/out.DefaultKeywords.php" class="btn btn-medium"><i class="fa fa-reorder"></i><br /><?php echo getMLText("global_default_keywords")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.Categories.php" class="btn btn-medium"><i class="fa fa-columns"></i><br /><?php echo getMLText("global_document_categories")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.AttributeMgr.php" class="btn btn-medium"><i class="fa fa-tags"></i><br /><?php echo getMLText("global_attributedefinitions")?></a>
	</div> <!--Ends -->
</div>

<?php if($this->params['workflowmode'] == 'advanced') { ?>
<div class="row">	
	<div class="col-md-3">
		<a href="../out/out.WorkflowMgr.php" class="btn btn-medium"><i class="fa fa-sitemap"></i><br /><?php echo getMLText("global_workflows"); ?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.WorkflowStatesMgr.php" class="btn btn-medium"><i class="fa fa-star"></i><br /><?php echo getMLText("global_workflow_states"); ?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.WorkflowActionsMgr.php" class="btn btn-medium"><i class="fa fa-bolt"></i><br /><?php echo getMLText("global_workflow_actions"); ?></a>
	</div> <!--Ends -->
</div>

<?php } if($enablefullsearch) { ?>
<div class="row">
	<div class="col-md-3">
		<a href="../out/out.Indexer.php" class="btn btn-medium"><i class="fa fa-refresh"></i><br /><?php echo getMLText("update_fulltext_index")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.CreateIndex.php" class="btn btn-medium"><i class="fa fa-search"></i><br /><?php echo getMLText("create_fulltext_index")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.IndexInfo.php" class="btn btn-medium"><i class="fa fa-info-sign"></i><br /><?php echo getMLText("fulltext_info")?></a>
	</div> <!--Ends -->
</div>
<?php } ?>

<div class="row">
	<div class="col-md-3">
		<a href="../out/out.Statistic.php" class="btn btn-medium"><i class="fa fa-tasks"></i><br /><?php echo getMLText("folders_and_documents_statistic")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.Charts.php" class="btn btn-medium"><i class="fa fa-bar-chart"></i><br /><?php echo getMLText("charts")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.ObjectCheck.php" class="btn btn-medium"><i class="fa fa-check"></i><br /><?php echo getMLText("objectcheck")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.Timeline.php" class="btn btn-medium"><i class="fa fa-time"></i><br /><?php echo getMLText("timeline")?></a>
	</div> <!--Ends -->
</div>

<?php if ($user->_comment != "client-admin") { ?>
<div class="row">
	<div class="col-md-3">
		<a href="../out/out.Settings.php" class="btn btn-medium"><i class="fa fa-wrench"></i><br /><?php echo getMLText("settings")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.ExtensionMgr.php" class="btn btn-medium"><i class="fa fa-cogs"></i><br /><?php echo getMLText("extension_manager")?></a>
	</div> <!--Ends -->

	<div class="col-md-3">
		<a href="../out/out.Info.php" class="btn btn-medium"><i class="fa fa-info-sign"></i><br /><?php echo getMLText("version_info")?></a>
	</div> <!--Ends -->
</div>
</div>
	<?php } ?>	

<?php $this->endsBoxPrimary(); ?>

    </div>
    </div>
    <?php
		
    $this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>
