<?php
include("../inc/inc.ClassSettings.php");

function usage() { /* {{{ */
	echo "Usage:\n";
	echo "  seeddms-importmail [--config <file>] [-h] [-v] -F <folder id> -d <dirname>\n";
	echo "\n";
	echo "Description:\n";
	echo "  This program accesses a mail box via imap and imports all mail attachments.\n";
	echo "\n";
	echo "Options:\n";
	echo "  -h, --help: print usage information and exit.\n";
	echo "  -v, --version: print version and exit.\n";
	echo "  -t: just check for mails without import.\n";
	echo "  --config: set alternative config file.\n";
	echo "  --password: set password for imap access ('-' for stdin).\n";
	echo "  --type: set type of message to extract (can be set more than once).\n";
	echo "    Common types are:\n";
	echo "    JPEG: jpeg images\n";
	echo "    PNG: png images\n";
	echo "    GIF: gif images\n";
	echo "    TIFF: tiff images\n";
	echo "    MPEG: mpeg files (e.g. mp3)\n";
 	echo "    PDF: PDF files\n";
 	echo "    VND.OPENXMLFORMATS-OFFICEDOCUMENT.SPREADSHEETML.SHEET: Excel xml files\n";
 	echo "    VND.OPENXMLFORMATS-OFFICEDOCUMENT.WORDPROCESSINGML.DOCUMENT: MS Word xml files\n";
 	echo "    X-XZ: xz compressed files\n";
 	echo "    GZIP: gzip compressed files\n";
 	echo "    ZIP: zip compressed files\n";
 	echo "    VND.DEBIAN.BINARY-PACKAGE: debian package files\n";
 	echo "    VND.OASIS.OPENDOCUMENT.TEXT: Open document type files\n";
 	echo "    RTF: rtf files\n";
 	echo "    MSWORD: MS Word doc files\n";
 	echo "    PGP-SIGNATURE: pgp signatures\n";
	echo "  -m, --mode: can be 'all', 'new', 'unseen', 'today', 'since:<date>', 'on:<date>\n";
	echo "    <date> can be any parsable date by strtotime(), e.g. -3days\n";
	echo "  -F <folder id>: id of folder the file is uploaded to\n";
	echo "  --subfolder: create subfolder for each mail\n";
	echo "  --delete: delete mails after successful import (default is to set 'seen')\n";
	echo "  --attribute: <header>:<attribute name>\n";
	echo "    save mail header field into attribute. If --subfolder is set, the header fields are saved in\n";
	echo "    attributes attached to the folder of the mail, otherwise they are attached to the documents\n";
	echo "    containing the attachment.\n";
	echo "    <header> is the name of the mail header field in lower case (e.g. subject, toaddress, fromaddress, date, message_id)\n";
	echo "    <attribute_name> is the name of the attribute\n";
	echo "\n";
	echo "Example:\n";
	echo "  seeddms-importmail --config=/home/www-data/seeddms/conf/settings.xml --password - --mode=\"since:-2 days\" -F 8376 --subfolder --attribute=\"toaddress:Mail: To\" --attribute=\"fromaddress:Mail: From\" --attribute=\"subject:Mail: Subject\" --attribute=\"date:Mail: Date\" --type=\\!PGP-SIGNATURE\n";
	echo "\n";
	echo "  This will import all attachments of mails received within the last two days. It creates\n";
	echo "  a folder for each mail (below folder 8376) and puts all attachments into it.\n";
	echo "  The folder will get 4 attributes containing the subject, fromaddress, toaddress and date.\n";
	echo "  The attributes have to exist already. Attachments of type PGP-SIGNATURE will not be imported.\n";
	echo "  The password for the mailbox is read from stdin.\n";
} /* }}} */

$g_config = array();
$version = "0.0.1";
$shortoptions = "d:F:p:m:thv";
$longoptions = array('help', 'version', 'config:', 'password:','type::', 'mode:', 'delete', 'subfolder', 'attribute:');
if(false === ($options = getopt($shortoptions, $longoptions))) {
	usage();
	exit(0);
}

/* Print help and exit */
if(!$options || isset($options['h']) || isset($options['help'])) {
	usage();
	exit(0);
}

/* Print version and exit */
if(isset($options['v']) || isset($options['verѕion'])) {
	echo $version."\n";
	exit(0);
}

/* Check for test mode */
$g_config['dryrun'] = false;
if(isset($options['t'])) {
	$g_config['dryrun'] = true;
}

/* Create subfolder */
$g_config['createsubfolder'] = false;
if(isset($options['subfolder'])) {
	$g_config['createsubfolder'] = true;
}

