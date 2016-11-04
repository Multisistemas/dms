<?php
//    SeedDMS (Formerly MyDMS) Document Management System
//    Copyright (C) 2002-2005  Markus Westphal
//    Copyright (C) 2006-2008 Malcolm Cowe
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

// Added to include pear packages in A2hosting
if (file_exists('/home/multisis/php') && is_dir('/home/multisis/php')) {
	ini_set("include_path", '/home/multisis/php:' . ini_get("include_path"));
}

if (file_exists('/home/multisis/zf/library') && is_dir('/home/multisis/zf/library')) {
        ini_set("include_path", '/home/multisis/zf/library:' . ini_get("include_path"));
}

include("inc/inc.Settings.php");

header("Location: ". (isset($settings->_siteDefaultPage) && strlen($settings->_siteDefaultPage)>0 ? $settings->_siteDefaultPage : "out/out.ViewFolder.php"));
?>
<html>
<head>
	<title>Multisistemas DMS</title>
</head>

<body>


</body>
</html>
