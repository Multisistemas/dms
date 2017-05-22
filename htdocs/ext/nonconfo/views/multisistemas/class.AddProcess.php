<?php
/**
 * Implementation of AddEvent view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Herson Cruz <herson@multisistemas.com.sv>
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
class SeedDMS_View_AddProcess extends SeedDMS_Bootstrap_Style {

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

function removeProcess(id){
	$.ajax({
    data: id,
    url: '../op/op.DeleteProcess.php',
    type: 'post',
    beforeSend: function () {
      noty({
	  		text: 'Procesando, por favor espere',
	  		type: 'error',
	      dismissQueue: true,
	  		layout: 'topRight',
	  		theme: 'defaultTheme',
				_timeout: 1500,
  		});
    },
    success: function (response) {
      noty({
	  		text: 'Elemento borrado',
	  		type: 'success',
	      dismissQueue: true,
	  		layout: 'topRight',
	  		theme: 'defaultTheme',
				_timeout: 1500,
  		});
    }
  });
}

$(document).ready(function() {
	$('body').on('submit', '#form1', function(ev){
		if(checkForm()) return;
		ev.preventDefault();
	});
});

<?php
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$processes = $this->params['processes'];

		$this->htmlStartPage(getMLText("nonconfo_title"));
		$this->globalNavigation();
		$this->contentStart();
		$this->pageNavigation("nonconfo_title", "nonconfo_view_navigation", "");

?>

<div class="row-fluid">

	<div class="span4">
		<?php $this->contentHeading(getMLText("nonconfo_add_process")); ?>
		<div class="well">
			<form class="form-horizontal" action="../op/op.AddProcess.php" id="form1" name="form1" method="post">
					<div class="control-group">
						<label class="control-label"><?php printMLText("nonconfo_process_name");?>:</label>
						<div class="controls"><input type="text" name="name" size="100"></div>
					</div>
					<div class="controls">
						<input class="btn" type="submit" value="<?php printMLText("nonconfo_add_process");?>">
					</div>
			</form>
		</div>
	</div>

	<div class="span8">
		<?php $this->contentHeading(getMLText("nonconfo_process_list")); ?>
		<div class="well">
		<?php if(count($processes) > 0) { ?>
			<table id="viewfolder-table" class="table table-condensed table-hover">
				<thead>
					<tr>
						<th><?php echo getMLText("nonconfo_process_name"); ?></th>
						<th><?php echo getMLText("nonconfo_process_owner"); ?></th>
						<th><?php echo getMLText("nonconfo_actions"); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($processes as $process): ?>
						<tr>
							<td>
								<?php echo $process['name']; ?>
							</td>
							<td>
								<p>ninguno</p>
							</td>
							<td>
								<div class="list-action">
									<a id="delete-process-btn" onclick="removeProcess(<?php echo $process['id']; ?>);"><i class="icon-remove"></i></a>
									<a href="/out/out.EditDocument.php?documentid=52" title="Editar propiedades de documento">
									<i class="icon-edit"></i></a>
								</div>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php	} ?>
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
