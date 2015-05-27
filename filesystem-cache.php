<?php

  /* Search caching

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

require_once('config.php');
require_once('cache.php');

define('CACHE_IMPLEMENTATION', 'filesystem');

function filepath ($query, $source, $type, $license) {
    global $filesystem_cache_path;
    $identifier = identifier_for_query($query, $source, $type, $license);
    return $filesystem_cache_path . $type . '/' . $identifier . ".json";
}

function cache_search_results ($results, $query, $source, $type, $license,
                               $count) {
    global $filesystem_cache_path;
    $type_cache_path = $filesystem_cache_path . $type;
    if (! file_exists($type_cache_path)) {
        mkdir($type_cache_path);
    }

    $data = json_encode(array_values($results));

    try {
        $filename = filepath($query, $source, $type, $license);
        file_put_contents($filename, $data);
    }
    finally {
    }

    return $results;
}

function search_results_from_cache ($query, $source, $type, $license, $count) {
    $filename = filepath($query, $source, $type, $license);
    $result = null;
    if (file_exists($filename)) {
        $text = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
        $result = json_decode($text, true);
    }
    return $result;
}
