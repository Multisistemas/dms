<?php
/**
 * Implementation of AddNonConfo view
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
require_once("../op/op.Ajax.php");

/**
 * Class which outputs the html page for AddNonConfo view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_ViewNonConfo extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */
		$nonconfo = $this->params['nonconfo'];
		header('Content-Type: application/javascript; charset=UTF-8');
//		echo '<script src="../../styles/application.js"></script>'."\n";
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

	/* Show analysis form */
	$("#display-analysis").on('click', function() {
   $("#analysis-block").fadeIn('slow');
	});

	/* Cancel analysis */
	$("#cancel-btn").on('click', function() {
   $("#analysis-block").fadeOut('slow');
	});

});

<?php

} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$allUsers = $this->params['allUsers'];
		$nonconfo = $this->params['nonconfo'];
		$process = $this->params['process'];
		$analysis = $this->params['analysis'];
		$actions = $this->params['actions'];
		$operation = $this->params['operation'];

		$this->htmlStartPage(getMLText("nonconfo_title"));
		$this->globalNavigation();
		$this->contentStart();
		$this->pageNavigation("nonconfo_title", "nonconfo_view_navigation", "");

?>

<div class="row-fluid">
	<div class="span12">
		<?php $this->contentHeading(getMLText("nonconfo_view_nonconfo")); ?>
		<div class="well">
			<?php echo $this->contentSubHeading(getMLText("nonconfo_general_info")); ?>
			<div style="overflow-x: auto;">
			<table class="table table-striped">
				<?php
					$date = new DateTime();
					$date->setTimestamp($nonconfo['created']);
				?>
				<thead>
					<tr>
						<th><?php echo getMLText("nonconfo_process_name"); ?></th>
						<th><?php echo getMLText("nonconfo_request_date"); ?></th>
						<th><?php echo getMLText("nonconfo_action_type"); ?></th>
						<th><?php echo getMLText("nonconfo_origin_source"); ?></th>
					</tr>	
				</thead>
				<tbody>
					<tr>
						<td><?php echo $process['name']; ?></td>
						<td><?php echo $date->format('d-m-Y H:i:s'); ?></td>
						<td><?php echo $nonconfo['type'] ?></td>
						<td><?php echo $nonconfo['source'] ?></td>
					</tr>
				</tbody>
			</table>
			</div>
			<div style="overflow-x: auto;">
			<table class="table">
				<thead>
					<tr>
						<th><?php echo getMLText("nonconfo_description"); ?>	</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<div class="span8">
								<div class="alert alert-info" role="info">
									<strong><?php echo $nonconfo['description']; ?></strong>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="lbl-right">
								<?php if($analysis == false ) { 
								 echo "<a type=\"button\" id=\"display-analysis\" class=\"btn btn-sm btn-warning\">".getMLText('nonconfo_add_analysis')."</a>";
								} ?>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>

