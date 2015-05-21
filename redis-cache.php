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

// How long to cache results for (time to live)
$TTL = 60 * 60 * 24;

$redis = new Redis();
$redis->connect($redis_host, $redis_port);
if ($redis_password) {
    $redis->auth($redis_password);
}

function cache_search_results ($identifier, $query, $type, $license, $count) {
    global $redis;
    global $TTL;
    $item = new Item;
    $results = $item->get_results($query, $type, $license, $count);
    $data = json_encode(array_values($results));

    try {
        $redis->setex($identifier, $TTL, $data);
    }
    finally {
    }

    return $results;
}

function search_results_from_cache ($identifier) {
    global $redis;
    $result = false;
    $text = $redis->get($identifier);
    if ($text != false) {
        $item = new Item;
        $result = $item->parse_results($text);
    }
    return $result;
}

function fetch_results_maybe_cached ($query, $type, $license, $count) {
    $identifier = identifier_for_query($query, $type, $license);
    $foo = search_results_from_cache ($identifier);
    if ($foo) {
        $was_cached = true;
    } else {
        $foo = cache_search_results ($identifier, $query, $type, $license,
                                     $count);
        $was_cached = false;
    }

    return new SearchResults($foo, $identifier, $was_cached, 'redis');
}
