<?php

/* Garmonbozia - Creative Commons search.

   Copyright (C) 2014, 2105 Creative Commons

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

namespace Garmonbozia\Data;

require_once('search-base.php');

function fetch_results ($query, $source, $type, $license, $count)
{
    $search = 'https://api.flickr.com/services/rest/'
        . '?method=flickr.photos.search&api_key='
        . \Garmonbozia\Config::$flickr_api_key
        . '&text='
        . urlencode($query)
        . '&per_page=15&format=json&nojsoncallback=1&license='
        . $license
        . '&extras=license,date_upload,date_taken,owner_name';
    $contents = file_get_contents($search);
    $json = json_decode($contents, true);
    return regularize_results($json);
}

function regularize_results ($json) {
    $regularized = array();
    $responses = $json['photos']['photo'];
    foreach ($responses as $response) {
        $title = $response['title'];
        $farmId = $response['farm'];
        $serverId = $response['server'];
        $id = $response['id'];
        $secret = $response['secret'];
        $owner = $response['owner'];
        $ownername = $response['ownername'];

        $thumb = 'https://farm' . $farmId . '.static.flickr.com/'
               . $serverId.'/' . $id . '_' . $secret . '_s.jpg';
        $image = 'https://farm' . $farmId . '.static.flickr.com/'
               . $serverId . '/' . $id . '_' . $secret . '_b.jpg';

        $owner_url = 'https://www.flickr.com/photos/' . $owner;

        $work_url = $owner_url . '/' . $id . '/';

        array_push($regularized, array(
            "site"=>"flickr.com",
            "identifier"=>$id,
            "title"=>$title,
            "url"=>$work_url,
            "author"=>$ownername,
            "author_url"=>$owner_url,
            "preview_url"=>$thumb,
            "full_url"=>$image,
        ));
    }
    return $regularized;
}

search('flickr.com');
