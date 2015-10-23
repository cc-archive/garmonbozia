<?php

/* Garmonbozia - Creative Commons search.

   Copyright (C) 2014, 2105 Creative Commons

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

//set_include_path(get_include_path() . PATH_SEPARATOR . '..');

require_once(dirname(dirname(__DIR__)).'/config.php');
require_once(__DIR__.'/cache.php');

function search ($source, $fetch_results) {
    $query = $_REQUEST['search'];
    $type = substr($_REQUEST['type'],0,1);
    $license = (int) $_REQUEST['license'];
    $count = (int) $_REQUEST['count'];
    $results = print_results_maybe_cached($query, $source, $type, $license,
                                          $count, $fetch_results);
    return $results;
}

function fetch_results_maybe_cached ($query, $source, $type, $license, $count,
                                     $fetch_results) {
    $foo = search_results_from_cache($query, $source, $type,
                                     $license, $count);
    if ($foo) {
        $was_cached = true;
    } else {
        $foo = $fetch_results($query, $source, $type, $license, $count);
        cache_search_results($foo, $query, $source, $type,
                             $license, $count);
        $was_cached = false;
    }
    $identifier = identifier_for_query($query, $source, $type, $license);
    return new SearchResults($foo, $source, $identifier,
                             $was_cached,
                             CACHE_IMPLEMENTATION);
}

function print_results_maybe_cached ($query, $source, $type, $license, $count,
                                     $fetch_results) {
    $results = fetch_results_maybe_cached ($query, $source, $type, $license,
                                           $count, $fetch_results);
    echo json_encode($results);
}
