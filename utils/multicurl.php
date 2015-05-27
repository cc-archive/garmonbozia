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

// Parralelize network fetches

class Multicurl {
    private $multicurl;
    // These are all by *index* due to resources being bad array indexes
    // This means that unset values in callbacks/identifiers are null,
    // and trying to really use null for them will lead to bad things happening.
    private $connections;
    private $callbacks;
    private $identifiers;

    public function __construct () {
        $this->multicurl = curl_multi_init();
        $this->connections = array();
        $this->callbacks = array();
        $this->identifiers = array();
    }

    // Callback is called via call_user_func with curl result, content
    // Associated data needs to be spl_object_hash -ed if not str or num
    // Result will be 0 if content is good, content will be '' if result is bad
    // Timeout is in seconds

    public function addCurl ($url, $identifier=null, $callback=null,
                             $timeout=5) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_TIMEOUT, $timeout);
        $i = count($this->connections);
        $this->connections[$i] = $conn;
        $this->callbacks[$i] = $callback;
        $this->identifiers[$i] = $identifier;
        curl_multi_add_handle($this->multicurl, $conn);
    }

    // Call this once you've called addCurl as many times as you're going to

    public function run () {
        do {
            $status = curl_multi_exec($this->multicurl, $active);
            $info = curl_multi_info_read($this->multicurl);
            if (false !== $info) {
                $handle = $info['handle'];
                $index = array_search($handle, $this->connections);
                $callback = $this->callbacks[$index];
                // If the handle has successfully fetched content
                if ($info['msg'] == 1 && $info['result'] == 0) {
                    if ($callback != null) {
                        $result = 0;
                        $content = curl_multi_getcontent($handle);
                        call_user_func($callback, $result, $content);
                    }
                } else if ($info["msg"] == 1) {
                    if ($callback != null) {
                        $result = $info['result'];
                        $content = '';
                        call_user_func($callback, $result, $content);
                    }
                }
            }
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);
    }

    // If you didn't pass a callback, or if you want to get results again,
    // you can call this once run() returns
    // Do not try to use null as an identifier. No really. Don't.

    public function urlContent ($identifier, &$content) {
        if ($identifier === null) {
            $content = '';
            return -1;
        }
        $index = array_search($identifier, $this->identifiers);
        $handle = $this->connections[$index];
        $result = curl_errno($handle);
        $content = curl_multi_getcontent($handle);
        return $result;
    }

    // Make sure you call this once you're finished! Avoid resource leaks!

    public function cleanup () {
        foreach ($this->connections as $conn) {
            curl_multi_remove_handle($this->multicurl, $conn);
            curl_close($conn);
        }
        curl_multi_close($this->multicurl);
    }
}

/* e.g.

$urls = array(
   "http://www.creativecommons.org/",
   "http://www.fsf.org/",
   "http://www.libre.fm/",
   "http://www.dsgs32654562dgdsfghsdhsdhsdfdfh.com/"
);

function call_me ($result, $content) {
    if ($result != 0) {
        echo "ERROR: " . $result . "<br><br>";
    } else {
        echo htmlspecialchars($content)
           . "<br><br>";
    }
};

$mc = new MultiCurl();
foreach ($urls as $url) {
    $mc->addCurl($url, $null, 'call_me');
}
$mc->run();
$mc->cleanup();

*/
