<?php

/* GNU FM -- a free network service for sharing your music listening habits

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

require_once('adodb/adodb-exceptions.inc.php');
require_once('adodb/adodb.inc.php');
require_once('version.php');
require_once('utils/get_absolute_url.php');

if (file_exists('config.php')) {
	die('A configuration file already exists. Please delete <i>config.php</i> if you wish to reinstall.');
}

if (isset($_POST['install'])) {

	$install_path = dirname(__FILE__) . '/';

	$default_theme = $_POST['default_theme'];
	$site_name = addslashes($_POST['site_name']);
	$base_url = $_POST['base_url'];

	if ($base_url[strlen($base_url) - 1] === '/') {
		$base_url = substr($base_url, 0, -1);
	}

	$submissions_server = $_POST['submissions_server'];

	//Write out the configuration
	$config = "<?php\n \$config_version = " . $version .";\n \$connect_string = '" . $connect_string . "';\n \$default_theme = '" . $default_theme . "';\n \$site_name = '" . $site_name . "';\n \$base_url = '" . $base_url . "';\n \$submissions_server = '" . $submissions_server . "';\n \$install_path = '" . $install_path . "';\n \$adodb_connect_string = '" . $adodb_connect_string . "';\n \$gnufm_key = 'default_gnufm_32_char_identifier'; ";

	$conf_file = fopen('config.php', 'w');
	$result = fwrite($conf_file, $config);
	fclose($conf_file);

	if (!$result) {
		$print_config = str_replace('<', '&lt;', $config);
		die('Unable to write to file \'<i>config.php</i>\'. Please create this file and copy the following in to it: <br /><pre>' . $print_config . '</pre>');
	}

	die('Configuration completed successfully!');
}

?>
<html>
	<head>
		<title>Installer</title>
	</head>

	<body onload="showSqlite()">
		<h1>Installer</h1>


		<form method="post">
			<h2>General</h2>
			Site Name: <input type="text" name="site_name" value="My Site" /><br />
			Default Theme: <select name="default_theme">
			<?php
				$dir = opendir('themes');
				while ($theme = readdir($dir)) {
					if (is_dir('themes/' . $theme) && $theme[0] != '.') {
						echo '<option>' . $theme . '</option>';
					}
				}
			?>
			</select><br />
			Base URL: <input type="text" name="base_url" value="<?php echo getAbsoluteURL(); ?>" /><br />
			Submissions Server: <input type="text" name="submissions_server" /> (URL to your gnukebox install)<br />
			<br /><br />
			<input type="submit" value="Install" name="install" />
		</form>
	</body>
</html>
