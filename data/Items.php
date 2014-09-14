<?php

/* Item model abstraction from garmonbozia project. 

   Copyright (C) 2014 Creative Commons

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


class Item {
    
    function parse_results($filename) {
        $results = json_decode(file_get_contents($filename, FILE_USE_INCLUDE_PATH),true);
        return $results;
    }

    function fetch_results($query, $domain, $license, $num) {
        
        if ($num == 0) {
            $num=15;
        }
       

        switch ($domain) {
        case "flickr.com":
            $search = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=9901011f723131e89cfd198d740c7b26&text=' . urlencode($query) . '&per_page=15&format=json&nojsoncallback=1&license=' . $license . "&extras=license,date_upload,date_taken,owner_name";
            //var_dump($search);
            $result = file_get_contents($search); 
            return $result; 
            break;
        }
    }

    function metadata($id, $site){

        $item = new Item;

        switch ($site) {

        case "flickr.com":

            

        }

    }

    function simplify($json, $domain){
        $clean = array();
        $json = json_decode($json,true);

        switch ($domain){
        
        case "flickr.com":

            $responses = $json['photos']['photo'];
        
            foreach ($responses as $response) {

                $title = $response['title'];
                $identifier = $response['id'];
                $farmId = $response['farm'];
                $serverId = $response['server'];
                $id = $response['id'];
                $secret = $response['secret'];
                $owner = $response['ownername'];
                
                $thumb = 'https://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'.$id.'_'.$secret.'_s.jpg';
                $image = 'https://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'.$id.'_'.$secret.'_b.jpg';

                array_push($clean, array("title"=>$title,"identifier"=>$identifier,"thumb"=>$thumb,"image"=>$image,"site"=>"flickr"));

            }
            break;

        case "archive.org":
            
            $responses = $json['response']['docs'];
            
            foreach($responses as $response) {

                $title = $response['title'];
                $identifier = $response['identifier'];
                
                $foo = json_decode(file_get_contents("https://archive.org/details/" . $identifier . "&output=json"),true);
                $server = $foo['server'];
                $dir = $foo['dir'];

                $rage = $foo['files'];

                // Yes, we have to loop through the resulting collection to find an image! The choice of variable names here is purely conincidental ;)


                //print_r(array_keys($rage));

                $keys = array_keys($rage);

                //var_dump($keys);

                $loop = 0;

                foreach ($rage as $blah){

                    if ($blah['format'] == "JPEG Thumb") {
                        $image = "https://" . $server . $dir . "/". $blah['original'];
                        $thumb = "https://" . $server . $dir . $keys[$loop];                   
                    }

                    $loop++;

                }

                array_push($clean, array("title"=>$title,"identifier"=>$identifier,"thumb"=>$thumb,"image"=>$image,"site"=>"archive"));
                
            }
      
            break;
        }

        return $clean;

    }


    function get_results($query, $type, $license) {

        $item = new Item;

        switch ($type){
        case "i":

            $archive_url = "https://archive.org/advancedsearch.php?q=(mediatype:(Image) \"" . $query . "\") AND (format:jpeg OR format:jpg OR format:gif OR format:png)&fl%5B%5D=identifier&fl%5B%5D=licenseurl&fl%5B%5D=source&fl%5B%5D=title&sort%5B%5D=downloads+desc&sort%5B%5D=&sort%5B%5D=&rows=10&page=1&output=json&callback=&save=yes";

            // echo $archive_url;

            $archive = file_get_contents($archive_url);
            $site = "archive.org";

            $archive = $item->simplify($archive,$site);

            $flickr = $item->fetch_results($query, "flickr.com", $license);

            $flickr = $item->simplify($flickr,"flickr.com");

            $results = $flickr;

            $results = array_merge($results, $archive);

            break;

        }
    
        $query = urlencode($query);
        $query = str_replace(" ", "+", $query);
     
        $filename = $type . "/" . $license . "--" . "results-" . $query . ".json";

        $data = json_encode(array_values($results));
    
        try {
            file_put_contents($filename, $data);
        }
        finally {
        }

        return $item->parse_results($filename);

    }
}

?>
