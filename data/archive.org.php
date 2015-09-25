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
require_once('utils/multicurl.php');

function fetch_results ($query, $source, $type, $license, $count)
{
    $search =
        'https://archive.org/advancedsearch.php?q=(mediatype:(Image) "'
        . $query
        . '") AND (format:jpeg OR format:jpg OR format:gif OR format:png)&fl%5B%5D=identifier&fl%5B%5D=licenseurl&fl%5B%5D=source&fl%5B%5D=title&sort%5B%5D=downloads+desc&sort%5B%5D=&sort%5B%5D=&rows=10&page=1&output=json&callback=&save=yes';
    $contents = file_get_contents($search);
    $json = json_decode($contents, true);
    return regularize_results($json);
}

function regularize_results ($json) {
    $results = $json['response']['docs'];
    $regularized = array();
    $multicurl = new \Garmonbozia\Utils\MultiCurl();
    foreach($results as $result) {
        $identifier = $result['identifier'];
        $url = "https://archive.org/details/" . $identifier
            . "&output=json";
        $multicurl->addCurl($url, $identifier);
    }
    $multicurl->run();
    foreach($results as $result) {
        $identifier = $result['identifier'];
        $fetched_contents;
        $err = $multicurl->urlContent($identifier, $fetched_contents);
        if($err != 0) {
            //FIXME: log error
            continue;
        }
        $foo = json_decode($fetched_contents, true);
        $metadata = $foo['metadata'];
        //FIXME: Not every upload has a "creator". Skip these for now,
        //       we should degrade more gracefully somehow.
        if (array_key_exists('creator', $metadata)) {
            $title = $result['title'];
            $server = $foo['server'];
            $dir = $foo['dir'];

            $rage = $foo['files'];

            // Yes, we have to loop through the resulting collection
            // to find an image! The choice of variable names here is
            // purely coincidental ;)

            //print_r(array_keys($rage));

            $keys = array_keys($rage);

            //var_dump($keys);

            $loop = 0;

            foreach ($rage as $blah) {

                if ($blah['format'] == "JPEG Thumb") {
                    $image = "https://" . $server . $dir . "/"
                           . $blah['original'];
                    $thumb = "https://" . $server . $dir . $keys[$loop];
                }

                $loop++;

            }

            $work_url = "https://archive.org/details/" . $identifier;

            $creator = $metadata['creator'][0];

            $creator_url = 'https://archive.org/search.php?query=creator"'
              . $creator .'"';

            array_push($regularized, array(
                "site"=>"archive.org",
                "identifier"=>$identifier,
                "title"=>$title,
                "url"=>$work_url,
                "author"=>$creator,
                "author_url"=>$creator_url,
                "preview_url"=>$thumb,
                "full_url"=>$image,
            ));
        }
    }

    $multicurl->cleanup();
    return $regularized;
}

search('archive.org');
