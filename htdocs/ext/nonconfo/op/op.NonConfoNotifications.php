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

function sendNotificationNonconfoAdded($id, $nonconfoId){
	global $db, $dms, $user, $notifier, $settings;

	$sender = "dms@gestiontotal.net";
	$user = $dms->getUser($id);
	$recipient = $user->_email;

	$subject = "nonconfo_request_review_email_subject";
	$message = "nonconfo_request_email_body";
	$params = array();
	$params['nonconfo_request'] = 'New non conformity added';
	$params['url'] = "http".((isset($_SERVER['HTTPS']) && (strcmp($_SERVER['HTTPS'],'off')!=0)) ? "s" : "")."://".$_SERVER['HTTP_HOST'].$settings->_httpRoot."ext/nonconfo/out/out.ViewNonConfo.php?nonconfoId".$nonconfoId;
	$res = $notifier->toIndividual($sender, $user, $subject, $message, $params);

	return $res;
}