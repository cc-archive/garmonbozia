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

    $query = $_REQUEST['search'];

if ($query !="") 
  {
    $type = substr($_REQUEST['type'],0,1);
    $license = (int) $_REQUEST['license'];
    $u = $_REQUEST['u'];
    $site = $_REQUEST['s'];
    $id = $_REQUEST['id'];
    
    switch ($site) {

    case "flickr":
        $site = "Flickr";
        
        

    }

    
    $smarty->assign('query', $query);
    $smarty->assign('license', $license);
    $smarty->assign('type', $type);
    $smarty->assign('uri', $u);
    $smarty->assign('site', $site);

   
  } 
 else {

   $smarty->assign('results',"");
   

 }

$smarty->assign('headerfile', 'welcome-header.tpl');
$smarty->display('view.tpl');

