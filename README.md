## Want to see your favorite site on our new search tool?

* Make a GitHub issue: https://github.com/cc-archive/garmonbozia/issues/new

## Who's working on it at Creative Commons?

| Role  | Person |
| ------------- | ------------- |
| Driver  | [Matt Lee](https://github.com/mattl)  |
| Accountable  |  |
| Consulted | Add yourselves here |
| Informed | Add yourselves here |

---

# CC Search

This is a replacement search for Creative Commons. Rather than just pointing at other engines, we aim to provide a more traditional interface.

* We are not making a search engine, but rather just an interface to existing search engines. 

>Through the darkness of futures past

>The magician longs to see

>One chants out between two worlds

>Fire walk with me

## How this works

1. You enter a query and choose a license.

2. We look for a cached resultset, and if we don't find one we build
   one.

3. We build a resultset by querying the various APIs (so far, Internet
   Archive and Flickr) and storing a few common pieces of data in a
   standard format in JSON.

    * Title of the work
    * License URL
    * Item identifier (IA item data, Flickr object number)
    * Item URL (to actually show the thing)

4. When we have some JSON in our standard format, we display it in a
   nice looking way and you can click it and see more info.

https://archive.org/advancedsearch.php?q=cat+mediatype%3A%28Image%29&fl%5B%5D=identifier&fl%5B%5D=licenseurl&fl%5B%5D=source&fl%5B%5D=title&sort%5B%5D=downloads+desc&sort%5B%5D=&sort%5B%5D=&rows=50&page=1&output=json&callback=callback&save=yes

## Installation

Check out this project:

    git clone https://github.com/cc-archive/garmonbozia.git

Install php dependencies: php5-curl , php5-redis

Restart apache afterwards.

Run composer:

    composer update

Copy and configure config.php:

    cp config.php.example config.php
    emacs config.php

Create the template cache directory and make it writable by the web server:

    mkdir application/themes/cc/templates_c
    chown www-data:www-data application/themes/cc/templates_c
