<?php
//    MyDMS. Document Management System
//    Copyright (C) 2002-2005  Markus Westphal
//    Copyright (C) 2006-2008 Malcolm Cowe
//    Copyright (C) 2010-2016 Uwe Steinmann
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

include("../inc/inc.Settings.php");
include("../inc/inc.LogInit.php");
include("../inc/inc.Utils.php");
include("../inc/inc.Language.php");
include("../inc/inc.Init.php");
include("../inc/inc.Extension.php");
include("../inc/inc.DBInit.php");
include("../inc/inc.ClassUI.php");
include("../inc/inc.ClassController.php");
include("../inc/inc.Authentication.php");

$tmp = explode('.', basename($_SERVER['SCRIPT_FILENAME']));
$controller = Controller::factory($tmp[1]);

if (!isset($_POST["folderid"]) || !is_numeric($_POST["folderid"]) || intval($_POST["folderid"])<1) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_folder_id"))),getMLText("invalid_folder_id"));
}

$folderid = $_POST["folderid"];
$folder = $dms->getFolder($folderid);

if (!is_object($folder)) {
	UI::exitError(getMLText("folder_title", array("foldername" => getMLText("invalid_folder_id"))),getMLText("invalid_folder_id"));
}

$folderPathHTML = getFolderPathHTML($folder, true);

if ($folder->getAccessMode($user) < M_READWRITE) {
	UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())),getMLText("access_denied"));	
}

$name    = $_POST["name"];
$comment = $_POST["comment"];
if(isset($_POST["sequence"])) {
	$sequence = str_replace(',', '.', $_POST["sequence"]);
	if (!is_numeric($sequence)) {
		$sequence = "keep";
	}
} else {
	$sequence = "keep";
}
if(isset($_POST["attributes"]))
	$attributes = $_POST["attributes"];
else
	$attributes = array();

$oldname = $folder->getName();
$oldcomment = $folder->getComment();
$oldattributes = $folder->getAttributes();

$controller->setParam('folder', $folder);
$controller->setParam('name', $name);
$controller->setParam('comment', $comment);
$controller->setParam('sequence', $sequence);
$controller->setParam('attributes', $attributes);
if(!$controller->run()) {
	if($controller->getErrorNo()) {
		UI::exitError(getMLText("folder_title", array("foldername" => $folder->getName())), $controller->getErrorMsg());
	}
}

if($oldname != $name) {
	// Send notification to subscribers.
	if($notifier) {
		$notifyList = $folder->getNotifyList();

		$subject = "folder_renamed_email_subject";
		$message = "folder_renamed_email_body";
		$params = array();
		$params['name'] = $folder->getName();
		$params['old_name'] = $oldname;
		$params['folder_path'] = $folder->getFolderPathPlain();
		$params['username'] = $user->getFullName();
		$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."out/out.ViewFolder.php?folderid=".$folder->getID();
		$params['sitename'] = $settings->_siteName;
		$params['http_root'] = $settings->_httpRoot;
		$notifier->toList($user, $notifyList["users"], $subject, $message, $params);
		foreach ($notifyList["groups"] as $grp) {
			$notifier->toGroup($user, $grp, $subject, $message, $params);
		}
		// if user is not owner send notification to owner
//		if ($user->getID() != $folder->getOwner()->getID()) 
//			$notifier->toIndividual($user, $folder->getOwner(), $subject, $message, $params);
	}
}

if($oldcomment != $comment) {
	// Send notification to subscribers.
	if($notifier) {
		$notifyList = $folder->getNotifyList();

		$subject = "folder_comment_changed_email_subject";
		$message = "folder_comment_changed_email_body";
		$params = array();
		$params['name'] = $folder->getName();
		$params['folder_path'] = $folder->getFolderPathPlain();
		$params['old_comment'] = $oldcomment;
		$params['comment'] = $comment;
		$params['username'] = $user->getFullName();
		$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."out/out.ViewFolder.php?folderid=".$folder->getID();
		$params['sitename'] = $settings->_siteName;
		$params['http_root'] = $settings->_httpRoot;
		$notifier->toList($user, $notifyList["users"], $subject, $message, $params);
		foreach ($notifyList["groups"] as $grp) {
			$notifier->toGroup($user, $grp, $subject, $message, $params);
		}
		// if user is not owner send notification to owner
//		if ($user->getID() != $folder->getOwner()->getID()) 
//			$notifier->toIndividual($user, $folder->getOwner(), $subject, $message, $params);

	}
}

$session->setSplashMsg(array('type'=>'success', 'msg'=>getMLText('splash_folder_edited')));

add_log_line("?folderid=".$folderid);

header("Location:../out/out.ViewFolder.php?folderid=".$folderid."&showtree=".$_POST["showtree"]);

?>