/* Check for test mode */
$g_config['deletemails'] = false;
if(isset($options['delete'])) {
	$g_config['deletemails'] = true;
}

/* set mode for retrieving emails */
if(isset($options['m']) || isset($options['mode'])) {
	if(isset($options['m']))
		$g_config['mode'] = $options['m'];
	else
		$g_config['mode'] = $options['mode'];
}

/* set attributes being set from mail header */
$g_config['attributes'] = array();
if(isset($options['attribute'])) {
	if(is_string($options['attribute']))
		$g_config['attributes'][] = $options['attribute'];
	else
		$g_config['attributes'] = $options['attribute'];
}

$g_config['inc_subtypes'] = array();
$g_config['exc_subtypes'] = array();
if(isset($options['type'])) {
	if(is_string($options['type'])) {
		if($options['type'][0] == '!')
			$g_config['exc_subtypes'][] = substr($options['type'], 1);
		else
			$g_config['inc_subtypes'][] = $options['type'];
	} else {
		foreach($options['type'] as $type) {
			if($type[0] == '!')
				$g_config['exc_subtypes'][] = substr($type, 1);
			else
				$g_config['inc_subtypes'][] = $type;
		}
	}
}

/* Set alternative config file */
if(isset($options['config'])) {
	$settings = new Settings($options['config']);
} else {
	$settings = new Settings();
}

/* Set alternative config file */
if(isset($options['p']) || isset($options['password'])) {
	$password = isset($options['p']) ? $options['p'] : (isset($options['password']) ? $options['password'] : '');
	if($password == '-') {
		$oldStyle = shell_exec('stty -g');
		echo "Please enter password: ";
		shell_exec('stty -echo');
		$line = fgets(STDIN);
		$password = rtrim($line);
		shell_exec('stty ' . $oldStyle);
		echo "\n";
	}
} else {
	$password = '';
}

if(isset($settings->_extraPath))
	ini_set('include_path', $settings->_extraPath. PATH_SEPARATOR .ini_get('include_path'));

require_once("SeedDMS/Core.php");

if(isset($options['F'])) {
	$folderid = (int) $options['F'];
} else {
	if(!$g_config['dryrun']) {
		echo "Missing folder ID\n";
		usage();
		exit(1);
	}
}

$db = new SeedDMS_Core_DatabaseAccess($settings->_dbDriver, $settings->_dbHostname, $settings->_dbUser, $settings->_dbPass, $settings->_dbDatabase);
$db->connect() or die ("Could not connect to db-server \"" . $settings->_dbHostname . "\"");
$db->_debug = 1;


$dms = new SeedDMS_Core_DMS($db, $settings->_contentDir.$settings->_contentOffsetDir);
if(!$dms->checkVersion()) {
	echo "Database update needed.";
	exit;
}

$dms->setRootFolderID($settings->_rootFolderID);
$dms->setMaxDirID($settings->_maxDirID);
$dms->setEnableConverting($settings->_enableConverting);
$dms->setViewOnlineFileTypes($settings->_viewOnlineFileTypes);

/* Create a global user object */
$g_config['dms_user'] = $dms->getUser(1);

/* Check if import folder exists and is writable */
$dms_folder = $dms->getFolder($folderid);
if (!is_object($dms_folder)) {
	echo "Could not find specified folder\n";
	exit(1);
}

if ($dms_folder->getAccessMode($g_config['dms_user']) < M_READWRITE) {
	echo "Not sufficient access rights\n";
	exit(1);
}
$g_config['dms_folder'] = $dms_folder;

/* Check if all attributes exist */
$g_config['attrmap'] = array();
if($g_config['attributes']) {
	foreach($g_config['attributes'] as $tmp) {
		$hdr_attr = explode(':', $tmp, 2);
		if(count($hdr_attr) == 2) {
			if(in_array($hdr_attr[0], array('subject', 'toaddress', 'fromaddress', 'date', 'message_id'))) {
				if($attrdef = $dms->getAttributeDefinitionByName($hdr_attr[1])) {
					if($g_config['createsubfolder']) {
						$ot = $attrdef->getObjType() == SeedDMS_Core_AttributeDefinition::objtype_all || $attrdef->getObjType() == SeedDMS_Core_AttributeDefinition::objtype_folder;
					} else {
						$ot = $attrdef->getObjType() == SeedDMS_Core_AttributeDefinition::objtype_all || $attrdef->getObjType() == SeedDMS_Core_AttributeDefinition::objtype_document;
					}
					$t = $attrdef->getType() == SeedDMS_Core_AttributeDefinition::type_string;
					if($ot) {
						if($t) {
							$g_config['attrmap'][strtolower($hdr_attr[0])] = $attrdef;
						} else {
							echo "Attribute '".$hdr_attr[1]."' has incorrect type\n";
						}
					} else {
						echo "Attribute '".$hdr_attr[1]."' has incorrect object type\n";
					}
				} else {
					echo "Unknown attribute name '".$hdr_attr[1]."' in attribute mapping\n";
				}
			} else {
				echo "Unknown header field '".strtolower($hdr_attr[0])."' in attribute mapping\n";
			}
		}
	}
}

