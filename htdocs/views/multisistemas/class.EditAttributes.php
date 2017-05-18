<?php
/**
 * Implementation of EditAttributes view
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
 * Class which outputs the html page for EditAttributes view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_EditAttributes extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */

  	$strictformcheck = $this->params['strictformcheck'];
  	$dropfolderdir = $this->params['dropfolderdir'];

    header('Content-Type: application/javascript; charset=UTF-8');
?>
  function checkForm()
  {
    msg = new Array();
   	//if (document.form1.userfile[].value == "") msg += "<?php printMLText("js_no_file");?>\n";

<?php 

    	if ($strictformcheck) { 
?>
      if(!document.form1.name.disabled){
        if (document.form1.name.value == "") msg.push("<?php printMLText("js_no_name");?>");
      }
      if (document.form1.comment.value == "") msg.push("<?php printMLText("js_no_comment");?>");
      if (document.form1.keywords.value == "") msg.push("<?php printMLText("js_no_keywords");?>");
<?php
  		}
?>
  		if (msg != ""){
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
    return true;
  }

        
  $(document).ready(function() {
    $('body').on('submit', '#form1', function(ev){
      if(checkForm()) return;
  	      ev.preventDefault();
    });
    
    $('#new-file').click(function(event) {
      $("#upload-file").clone().appendTo("#upload-files").removeAttr("id").children('div').children('input').val('');
    });

    jQuery.validator.addMethod("alternatives", function(value, element, params) {
      if(value == '' && params.val() == '')
        return false;
      return true;
    }, "<?php printMLText("js_no_file");?>");
      
      $("#form1").validate({
      	invalidHandler: function(e, validator) {
        noty({
        	text:  (validator.numberOfInvalids() == 1) ? "<?php printMLText("js_form_error");?>".replace('#', validator.numberOfInvalids()) : "<?php printMLText("js_form_errors");?>".replace('#', validator.numberOfInvalids()),
        type: 'error',
        dismissQueue: true,
        layout: 'topRight',
        theme: 'defaultTheme',
        timeout: 1500,
      });
    },
    	rules: {
	      'userfile[]': {
	      alternatives: $('#dropfolderfileform1')
      },
        dropfolderfileform1: {
        alternatives: $(".btn-file input")
      }
    },
      messages: {
      name: "<?php printMLText("js_no_name");?>",
      comment: "<?php printMLText("js_no_comment");?>",
      keywords: "<?php printMLText("js_no_keywords");?>"
    },
      errorPlacement: function( error, element ) {
        if ( element.is( ":file" ) ) {
        error.appendTo( element.parent().parent().parent());
        console.log(element);
      } else {
        error.appendTo( element.parent());
      }
    }
  });
});
<?php
        $this->printKeywordChooserJs("form1");
        if($dropfolderdir) {
            $this->printDropFolderChooserJs("form1");
        }
} /* }}} */         

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$document = $this->params['document'];
		$version = $this->params['version'];
		$attrdefs = $this->params['attrdefs'];

		$this->htmlStartPage(getMLText("document_title", array("documentname" => htmlspecialchars($document->getName()))));
		$this->globalNavigation($folder);
		$this->contentStart();
		$this->pageNavigation($this->getFolderPathHTML($folder, true, $document), "view_document", $document);

		$this->contentHeading(getMLText("edit_attributes"));
		$this->contentContainerStart();
?>
<form class="form-horizontal" action="../op/op.EditAttributes.php" name="form1" method="POST">
	<?php echo createHiddenFieldWithKey('editattributes'); ?>
	<input type="hidden" name="documentid" value="<?php print $document->getID();?>">
	<input type="hidden" name="version" value="<?php print $version->getVersion();?>">
    <table class="table-condensed">
        <tr>
            <td>
                <?php $this->contentSubHeading(getMLText("document_infos")); ?>
            </td>
        </tr>
        <tr>
            <td class="lbl-right"><?php printMLText("name");?>:</td>
            <td class="lbl-left"><?php print $version->_orgFileName; ?></td>
        </tr>
        <tr>
            <td class="lbl-right"><?php printMLText("version");?>:</td>
            <td class="lbl-left"><?php print $version->getVersion(); ?></td>
        </tr>
        <tr>
            <td class="lbl-right"><?php printMLText("change_local_file");?>:</td>
            <td><?php $this->printFileChooser('userfile[]', false); ?></td>
        </tr>
        <tr>
            <td class="lbl-right"><?php printMLText("comment_for_current_version");?>:</td>
            <td><textarea name="version_comment" rows="6" cols="80"></textarea><br />
        </tr>
        <tr>
            <td>
                <div class="controls">
                    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> <?php printMLText("save") ?></button>
                </div>
            </td>
        </tr>
    </table>
</form>
<?php
		$this->contentContainerEnd();
		$this->contentEnd();
		$this->htmlEndPage();
	} /* }}} */
}
?>
