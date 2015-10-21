<?php

  /* Search builder/caching

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

namespace Garmonbozia;

require_once('database.php');
require_once('templating.php');
require_once('utils/integrity.php');
require_once('utils/licenses.php');

$check_str = $_REQUEST['check'];

if ($check_str != "")
{
    //TODO: Check we have all the params and tell user if not

    $site = urldecode($_REQUEST['site']);
    $type = urldecode($_REQUEST['type']);
    $license = urldecode($_REQUEST['license']);
    $license_locale = '';
    $license_version = '4.0';
    $title = urldecode($_REQUEST['title']);
    $url = urldecode($_REQUEST['url']);
    $author = urldecode($_REQUEST['author']);
    $author_url = urldecode($_REQUEST['author_url']);
    $preview_url = urldecode($_REQUEST['preview_url']);
    $full_url = urldecode($_REQUEST['full_url']);

    $check =
      Utils\Integrity::view_params_hash($license,
                                        $type,
                                        $site,
                                        $title,
                                        $url,
                                        $author,
                                        $author_url,
                                        $preview_url,
                                        $full_url);

    if ($check_str != $check) {
        die("Params invalid.");
    }

    $smarty->assign('site', $site);
    $smarty->assign('dcmitype', Utils\media_dcmitype_for_letter($type));
    $smarty->assign('license_string', Utils\license_string($license,
                                                           $license_version,
                                                           $license_locale));
    $smarty->assign('license_url', Utils\license_url_for_num($license,
                                                             $license_version));
    $smarty->assign('license_icon',
                    Utils\license_icon_for_num($license, $license_version));
    $smarty->assign('title', $title);
    $smarty->assign('url', $url);
    $smarty->assign('author', $author);
    $smarty->assign('author_url', $author_url);
    $smarty->assign('image', $full_url);
}

$smarty->assign('headerfile', 'welcome-header.tpl');
$smarty->display('view.tpl');
