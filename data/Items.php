<?php

class Item {

  function parse_results($filename) {

    $results = json_decode(file_get_contents($filename, FILE_USE_INCLUDE_PATH),true);

    return $results;
  }

  function simplify($json, $domain){

    $clean = array();

    switch ($domain){
    case "archive.org":

      $foo = json_decode($json,true);
      // var_dump($foo['response']);

      $responses = $foo['response']['docs'];

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

        array_push($clean, array("title"=>$title,"identifier"=>$identifier,"image"=>$image));

        //$clean['title'] = $title;
        //$clean['identifier'] = $identifer;
        //$clean['image'] = $image;
        
      }
      
      break;
    }

    return $clean;

  }


  function get_results($query, $type) {

    $item = new Item;

    switch ($type){
    case "i":

      $results = file_get_contents("https://archive.org/advancedsearch.php?q=" . $query . "+mediatype%3A%28Image%29&fl%5B%5D=identifier&fl%5B%5D=licenseurl&fl%5B%5D=source&fl%5B%5D=title&sort%5B%5D=downloads+desc&sort%5B%5D=&sort%5B%5D=&rows=10&page=1&output=json&callback=&save=yes");
      $site = "archive.org";

      $results = $item->simplify($results,$site);

      //echo $results;

      break;

    }
     
    $filename = $type . "/results-" . $query . ".json";

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
