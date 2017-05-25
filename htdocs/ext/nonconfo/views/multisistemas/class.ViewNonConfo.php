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
						<td>
							<div>
								<a type="button" id="display-analysis" class="btn btn-sm btn-success"><?php echo getMLText('nonconfo_add_analysis'); ?></a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<br/>
	<div class="" style="display: none;" id="analysis-block">
		<div class="well">
		<?php echo $this->contentSubHeading(getMLText("nonconfo_add_analysis")); ?>
			<form class="form-horizontal" action="../op/op.AddAnalysis.php" id="form1" name="form1" method="post">
				<table class="table">
						<thead>
							<tr>
								<th><?php echo getMLText("nonconfo_analysis_description"); ?>	</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="span8">
										<textarea class="comment_width" name="description" rows="5" cols="100"></textarea>
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
							</tr>
							<tr>
								<td>
									<a type="button" class="btn btn-sm btn-success"><?php echo getMLText('save'); ?></a>
									<a type="button" id="cancel-btn" class="btn btn-sm btn-defaul"><?php echo getMLText('cancel'); ?></a>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
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
