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

require_once('database.php');
require_once('templating.php');
require_once('data/Items.php');

if ($_REQUEST['search'] !="") 
  {

    $query = $_REQUEST['search'];
    $type = substr($_REQUEST['type'],0,1);
    $license = (int) $_REQUEST['license'];
    
    $smarty->assign('query', $query);

    $results = new Item;

    $filename = preg_replace("/[^a-zA-Z]+/", "", $query);
    $filename = strtolower($filename);
    $filename = $type . "/results-" . $filename . ".json";
    mkdir($type);

    $smarty->assign('debug', "filename=" . $filename);
    
    if (file_exists($filename)) {
        $foo = $results->parse_results($filename);
    }
    else {
        $foo = $results->get_results($query,$type, $license);
    }

    $smarty->assign('results', $foo);
   
  } 
 else {

   $smarty->assign('results',"");

 }

$smarty->assign('headerfile', 'welcome-header.tpl');
$smarty->display('search.tpl');

