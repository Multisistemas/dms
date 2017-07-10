<?php
include("../../inc/inc.Settings.php");
include("../../inc/inc.Language.php");
include("../../inc/inc.Init.php");
include("../../inc/inc.ClassUI.php");

//global $dms;
var_dump($_FILES);	
exit;

if (isset($_POST["command"])) {
	$target_dir = $settings->_rootDir."/views/multisis-lte/images/";
	switch ($_POST["command"]) {
	case 'validateLogo':
		var_dump($_FILES["logofile"]["name"]);
		var_dump(pathinfo_extension($_FILES["logofile"]["name"]));

		if (pathinfo_extension($_FILES["logofile"]["name"]) == "png") {

			//TODO : Validate the image size
			//$userfiletmp = $_FILES["logofile"]["tmp_name"][0];
			//var_dump(filesize($userfiletmp));
			var_dump($_FILES);	
			var_dump($_FILES["logofile"]["name"]);
			var_dump($settings->_rootDir);
			exit;

			$target_file = $target_dir . basename($_FILES["logofile"]["name"]);
			$upload_result = move_uploaded_file($_FILES["logofile"]["tmp_name"], $target_file);
			$change_name = rename($target_file, $target_dir."logo.png");
			var_dump($change_name);
			exit;

			header("Location:../../out/out.ViewFolder.php?folderid=1");
			
		} else {
			UI::exitError(getMLText("error_ocurred"));
		}

		/*if (true) {
			$target_dir = $folder->_dms->contentDir.$documentid."/";

			$target_file = $target_dir . basename($_FILES["filename"]["name"][0]);

			$upload_result = move_uploaded_file($_FILES["filename"]["tmp_name"][0], $target_file);

			$change_name = rename($target_file, $target_dir.$version->getVersion().$version->_fileType);

			header("Location:../out/out.ViewFolder.php?folderid=1");

		}*/
		break;
	case 'validateBrand':

		break;
	}	
}

/**
 * Check if the $_FILES[][name] is a valid name
 *
 * @param (string) $filename - Uploaded file name.
 */
function check_file_uploaded_name($filename) {
	return (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
}

/**
 * Check $_FILES[][name] length.
 *
 * @param (string) $filename - Uploaded file name.
 */
function check_file_uploaded_length($filename) {
  return (bool) ((mb_strlen($filename,"UTF-8") > 225) ? true : false);
}

/**
 * Returns the file extension.
 *
 * @param file - Uploaded file.
 */
function pathinfo_extension($file) {
	if (defined('PATHINFO_EXTENSION')) {
		return pathinfo($file,PATHINFO_EXTENSION);
	}
}

?>