<div class="row-fluid" id="analysis-block" <?php if ($analysis == false) { echo "style=\"display: none;\""; } ?>>
	<div class="span12">
		<div class="well">
		<?php 
			echo $this->contentSubHeading(getMLText("nonconfo_analysis_title")); 
			if($operation == 'add') {
				$action = "../op/op.AddAnalysis.php";
			} else {
				$action = "../op/op.EditAnalysis.php";
			}
		?>
			<form class="form-horizontal" action="<?php echo $action ?>" id="form1" name="form1" method="post" enctype="multipart/form-data">
			<?php echo createHiddenFieldWithKey($operation.'analysis'); ?>
			<input type="hidden" name="nonconfoId" value="<?php echo $nonconfo['id']; ?>">
			<div style="overflow-x: auto;">
				<table class="table">
						<thead>
							<tr>
								<th><?php echo getMLText("nonconfo_analysis_description"); ?>	</th>
								<th><?php echo getMLText("nonconfo_attached_files"); ?>	</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="span8">
									<?php if($analysis != false) { 
											echo "<textarea class=\"comment_width\" name=\"description\" rows=\"5\" cols=\"100\" disabled>".$analysis['comment']."</textarea>";
											echo "<div class=\"list-action\">";
											echo "<a class=\"enable-comment-btn\" rel=\"1\" msg=\"Edit enabled\" title=\"Editar descripción del análisis\">
									<i class=\"icon-edit\"></i></a>";
											echo "</div>";
										} else {
											echo "<textarea class=\"comment_width\" name=\"description\" rows=\"5\" cols=\"100\"></textarea>";
										}
									?>
									</div>
								</td>
								<td>
									<div class="span8">
									<?php if(!empty($analysis['fileName'])) {
										echo("<a href='../op/op.ViewAnalysisAttach.php?filename=".$analysis['fileName']."&type=".$analysis['mimeType']."'>".$analysis['fileName']."</a>");
									} else {
										echo "Ninguno";
									}
									?>
									</div>
								</td>
							</tr>
							<tr>
								<td class=""><?php printMLText("nonconfo_attach_file");?>:
	                <div id="upload-files">
	                  <div id="upload-file">
	                    <div class="input-append">
	                      <input type="text" class="form-control" readonly>
	                      <span class="btn btn-default btn-file">
										     	<?php printMLText("browse");?>&hellip; <input id="filename" type="file" name="attach[]">
										    </span>
	                    </div>
	                  </div>
	                </div>
            		</td>
            		<td></td>
							</tr>
							<tr>
								<td>
								<?php if($analysis == false ) { 
									echo "<input type=\"hidden\" name=\"operation\" value=\"add\"></input>";
									echo "<input type=\"submit\" class=\"btn btn-success\" value=\"".getMLText('nonconfo_save')."\">";
									echo "<a type=\"button\" id=\"cancel-btn\" class=\"btn btn-sm btn-default\">".getMLText('cancel')."</a>";
								} else {
									echo "<input type=\"hidden\" name=\"analysisId\" value=\"".$analysis['id']."\"></input>";
									echo "<input type=\"hidden\" name=\"description\" value=\"".$analysis['comment']."\"></input>";
									echo "<input type=\"hidden\" name=\"operation\" value=\"edit\"></input>";
									echo "<button type=\"submit\" class=\"btn btn-success\"><i class=\"icon-pencil\"></i> ".getMLText('nonconfo_edit')."</button>";
								} ?>
								</td>
								<td class="lbl-right">
								<?php if($analysis != false ) { 
									echo "<a type=\"button\" href=\"../out/out.AddAction.php?nonconfoId=".$nonconfo['id']."\" id=\"add-actions-btn\" class=\"btn btn-sm btn-warning\"><i class=\"icon-plus\"></i> ".getMLText('nonconfo_add_actions')."</a>";
								} ?>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</form>
		</div>
	</div>
</div>

<?php if (false != $actions && count($actions) >= 1 ) { 
	$i = 0;
	foreach ($actions as $action => $i) {
	echo "<div class='row-fluid'>
		<div class='span12'>
			<div class='well'>
				<div style='overflow-x: auto;''>
					<table class='table table-striped'>
						<thead>
							<tr>
								<th>".getMLText('nonconfo_action_detail')."</th>
								<th>".getMLText('nonconfo_action_creation')."</th>
								<th>".getMLText('nonconfo_action_date_end')."</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class='alert alert-info' role='info'>
										<strong>".$i['description']."</strong>
									</div>
								</td>
								<td>
									".gmdate("d-m-Y", $i['dateStart'])."
								</td>
								<td>
									".gmdate("d-m-Y", $i['dateEnd'])."
								</td>
								<td>
										<a type='button' href='../out/out.EditAction.php?actionId=".$i['id']."' class='btn btn-success' rel=' id='btn-edit-action'><i class='icon-pencil'></i> ".getMLText('nonconfo_edit')."</a>
										<a type='button' href='../op/op.DeleteAction.php?actionId=".$i['id']."' class='btn btn-danger' rel=' id='btn-edit-action'><i class='icon-close'></i> ".getMLText('nonconfo_delete')."</a>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
			</div>
		</div>
	</div>";
	$i++;
} } ?>

<?php

		$this->contentContainerEnd();
		$this->contentEnd();
		$this->htmlEndPage();

	} /* }}} */
}
?>