function getpart($mbox,$mid,$p,$partno) { /* {{{ */
	// $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
	global $g_config, $attachments;

	$htmlmsg = $plainmsg = '';
	if((!isset($g_config['inc_subtypes']) && !isset($g_config['exc_subtypes'])) || (!$g_config['inc_subtypes'] && !$g_config['exc_subtypes']) || in_array($p->subtype, $g_config['inc_subtypes']) || !in_array($p->subtype, $g_config['exc_subtypes'])) {
		// DECODE DATA
		$data = ($partno)?
			imap_fetchbody($mbox,$mid,$partno):  // multipart
			imap_body($mbox,$mid);  // simple
		// Any part may be encoded, even plain text messages, so check everything.
		if ($p->encoding==4)
			$data = quoted_printable_decode($data);
		elseif ($p->encoding==3)
			$data = base64_decode($data);

		// PARAMETERS
		// get all parameters, like charset, filenames of attachments, etc.
		$params = array();
		if (isset($p->parameters))
			foreach ($p->parameters as $x)
				$params[strtolower($x->attribute)] = $x->value;
		if (isset($p->dparameters))
			foreach ($p->dparameters as $x)
				$params[strtolower($x->attribute)] = $x->value;

		// ATTACHMENT
		// Any part with a filename is an attachment,
		// so an attached text file (type 0) is not mistaken as the message.
		if (isset($params['filename']) || isset($params['name'])) {
			// filename may be given as 'Filename' or 'Name' or both
			$filename = (!empty($params['filename'])) ? $params['filename'] : $params['name'];
			// filename may be encoded, so see imap_mime_header_decode()
			$attachments[] = array('filename'=>$filename, 'type'=>$p->subtype, 'data'=>$data);  // this is a problem if two files have same name
		}

		// TEXT
		if ($p->type==0 && $data) {
			// Messages may be split in different parts because of inline attachments,
			// so append parts together with blank row.
			if (strtolower($p->subtype)=='plain')
				$plainmsg .= trim($data) ."\n\n";
			else
				$htmlmsg .= $data ."<br><br>";
			$charset = $params['charset'];  // assume all parts are same charset
		}

		// EMBEDDED MESSAGE
		// Many bounce notifications embed the original message as type 2,
		// but AOL uses type 1 (multipart), which is not handled here.
		// There are no PHP functions to parse embedded messages,
		// so this just appends the raw source to the main message.
		elseif ($p->type==2 && $data) {
			$plainmsg .= $data."\n\n";
		}
	}

	// SUBPART RECURSION
	if (isset($p->parts)) {
		foreach ($p->parts as $partno0=>$p2)
			getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
	}
} /* }}} */

/**
 * Convert a quoted printable and converts it into utf-8
 *
 * @param string $item
 * @return string recoded string in utf-8
 */
function rfc2047_decode($item) { /* {{{ */
	$text = '';
	$elements = imap_mime_header_decode($item);
	foreach ($elements as $element) {
		switch(strtoupper($element->charset)) {
		case 'DEFAULT':
		case 'UTF-8':
			$text .= $element->text;
			break;
		default:
			$text .= iconv($element->charset, 'UTF-8', $element->text);
		}
	}
	return $text;
} /* }}} */

