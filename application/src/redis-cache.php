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

namespace Garmonbozia;

require_once(dirname(dirname(__DIR__)).'/config.php');
require_once(__DIR__.'/caching.php');

define('CACHE_IMPLEMENTATION', 'redis');

// How long to cache results for (time to live)
define('REDIS_TTL', 60 * 60 * 24);

$redis = new \Redis();
$redis->connect(Config::$redis_host, Config::$redis_port);
if (Config::$redis_password) {
    $redis->auth(Config::$redis_password);
}

function cache_search_results ($results, $query, $source, $type, $license,
                               $count) {
    global $redis;
    $identifier = identifier_for_query($query, $source, $type, $license);
    $data = json_encode(array_values($results));

    try {
        $redis->setex($identifier, REDIS_TTL, $data);
    }
    finally {
    }
}

function search_results_from_cache ($query, $source, $type, $license, $count) {
    global $redis;
    $identifier = identifier_for_query($query, $source, $type, $license);
    $result = false;
    $text = $redis->get($identifier);
    return json_decode($text, true);
}
