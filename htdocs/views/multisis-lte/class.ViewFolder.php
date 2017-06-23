<?php
/**
 * Implementation of ViewFolder view
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
 * Include class to preview documents
 */
require_once("SeedDMS/Preview.php");

/**
 * Class which outputs the html page for ViewFolder view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_ViewFolder extends SeedDMS_Bootstrap_Style {

	function getAccessModeText($defMode) { /* {{{ */
		switch($defMode) {
			case M_NONE:
				return getMLText("access_mode_none");
				break;
			case M_READ:
				return getMLText("access_mode_read");
				break;
			case M_READWRITE:
				return getMLText("access_mode_readwrite");
				break;
			case M_ALL:
				return getMLText("access_mode_all");
				break;
		}
	} /* }}} */

	function printAccessList($obj) { /* {{{ */
		$accessList = $obj->getAccessList();
		if (count($accessList["users"]) == 0 && count($accessList["groups"]) == 0)
			return;

		$content = '';
		for ($i = 0; $i < count($accessList["groups"]); $i++)
		{
			$group = $accessList["groups"][$i]->getGroup();
			$accesstext = $this->getAccessModeText($accessList["groups"][$i]->getMode());
			$content .= $accesstext.": ".htmlspecialchars($group->getName());
			if ($i+1 < count($accessList["groups"]) || count($accessList["users"]) > 0)
				$content .= "<br />";
		}
		for ($i = 0; $i < count($accessList["users"]); $i++)
		{
			$user = $accessList["users"][$i]->getUser();
			$accesstext = $this->getAccessModeText($accessList["users"][$i]->getMode());
			$content .= $accesstext.": ".htmlspecialchars($user->getFullName());
			if ($i+1 < count($accessList["users"]))
				$content .= "<br />";
		}

		if(count($accessList["groups"]) + count($accessList["users"]) > 3) {
			$this->printPopupBox(getMLText('list_access_rights'), $content);
		} else {
			echo $content;
		}
	} /* }}} */

	function js() { /* {{{ */
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$orderby = $this->params['orderby'];
		$expandFolderTree = $this->params['expandFolderTree'];
		$enableDropUpload = $this->params['enableDropUpload'];

		header('Content-Type: application/javascript; charset=UTF-8');
		parent::jsTranslations(array('cancel', 'splash_move_document', 'confirm_move_document', 'move_document', 'splash_move_folder', 'confirm_move_folder', 'move_folder'));
?>
function folderSelected(id, name) {
	window.location = '../out/out.ViewFolder.php?folderid=' + id;
}


//$(document).ajaxStart(function() { Pace.restart(); });
//  $('.ajax').click(function(){
//    $.ajax({url: '#', success: function(result){
//    $('.ajax-content').html('<hr>Ajax Request Completed !');
//  }});
//});
	
	$(function () {
    $('#viewfolder-table').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false
    });
  });

