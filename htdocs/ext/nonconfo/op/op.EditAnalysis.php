<?php
//    Copyright (C) 2017 Multisistemas e Inversiones S.A. de C.V.
// 
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

include("../../../inc/inc.Settings.php");
include("../../../inc/inc.LogInit.php");
include("../../../inc/inc.Utils.php");
include("../../../inc/inc.Language.php");
include("../inc/inc.NonConfoLanguages.php");
include("../../../inc/inc.Init.php");
include("../../../inc/inc.Extension.php");
include("../../../inc/inc.DBInit.php");
include("../../../inc/inc.ClassUI.php");
include("../inc/inc.NonConfoAnalysis.php");
include("../../../inc/inc.Authentication.php");

if ($user->isGuest()) {
	UI::exitError(getMLText("nonconfo_edit_analysis"),getMLText("access_denied"));
}

/* Check if the form data comes from a trusted request */
if(!checkFormKey('editanalysis')) {
	UI::exitError(getMLText("nonconfo_edit_analysis"),getMLText("invalid_request_token"));
}

if (!isset($_POST['description'])) {
	UI::exitError(getMLText("nonconfo_edit_analysis"),getMLText("nonconfo_no_analysis_description")); ////
}

$res = editNonConfoAnalysis($_POST['analysisId'], $_POST['description']);

if (false == $res || empty($res)) {
	UI::exitError(getMLText("nonconfo_edit_analysis"),getMLText("error_occured"));
}

$analysis = getNonConfoAnalysisById($_POST['analysisId']);

$session->setSplashMsg(array('type'=>'success', 'msg'=>getMLText('nonconfo_analysis_modified')));

add_log_line();

header("Location:../out/out.ViewNonConfo.php?nonconfoId=".$analysis['nonconformityId']);

?>