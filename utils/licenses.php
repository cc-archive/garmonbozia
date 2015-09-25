<?php

  /* License identifiers

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

// FIXME: Separate PD and CC0!

function license_name_for_num ($num) {
    switch ($num) {
    case 0:
        $lic = 'Creative Commons Zero/Public Domain';
        break;
    case 1:
        $lic = 'Creative Commons Attribution-NonCommercial-ShareAlike';
        break;
    case 2:
        $lic = 'Creative Commons Attribution-NonCommercial';
        break;
    case 3:
        $lic = 'Creative Commons Attribution-NonCommercial-NoDerivs';
        break;
    case 4:
        $lic = 'Creative Commons Attribution';
        break;
    case 5:
        $lic = 'Creative Commons Attribution-ShareAlike';
        break;
    case 6:
        $lic = 'Creative Commons Attribution-NoDerivs';
        break;
    default:
        $lic = "Unknown";
        break;
    }
    return $lic;
}

function license_string ($num, $ver, $locale) {
    $license = license_name_for_num($num);
    if ($ver) {
        $license .= ' ' . $ver;
    }
    if ($locale) {
        $license .= ' ' . $locale;
    }
    return $license . ' License';
}

function license_shortcode_for_num ($num) {
   switch ($num) {
    case 0:
        $lic = 'publicdomain';
        break;
    case 1:
        $lic = 'by-nc-sa';
        break;
    case 2:
        $lic = 'by-nc';
        break;
    case 3:
        $lic = 'by-nc-nd';
        break;
    case 4:
        $lic = 'by';
        break;
    case 5:
        $lic = 'by-sa';
        break;
    case 6:
        $lic = 'by-nd';
        break;
    default:
        $lic = "unknown";
        break;
    }
    return $lic;
}

//TODO: We don't currently localize currency symbol for NC

function license_icon_for_num ($num, $version) {
   return 'https://licensebuttons.net/l/'
       . license_shortcode_for_num($num) . '/' . $version
       .'/88x31.png';
}

function license_url_for_num ($num, $ver) {
   $url = 'http://creativecommons.org/licenses/'
       . license_shortcode_for_num($num)
       . '/' . $ver . '/';
   return $url;
}

function license_deed_for_num ($num, $ver, $lang) {
    if ($lang) {
        $lc = ".$lang";
    } else {
        $lc = '';
    }
    return license_url_for_num($num)
        . 'deed' . $lc;
}

function media_dcmitype_for_letter ($letter) {
    switch ($letter) {
    case 'a': // Audio
        $type = 'Sound';
        break;
    case 'b': // Book
        $type = 'Text';
        break;
    case 'i': // Dataset
        $type = 'Dataset';
        break;
    case 'i': // Image
        $type = 'StillImage';
        break;
    case 'r': // Interactive resource
        $type = 'InteractiveResource';
        break;
    case 'v': // Video
        $type = 'MovingImage';
        break;
    default:
        $type = '';
        //FIXME: ERROR
        break;
    }
    return 'http://purl.org/dc/dcmitype/' . $type;
}
