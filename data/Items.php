<?php

class Item {

  function parse_results($filename) {

    $results = json_decode(file_get_contents($filename, FILE_USE_INCLUDE_PATH),true);

    return $results;
  }

  function fetch_results($query, $domain, $license) {

      switch ($domain) {

      case "flickr.com":

          $search = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=9901011f723131e89cfd198d740c7b26&text=' . urlencode($query) . '&per_page=50&format=json&nojsoncallback=1&license=' . $license; 
          $result = file_get_contents($search); 
          
          return $result; 

          break;
      }

  }

  function simplify($json, $domain){

      //echo $json;

    $clean = array();

    $json = json_decode($json,true);

    switch ($domain){

    case "flickr.com":

        $responses = $json['photos']['photo'];

        //var_dump($responses);
        
        foreach ($responses as $response) {

            $title = $response['title'];
            $identifier = $response['id'];
            $farmId = $response['farm'];
            $serverId = $response['server'];
            $id = $response['id'];
            $secret = $response['secret'];

            $thumb = 'http://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'.$id.'_'.$secret.'_s.jpg';
            $image = 'http://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'.$id.'_'.$secret.'_b.jpg';

            array_push($clean, array("title"=>$title,"identifier"=>$identifier,"thumb"=>$thumb,"image"=>$image));

        }

        break;

    case "archive.org":


      // var_dump($foo['response']);

      $responses = $json['response']['docs'];

      foreach($responses as $response) {

        $title = $response['title'];
        $identifier = $response['identifier'];
        
        $foo = json_decode(file_get_contents("https://archive.org/details/" . $identifier . "&output=json"),true);
        $server = $foo['server'];
        $dir = $foo['dir'];

        $foo = $foo['files'];

        foreach ($foo as $blah){

          if ($blah['format'] == "JPEG Thumb") {
            $image = "https://" . $server . $dir . "/". $blah['original'];
          }

        }

        array_push($clean, array("title"=>$title,"identifier"=>$identifier,"thumb"=>$image));

        //$clean['title'] = $title;
        //$clean['identifier'] = $identifer;
        //$clean['image'] = $image;
        
      }
      
      break;
    }

    return $clean;

  }


  function get_results($query, $type, $license) {

    $item = new Item;

    switch ($type){
    case "i":

        //$results = file_get_contents("https://archive.org/advancedsearch.php?q=" . $query . "+mediatype%3A%28Image%29&fl%5B%5D=identifier&fl%5B%5D=licenseurl&fl%5B%5D=source&fl%5B%5D=title&sort%5B%5D=downloads+desc&sort%5B%5D=&sort%5B%5D=&rows=10&page=1&output=json&callback=&save=yes");
        //$site = "archive.org";

        

        //$results = $item->simplify($results,$site);

        $results = $item->fetch_results($query, "flickr.com");

        $results = $item->simplify($results,"flickr.com");

        //echo $results;


      


      //echo $results;

      break;

    }
     
    $filename = $type . "/" . $license . "--" . "results-" . $query . ".json";

    //$smarty->assign('debug', "writing new file:" . $filename);

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
