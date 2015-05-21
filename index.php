<?php

/* Garmonbozia - Creative Commons search.

   Based on GNU FM.

   Copyright (C) 2014, 2015 Creative Commons
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

//require_once('database.php');

if (isset($_REQUEST['output'])) {

   // Leaving this here for future API support for the search itself

} else {
    //If we're not handshaking we display the nixtape start page

    require_once('templating.php');
    require_once('data/Items.php');

    $smarty->assign('query', '');
    $smarty->assign('license', 4);
    $smarty->assign('type', 'i');

    $smarty->assign('headerfile', 'welcome-header.tpl');
    $smarty->assign('welcome', true);

    $smarty->display('welcome.tpl');
  }
