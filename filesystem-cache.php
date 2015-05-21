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
require_once('data/Items.php');
require_once('cache.php');

function cache_search_results ($filename, $query, $type, $license, $count) {
    if (! file_exists($type)) {
        mkdir($filesystem_cache_path . '/' . $type);
    }

    $item = new Item;
    $results = $item->get_results($query, $type, $license, $count);
    $data = json_encode(array_values($results));

    try {
        file_put_contents($filename, $data);
    }
    finally {
    }

    return $results;
}

function search_results_from_cache ($filename) {
    $text = file_get_contents($filename, FILE_USE_INCLUDE_PATH);

    $item = new Item;
    return $item->parse_results($text);
}

function fetch_results_maybe_cached ($query, $type, $license, $count) {
    $identifier = identifier_for_query($query, $type, $license);
    $filename = $type . '/' . $identifier;

    if (! file_exists($filename)) {
        $foo = cache_search_results ($filename, $query, $type, $license,
                                     $count);
        $was_cached = false;
    } else {
        $foo = search_results_from_cache ($filename);
        $was_cached = true;
    }

    return new SearchResults($foo, $identifier, $was_cached, 'filesystem');
}
