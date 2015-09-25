<?php
/* Parameter integrity

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

namespace Garmonbozia\Utils;

require_once('config.php');

class Integrity {

  static $VIEW_PARAM_HASH_SIGNIFICANT_DIGITS = 6;

  static $VIEW_PARAM_HASH_CURRENT_VERSION = 'a';

  static function view_params_hash ($license, $type, $site, $title,
                                    $url, $author, $author_url,
                                    $preview_url, $full_url) {
    $salted_str = \Garmonbozia\Config::$view_hash_salt . $license . $type
      . $site . $title . $url . $author . $author_url . $preview_url
      . $full_url;
    $full_hash = md5($salted_str);
    $short_hash = substr($full_hash,
                         - self::$VIEW_PARAM_HASH_SIGNIFICANT_DIGITS);
    //$short_hash_base64 = base64_encode($short_hash);
    //return substr($short_hash_base64,
    //              - self::$VIEW_PARAM_HASH_SIGNIFICANT_DIGITS);
    return $short_hash;
  }

}