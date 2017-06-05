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
include("../../../inc/inc.Authentication.php");
include("../inc/inc.Nonconformities.php");
include("../inc/inc.NonConfoAnalysis.php");
include("../inc/inc.NonConfoResponsibles.php");
include("../inc/inc.FollowAction.php");
include("../inc/inc.NonConfoAction.php");

if ($user->isGuest()) {
	UI::exitError(getMLText("nonconfo_delete_action"),getMLText("access_denied"));
}

if(!checkFormKey('deletenonconfo', 'GET')) {
	UI::exitError(getMLText("nonconfo_title"),getMLText("invalid_request_token"));
}

if(!isset($_REQUEST['id'])){
	UI::exitError(getMLText("nonconfo_title"),getMLText("nonconfo_id_error"));
}

header("Cache-Control: no-cache,no-store");

$nonconfo = getNonconformity($_REQUEST['id']);

$analysis = getNonConfoAnalysisByNonconfoId($_REQUEST['id']);

$actions = getNonConfoActions($_REQUEST['id']);

if (false != $nonconfo) {
	if (false != $analysis){
		if ($actions != 0 || $actions != false) {
			for ($i=0; $i < count($actions); $i++) { 
				$follow = getActionFollowById($actions[$i]['id']);
				if (false != $follow) {
					delActionFollow($follow['id']);
				}
			}

			for ($i=0; $i < count($actions); $i++) {
				delNonconfoAction($actions[$i]['id']);
			}
		}

		if ($analysis['fileName'] != "") {
			$thefile = $settings->_contentDir . 'nonconfo/'. $analysis['fileName'];
			if (file_exists($thefile)) {
			 $ret =	unlink($thefile);
			}
		}
		
		delNonconfoAnalysis($analysis['id']);

			
	}

	$responsibles = getNonConfoResponsible($nonconfo['id']);
		if (false != $responsibles){
			for ($i=0; $i < count($responsibles); $i++) { 
				delNonConfoResponsible($responsibles[$i]['id']);
		}	
	}

	$res = delNonconformities($_REQUEST['id']);
	if (is_bool($res) && !$res) {
		header('Content-Type: application/json');
		echo json_encode(array('success'=>false, 'message'=>getMLText('error_occured'), 'data'=>''));
	}

	header('Content-Type: application/json');
	echo json_encode(array('success'=>true, 'message'=>getMLText('nonconfo_delete_success'), 'data'=>''));
}







