<?php

  /* Search builder/caching

     Based on GNU FM.

     Copyright (C) 2014 Creative Commons
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

$search_start_time = microtime(true);

//require_once('database.php');
require_once('templating.php');
require_once('data/Items.php');
if($cache_engine == 'redis') {
    require_once('redis-cache.php');
} else {
    require_once('filesystem-cache.php');
}

$query = $_REQUEST['search'];

if ($query !="")
{
    $type = substr($_REQUEST['type'],0,1);
    $license = (int) $_REQUEST['license'];
    $count = 20;

    $smarty->assign('query', $query);
    $smarty->assign('license', $license);
    $smarty->assign('type', $type);

    $was_cached;
    $results = fetch_results_maybe_cached($query, $type, $license, $count);
    if ($results->cached) {
        $smarty->assign('from', "cached (" . $results->cache ."): "
                              . $results->identifier);
    } else {
        $smarty->assign('from', 'live');
    }

    //$foo_of_at_most_requested_length = array_slice($results->results,
    //                                               0, $count);
    $smarty->assign('results', $results->results);
    $smarty->assign('query', $query);

} else {

   $smarty->assign('results', false);

}

// Not the most accurate calculation, as it excludes smarty rendering
// Move final calculation into the template or even postprocess somehow?
$search_time = (microtime(true) - $search_start_time);
if($search_time < 1.0) {
    $search_time = round($search_time * 1000000) . ' &mu;s';
} else {
    $search_time = $search_time . " seconds";
}
$smarty->assign('search_time', $search_time);
$smarty->assign('headerfile', 'welcome-header.tpl');
$smarty->display('search.tpl');
