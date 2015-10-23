<?php

/*   copy-theme-resources.php
     Copy theme resources (but not templates) to the public directory.

     Copyright (C) 2015 Creative Commons

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

$sourceroot = dirname(__DIR__)."/application/themes/";
$destroot = dirname(__DIR__)."/public/themes/";
$matchregex = '/\.(css|gif|jpg|jpeg|js|png)$/';

echo "\e[32mCopying theme resources to public directory\e[0m\n";

$iterator = new \RecursiveIteratorIterator(
    new \RecursiveDirectoryIterator($sourceroot,
                                    \RecursiveDirectoryIterator::SKIP_DOTS),
    \RecursiveIteratorIterator::SELF_FIRST);

foreach ($iterator as $item) {
    if (! $item->isDir()) {
        // Only copy resource files (images, scripts, css)
        if (preg_match($matchregex, $item)) {
            $destitempath = $destroot
                          . DIRECTORY_SEPARATOR
                          . $iterator->getSubPathName();
            // Create only those directories we will copy to
            $destitemdir = dirname($destitempath);
            if(! file_exists($destitemdir)) {
                mkdir($destitemdir, 0777, true);
            }
            copy($item, $destitempath);
        }
    }
}
