# README

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
