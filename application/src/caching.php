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

require_once(__DIR__.'/integrity.php');

class SearchResults {
    public $source;
    public $results;
    public $cached;
    public $identifier;
    public $cache;

    function __construct($results, $source, $identifier, $cached, $cache) {
        $this->results = $results;
        $this->source = $source;
        $this->identifier = $identifier;
        $this->cached = $cached;
        $this->cache = $cache;
    }
}

function identifier_for_query ($query, $source, $type, $license) {
    // This has to be Unix filename and url safe, so no / etc.
    // The identifier  includes the type, which is redundant when used as a
    // filename within a type directory.
    // Don't be tempted to remove it, we need it for e.g. nosql ids!
    $identifier = urldecode($query);
    $identifier = strtolower($identifier);
    $identifier = str_replace(" ", "+", $identifier);
    //$identifier = preg_replace("/[^a-zA-Z]+/", "", $query);
    $identifier = $type . "-" . $license . "-" . $source . "--results-"
                . $identifier;
    return $identifier;
}

// Attach useful things we don't cache to each item

function attach_info_to_results($results, $type, $license) {
  $id_array = [//'cache_id'=>$identifier,
               'type' => $type,
               'license' => $license];
  $attach_id_fun = function($result) use ($id_array) {
    $params = array_merge($result, $id_array);
    $params['hash'] = Integrity::view_params_hash($params['license'],
                                                  $params['type'],
                                                  $params['site'],
                                                  $params['title'],
                                                  $params['url'],
                                                  $params['author'],
                                                  $params['author_url'],
                                                  $params['preview_url'],
                                                  $params['full_url']);
    return $params;
  };
  return array_map($attach_id_fun, $results);
}
