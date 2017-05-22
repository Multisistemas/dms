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

		$this->htmlStartPage(getMLText("process"));
		$this->globalNavigation();
		$this->contentStart();
		$this->pageNavigation("", "process");

		$this->contentHeading(getMLText("nonconfo_add_process"));
		$this->contentContainerStart();

		$expdate = date('Y-m-d');
?>

<form class="form-horizontal" action="../op/op.AddProcess.php" id="form1" name="form1" method="post">

		<div class="control-group">
			<label class="control-label"><?php printMLText("nonconfo_process_name");?>:</label>
			<div class="controls"><input type="text" name="name" size="100"></div>
		</div>
		
		<div class="controls">
			<input class="btn" type="submit" value="<?php printMLText("nonconfo_add_process");?>">
		</div>

</form>
<?php
		$this->contentContainerEnd();
		$this->contentEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>