<?php
		$this->printNewTreeNavigationJs($folder->getID(), M_READ, 0, '', $expandFolderTree == 2, $orderby);

		if ($enableDropUpload && $folder->getAccessMode($user) >= M_READWRITE) {
			echo "SeedDMSUpload.setUrl('../op/op.Ajax.php');";
			echo "SeedDMSUpload.setAbortBtnLabel('".getMLText("cancel")."');";
			echo "SeedDMSUpload.setEditBtnLabel('".getMLText("edit_document_props")."');";
			echo "SeedDMSUpload.setMaxFileSize(".SeedDMS_Core_File::parse_filesize(ini_get("upload_max_filesize")).");";
			echo "SeedDMSUpload.setMaxFileSizeMsg('".getMLText("uploading_maxsize")."');";
		}

		$this->printDeleteFolderButtonJs();
		$this->printDeleteDocumentButtonJs();
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$folder = $this->params['folder'];
		$orderby = $this->params['orderby'];
		$enableFolderTree = $this->params['enableFolderTree'];
		$enableClipboard = $this->params['enableclipboard'];
		$enableDropUpload = $this->params['enableDropUpload'];
		$expandFolderTree = $this->params['expandFolderTree'];
		$showtree = $this->params['showtree'];
		$cachedir = $this->params['cachedir'];
		$workflowmode = $this->params['workflowmode'];
		$enableRecursiveCount = $this->params['enableRecursiveCount'];
		$maxRecursiveCount = $this->params['maxRecursiveCount'];
		$previewwidth = $this->params['previewWidthList'];
		$timeout = $this->params['timeout'];

		$folderid = $folder->getId();

		$this->htmlAddHeader('<link href="../styles/'.$this->theme.'/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">'."\n", 'css');
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/plugins/datatables/jquery.dataTables.min.js"></script>'."\n", 'js');
		$this->htmlAddHeader('<script type="text/javascript" src="../styles/'.$this->theme.'/plugins/datatables/dataTables.bootstrap.min.js"></script>'."\n", 'js');

		echo $this->callHook('startPage');

		$this->htmlStartPage(getMLText("folder_title", array("foldername" => htmlspecialchars($folder->getName()))), "skin-blue sidebar-mini");

		$this->containerStart();
		$this->mainHeader();
		$this->mainSideBar();

		$previewer = new SeedDMS_Preview_Previewer($cachedir, $previewwidth, $timeout);

		echo $this->callHook('preContent');

		$this->contentStart();		

		echo $this->getFolderPathHTML($folder);

		/*echo "<div class=\"ajax-content\"></div>";

		echo "<button type=\"button\" class=\"btn btn-default btn-lrg ajax\" title=\"Ajax Request\">";
    echo "<i class=\"fa fa-spin fa-refresh\"></i>&nbsp; Get External Content";
    echo "</button>";*/

		// dynamic columns - left column removed if no content and right column then fills span12.

		/*if (!($enableFolderTree || $enableClipboard)) {
			$leftDiv = 0;
			$rightDiv = 12;
		} else {
			$leftDiv = 4;
			$rightDiv = 8;
		}

		// Print folder tree // <----------------------------------------------------/
		if ($leftDiv > 0) {

			echo "<div class=\"col-md-".$leftDiv."\">";
			echo "<div class=\"box box-success box-solid\">";
			echo "<div class=\"box-header with-border\">";
			echo "<h3 class=\"box-title\">".getMLText("folderTree")."</h3>";
			echo "<div class=\"box-tools pull-right\">";
      echo "<button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"collapse\"><i class=\"fa fa-minus\"></i>";
      echo "</button>";
      echo "</div>";
      echo "</div>";
      echo "<div class=\"box-body\" style=\"display: block;\">";
			if ($enableFolderTree) {
				if ($showtree==1){
					$this->printNewTreeNavigationHtml($folderid, M_READ, 0, '', $this->params['expandFolderTree'] == 2, $orderby);
				} else {
					$this->contentHeading("<a href=\"../out/out.ViewFolder.php?folderid=". $folderid."&showtree=1\"><i class=\"fa fa-plus-circle\"></i></a>", true);
				}
			}
			echo $this->callHook('leftContent');
			if ($enableClipboard) $this->printClipboard($this->params['session']->getClipboard(), $previewer);

			echo "</div>";
			echo "</div>";
			echo "</div>";
		}*/

		// Print forder info // <----------------------------------------------------/

		/*echo "<div class=\"col-md-".$rightDiv."\">\n";

		// If dropupload is enable
		if ($enableDropUpload && $folder->getAccessMode($user) >= M_READWRITE) {
			echo "<div class=\"col-md-12\">";
			$this->contentHeading(getMLText("dropupload"), true);
			//			$this->addFooterJS("SeedDMSUpload.setUrl('../op/op.Ajax.php');");
			//			$this->addFooterJS("SeedDMSUpload.setAbortBtnLabel('".getMLText("cancel")."');");
			//			$this->addFooterJS("SeedDMSUpload.setEditBtnLabel('".getMLText("edit_document_props")."');");
			//			$this->addFooterJS("SeedDMSUpload.setMaxFileSize(".SeedDMS_Core_File::parse_filesize(ini_get("upload_max_filesize")).");");
			//			$this->addFooterJS("SeedDMSUpload.setMaxFileSizeMsg('".getMLText("uploading_maxsize")."');");
			?>
			<div id="dragandrophandler" class="well alert" data-target="<?php echo $folder->getID(); ?>" data-formtoken="<?php echo createFormKey('adddocument'); ?>"><?php printMLText('drop_files_here'); ?></div>
			<?php
			echo "</div>";
		}

		echo "<div class=\"box box-warning box-solid\">";
		echo "<div class=\"box-header with-border\">";
		echo "<h3 class=\"box-title\">".getMLText("folder_infos")."</h3>";
		echo "<div class=\"box-tools pull-right\">";
    echo "<button type=\"button\" class=\"btn btn-box-tool\" data-widget=\"remove\"><i class=\"fa fa-times\"></i>";
    echo "</button>";
    echo "</div>";
    echo "</div>";
    echo "<div class=\"box-body\" style=\"display: block;\">";

		$txt = $this->callHook('folderInfo', $folder);
		if(is_string($txt))
			echo $txt;
		else {
			$owner = $folder->getOwner();
			echo "<table class=\"table-condensed\">\n";
			echo "<tr>";
			echo "<td>".getMLText("owner").":</td>\n";
			echo "<td><a href=\"mailto:".htmlspecialchars($owner->getEmail())."\">".htmlspecialchars($owner->getFullName())."</a></td>\n";
			echo "</tr>";
			echo "<tr>";
			echo "<td>".getMLText("creation_date").":</td>";
			echo "<td>".getLongReadableDate($folder->getDate())."</td>";
			echo "</tr>";
			if($folder->getComment()) {
				echo "<tr>";
				echo "<td>".getMLText("comment").":</td>\n";
				echo "<td>".htmlspecialchars($folder->getComment())."</td>\n";
				echo "</tr>";
			}

			if($user->isAdmin()) {
				echo "<tr>";
				echo "<td>".getMLText('default_access').":</td>";
				echo "<td>".$this->getAccessModeText($folder->getDefaultAccess())."</td>";
				echo "</tr>";
				if($folder->inheritsAccess()) {
					echo "<tr>";
					echo "<td>".getMLText("access_mode").":</td>\n";
					echo "<td>";
					echo getMLText("inherited")."<br />";
					$this->printAccessList($folder);
					echo "</tr>";
				} else {
					echo "<tr>";
					echo "<td>".getMLText('access_mode').":</td>";
					echo "<td>";
					$this->printAccessList($folder);
					echo "</td>";
					echo "</tr>";
				}
			}
			$attributes = $folder->getAttributes();
			if($attributes) {
				foreach($attributes as $attribute) {
					$arr = $this->callHook('showFolderAttribute', $folder, $attribute);
					if(is_array($arr)) {
						echo $txt;
						echo "<tr>";
						echo "<td>".$arr[0].":</td>";
						echo "<td>".$arr[1].":</td>";
						echo "</tr>";
					} else {
						$attrdef = $attribute->getAttributeDefinition();
			?>
					<tr>
					<td><?php echo htmlspecialchars($attrdef->getName()); ?>:</td>
					<td><?php echo htmlspecialchars(implode(', ', $attribute->getValueAsArray())); ?></td>
					</tr>
			<?php
					}
				}
			}
			echo "</table>\n";
			echo "</div>";
			echo "</div>";
		}*/

		//// Folder content ////

		echo "<div class=\"row\">";
		echo "<div class=\"col-md-12\">\n";
		echo "<div class=\"box box-primary\">";
		echo "<div class=\"box-header with-border\">";
    echo "<h3 class=\"box-title\">".getMLText("folder_contents")."</h3>";
    echo "</div>";
    echo "<div class=\"box-body no-padding\">";
    echo "<div class=\"table-responsive\">";

		$subFolders = $folder->getSubFolders($orderby);
		$subFolders = SeedDMS_Core_DMS::filterAccess($subFolders, $user, M_READ);
		$documents = $folder->getDocuments($orderby);
		$documents = SeedDMS_Core_DMS::filterAccess($documents, $user, M_READ);

		if ((count($subFolders) > 0)||(count($documents) > 0)){
			$txt = $this->callHook('folderListHeader', $folder, $orderby);
			if(is_string($txt))
				echo $txt;
			else {
				print "<table id=\"viewfolder-table\" class=\"table table-hover table-striped\">";
				print "<thead>\n<tr>\n";
				print "<th></th>\n";	
				print "<th>".getMLText("name")."</th>\n";
	//			print "<th>".getMLText("owner")."</th>\n";
				print "<th>".getMLText("status")."</th>\n";
	//			print "<th>".getMLText("version")."</th>\n";
				print "<th>".getMLText("action")."</th>\n";
				print "</tr>\n</thead>\n<tbody>\n";
			}
		}
		else printMLText("empty_folder_list");


		foreach($subFolders as $subFolder) {
			$txt = $this->callHook('folderListItem', $subFolder);
			if(is_string($txt))
				echo $txt;
			else {
				echo $this->folderListRow($subFolder);
			}
		}

		foreach($documents as $document) {
			$document->verifyLastestContentExpriry();
			$txt = $this->callHook('documentListItem', $document, $previewer);
			if(is_string($txt))
				echo $txt;
			else {
				echo $this->documentListRow($document, $previewer);
			}
		}

		if ((count($subFolders) > 0)||(count($documents) > 0)) {
			$txt = $this->callHook('folderListFooter', $folder);
			if(is_string($txt))
				echo $txt;
			else
				echo "</tbody>\n</table>\n";
		}
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>"; // End folder content table		

		echo "</div>\n"; // End of right column div
		echo "</div>\n"; // End of div around left and right column
		echo "</div>\n"; // End of row

		echo $this->callHook('postContent');

		$this->contentEnd();
		$this->mainFooter();		
		$this->containerEnd();
		$this->htmlEndPage();
	} /* }}} */
}

?>
