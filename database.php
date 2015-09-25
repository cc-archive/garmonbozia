<?php

/* Garmonbozia - Creative Commons search.

   Based on GNU FM.

   Copyright (C) 2014, 2015 Creative Commons
   Copyright (C) 2009 Free Software Foundation, Inc

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

namespace Garmonbozia;

if (!file_exists(dirname(__FILE__) . '/config.php')) {
	die('Please run the <a href="install.php">Install</a> script to configure your installation');
}

require_once('config.php');

global $db;

try {
	$db = new \PDO(Config::$connect_string);
} catch (\PDOException $e) {
	// die("Unable to connect to database.");
}

/**
 * Write error to Error database table
 *
 * @param string msg Message
 * @param string data Data
 * @return null
 */
/*function reportError($msg, $data) {
	global $db;

	$adodb->Execute('INSERT INTO Error(msg, data, time) VALUES('
		. $adodb->qstr($msg) . ', '
		. $adodb->qstr($data) . ', '
		. time() . ')');
}
*/