function import_mail($mbox, $msgids) { /* {{{ */
	global $g_config;
	global $attachments;

	$attachments = array();

	echo "Processing ".count($msgids)." mails\n";
	foreach($msgids as $mid) {
		$attachments = array();
		$header = imap_header($mbox, $mid);
		echo "\n";
		echo "Reading msg ".$header->message_id."\n";
		echo " Subject: ".rfc2047_decode($header->subject)."\n";
		echo " From: ".rfc2047_decode($header->fromaddress)."\n";
		echo " To: ".rfc2047_decode($header->toaddress)."\n";
		echo " Date: ".$header->date."\n";
//			print_r($header);
		$s = imap_fetchstructure($mbox,$mid);
//			print_r($s);
		if($s) {
			if (!isset($s->parts))  // simple
				getpart($mbox,$mid,$s,0);  // pass 0 as part-number
			else {  // multipart: cycle through each part
				foreach ($s->parts as $partno0=>$p)
					getpart($mbox,$mid,$p,$partno0+1);
			}
			if($attachments) {
				echo " Attachments:\n";
				$sequence = 1;
				$f = null;
				if(!$g_config['dryrun']) {
					if($g_config['createsubfolder']) {
						$attributes = array();
						if($g_config['attrmap']) {
							foreach($g_config['attrmap'] as $hdrfield=>$attrdef) {
								$attributes[$attrdef->getID()] = rfc2047_decode($header->{$hdrfield});
							}
						}
						$f = $g_config['dms_folder']->addSubFolder(rfc2047_decode($header->subject), '', $g_config['dms_user'], 0, $attributes);

					} else {
						$f = $g_config['dms_folder'];
					}
				} else {
					$f = true; // fake $f, to list at least the attachments
				}
				if($f) {
					foreach($attachments as $attachment) {
						echo "  ".$attachment['type'].": ".rfc2047_decode($attachment['filename'])." ";

						if(!$g_config['dryrun']) {
							$name = rfc2047_decode($attachment['filename']);
							$filetmp = tempnam(sys_get_temp_dir(), 'IMPMAIL');
							file_put_contents($filetmp, $attachment['data']);

							$reviewers = array();
							$approvers = array();
							$comment = '';
							$version_comment = '';
							$reqversion = 1;
							$expires = false;
							$keywords = '';
							$categories = array();
							$attributes = array();
							if(!$g_config['createsubfolder']) {
								if($g_config['attrmap']) {
									foreach($g_config['attrmap'] as $hdrfield=>$attrdef) {
										$attributes[$attrdef->getID()] = rfc2047_decode($header->{$hdrfield});
									}
								}
								$f = $g_config['dms_folder']->addSubFolder(rfc2047_decode($header->subject), '', $g_config['dms_user'], 0, $attributes);

							}

							$finfo = finfo_open(FILEINFO_MIME_TYPE);
							$mimetype = finfo_file($finfo, $filetmp);
							$lastDotIndex = strrpos($name, ".");
							if (is_bool($lastDotIndex) && !$lastDotIndex) $filetype = ".";
							else $filetype = substr($name, $lastDotIndex);

							$res = $f->addDocument($name, $comment, $expires, $g_config['dms_user'], $keywords,
																					$categories, $filetmp, $name,
																					$filetype, $mimetype, $sequence, $reviewers,
																					$approvers, $reqversion, $version_comment, $attributes);

							if (is_bool($res) && !$res) {
								echo "(could not be added)";
							} else {
								echo "(added)";
							}
							$sequence++;
						}
						echo "\n";
					}
				}
				if(!$g_config['dryrun'] && $g_config['deletemails'])
					imap_delete($mbox, $mid);
			}
		}
	}
} /* }}} */

$host = 'mail.mmk-hagen.de';
$port = '993';
$ssl = 'ssl/novalidate-cert';
$folder = 'seeddms';
$urn = "{"."$host:$port/imap/$ssl"."}$folder";
$user = 'steinm';

//echo $urn;
$options = 0;
if($g_config['dryrun'])
	$options = OP_READONLY;
$mbox = imap_open($urn, $user, $password, $options);
if(!$mbox) {
	echo "Error opening Mailbox\n";
	exit;
}
switch($g_config['mode']) {
case 'today':
	$msgids = imap_search($mbox, 'ON '.date('d-M-Y'));
	break;
case 'unseen':
	$msgids = imap_search($mbox, 'UNSEEN');
	break;
case 'new':
	$msgids = imap_search($mbox, 'NEW');
	break;
default:
	if(substr($g_config['mode'], 0, 6) == 'since:') {
		$tmp = explode(':', $g_config['mode'], 2);
		if($tt = strtotime($tmp[1])) {
			$msgids = imap_search($mbox, 'SINCE '.date('d-M-Y', $tt));
		}
	} elseif(substr($g_config['mode'], 0, 3) == 'on:') {
		$tmp = explode(':', $g_config['mode'], 2);
		if($tt = strtotime($tmp[1])) {
			$msgids = imap_search($mbox, 'ON '.date('d-M-Y', $tt));
		}
	} else {
		$msgids = imap_search($mbox, 'ALL');
	}
}
if($msgids) {
	import_mail($mbox, $msgids);
	imap_expunge($mbox);
}
imap_close($mbox);

