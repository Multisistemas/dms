<?php
/**
 * Implementation of ViewAllNonConfo view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Luis Medrano <lmedrano@multisistemas.com.sv>
 * @copyright  Copyright (C) 2011-2017 Multisistemas,
 * @version    Release: @package_version@
 */

/**
 * Include parent class
 */
require_once("../../../views/$theme/class.Bootstrap.php");

/**
 * Class which outputs the html page for ViewAllNonConfo view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_ViewAllNonConfo extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */
		header('Content-Type: application/javascript; charset=UTF-8');
?>
function checkForm()
{
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
});
<?php
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$processes = $this->params['processes'];
		$nonconfos = $this->params['nonconformities'];

		$this->htmlStartPage(getMLText("nonconfo_title"));
		$this->globalNavigation();
		$this->contentStart();
		$this->pageNavigation("nonconfo_title", "nonconfo_view_navigation", "");

		$date = new DateTime();
?>
	
<div class="row-fluid">
	<div class="span12">
		<?php $this->contentHeading(getMLText("nonconfo_created_by_current_user")); ?>
		<div class="well">
			<?php echo $this->contentSubHeading(getMLText("nonconfo_general_info")); ?>
			<div style="overflow-x: auto;">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th><?php echo getMLText("nonconfo_process_name"); ?></th>
						<th><?php echo getMLText("nonconfo_request_date"); ?></th>
						<th><?php echo getMLText("nonconfo_action_type"); ?></th>
						<th><?php echo getMLText("nonconfo_origin_source"); ?></th>
						<th></th>
					</tr>	
				</thead>
				<tbody>				
				<?php $i = 0; $j = 1;
				foreach ($nonconfos as $nonconfo => $i) { ?>
					<tr>
						<td><?php echo $j; ?></td>
						<td><?php
						for ($k=0; $k < count($processes); $k++) { 
							if ($i['processId'] == $processes[$k]['id']) {
								echo $processes[$k]['name'];
							}
						}
						?></td>
						<td><?php $date->setTimestamp($i['created']); echo $date->format('d-m-Y H:i:s'); ?></td>
						<td><?php echo $i['type']; ?></td>
						<td><?php echo $i['source']; ?></td>
						<td><a type="button" class="btn btn-info" href="../out/out.ViewNonConfo.php?nonconfoId=<?php echo $i['id']; ?>"><i class="icon-eye-open"></i> <?php echo getMLText('nonconfo_view'); ?></a>
						<a type="button" class="btn btn-danger" href="../out/out.ViewNonConfo.php?nonconfoId=<?php echo $i['id']; ?>"><i class="icon-remove"></i> <?php echo getMLText('nonconfo_delete'); ?></a></td>
					</tr>
				<?php $j++; } ?>
				</tbody>
			</table>
			</div>
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
