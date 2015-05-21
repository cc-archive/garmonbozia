<?php

/* Garmonbozia - Creative Commons search.
   Based on GNU FM

   Copyright (C) 2015 Creative Commons
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

require('vendor/autoload.php');
require_once('version.php');
require_once('utils/get_absolute_url.php');

if (file_exists('config.php')) {
	die('A configuration file already exists.'
        . ' Please delete <i>config.php</i> if you wish to reinstall.');
}

if (isset($_POST['install'])) {
	$install_path = dirname(__FILE__) . '/';

	$default_theme = $_POST['default_theme'];
	$site_name = addslashes($_POST['site_name']);
	$base_url = $_POST['base_url'];

	if ($base_url[strlen($base_url) - 1] === '/') {
		$base_url = substr($base_url, 0, -1);
	}

	// Write out the configuration
	$config = "<?php\n require('vendor/autoload.php');\n"
            . "\$config_version = " . $version
            . ";\n\$connect_string = '" . $connect_string
            . "';\n\$default_theme = '" . $default_theme
            . "';\n\$site_name = '" . $site_name
            . "';\n\$base_url = '" . $base_url
            . "';\n\$install_path = '" . $install_path
    //TODO: expose these from installer
            . "';\n\$filesystem_cache_path = \$install_path . 'cache"
            . "';\n\$redis_host = '127.0.0.1"
            . "';\n\$redis_port = '6379"
            . "';\n\$redis_password = '"
            . "';\n\$cache_engine = 'redis"
    //TODO: we need a better way of handling these, there will be many
            . "';\n\$flickr_api_key = '"
            . "';\n";

	$conf_file = fopen('config.php', 'w');
	$result = fwrite($conf_file, $config);
	fclose($conf_file);

	if (!$result) {
		$print_config = str_replace('<', '&lt;', $config);
		die('Unable to write to file \'<i>config.php</i>\'.'
          . ' Please create this file and copy the following in to it:'
          . '<br /><pre>' . $print_config . '</pre>');
	}

	die('Configuration completed successfully!');
}

?>
<html>
	<head>
		<title>Installer</title>
	</head>

	<body>
		<h1>Installer</h1>


		<form method="post">
			<h2>General</h2>
            Site Name: <input type="text" name="site_name" value="My Site"
                         /><br />
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
            Base URL: <input type="text" name="base_url"
            value="<?php echo getAbsoluteURL(); ?>" /><br />
			<br /><br />
			<input type="submit" value="Install" name="install" />
		</form>
	</body>
</html>
