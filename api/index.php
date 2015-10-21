<?php

/* API access

   Copyright (C) 2015 Creative Commons Corporation

   based on:

   GNU FM -- a free network service for sharing your music listening habits
   GNU Archie Framework -- a web application framework derived from GNU FM

   Copyright (C) 2009, 2015 Free Software Foundation, Inc

   This program is free software: you can redistribute it and/or modify
   it under the terms of either the GNU Affero General Public License or
   the GNU General Public License as published by the
   Free Software Foundation, either version 3 of the Licenses, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU General Public License and
   the GNU Affero General Public License along with this program.

   If not, see <http://www.gnu.org/licenses/>.

 */

namespace Garmonbozia\Api\v1;

$root = dirname(__DIR__);
require_once($root.'/database.php');
require_once($root.'/version.php');
require_once($root.'/cache.php');
require_once($root.'/utils/multicurl.php');
require_once(__DIR__.'/klein.php');

use Garmonbozia;
use Garmonbozia\Utils;

header('Content-Type: text/javascript; charset=utf8');
header('Access-Control-Allow-Origin: '.\Garmonbozia\Config::$base_url.'/');
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET');

with('/api/v1/version', function () {

    respond('GET', '/?', function ($request, $response) {
        global $code_version, $api_version;
        $version = ['api_version'=>$api_version,
                    'code_version'=>$code_version];
        $output = json_encode($version, JSON_PRETTY_PRINT);
        http_response_code(200);
        echo $output;
    });

});

with('/api/v1/search', function () {

    respond('GET', '/', function ($request, $response) {
        $errors = [];
        $query = $request->param('q', false);
        //FIXME: SANITIZE THESE
        // Errors are dictionaries as we may add error codes as well as mesages
        if (! $query) {
            $errors[] = ['message'=>'No query specified.'];
        }
        $license = $request->param('license', false);
        if (! $license) {
            $errors[] = ['message'=>'No license specified.'];
        }
        $type = $request->param('type', false);
        if (! $type) {
            $errors[] = ['message'=>'No type specified.'];
        }
        $count = min(max($request->param('count', 20), 1), 50);
        if ($errors) {
            $result = ['errors'=>$errors];
            $code = 400;
        } else {
            // Runs in parallel but waits for all to finish
            //TODO: stream responses via http response chunking
            $sources = \Garmonbozia\Config::$media_searches[$type];
            $multicurl = new \Garmonbozia\Utils\MultiCurl();
            $query_string = '?search=' . $query
                          . '&count=' . $count
                          . '&license=' . $license
                          . '&type=' . $type;
            foreach ($sources as $source) {
                $url = $source . $query_string;
                $multicurl->addCurl($url, $source);
            }
            $multicurl->run();
            $results = array();
            $caching = '';
            foreach ($sources as $source) {
                $fetched_contents;
                $err = $multicurl->urlContent($source, $fetched_contents);
                if ($err == 0) {
                    $foo = json_decode($fetched_contents, true);
                    //FIXME: handle individual cache results
                    $results =
                    array_merge($results,
                                Utils\attach_info_to_results($foo['results'],
                                                             $type,
                                                             $license));
                    $caching .= $foo['source'];
                    $caching .= ' | ';
                    if ($foo['cached']) {
                        $caching .= ' (' . $foo['cache'] . '): '
                                  . $foo['identifier'];
                    } else {
                        $caching .= ': live    ';
                    }
                } else {
                    //FIXME: handle err
                    error_log($err);
                }
            }
            $result = ['results'=>$results,
                       'caching'=>$caching];
            $code = 200;
        }
        $output = json_encode($result, JSON_PRETTY_PRINT);
        http_response_code($code);
        echo $output;
    });

});

dispatch();
