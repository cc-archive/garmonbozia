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

class SearchResults {
    public $results;
    public $cached;
    public $identifier;
    public $cache;

    function __construct($results, $identifier, $cached, $cache) {
        $this->results = $results;
        $this->identifier = $identifier;
        $this->cached = $cached;
        $this->cache = $cache;
    }
}

function identifier_for_query ($query, $type, $license) {
    // This has to be filename and url safe, so it includes the type.
    // This is redundant when used as a filename within a type directory.
    // Don't be tempted to remove it, we need it for e.g. nosql ids.
    $identifier = urldecode($query);
    $identifier = strtolower($identifier);
    $identifier = str_replace(" ", "+", $identifier);
    //$identifier = preg_replace("/[^a-zA-Z]+/", "", $query);
    $identifier = $type . "-" . $license . "--results-" . $identifier . ".json";
    return $identifier;
}
