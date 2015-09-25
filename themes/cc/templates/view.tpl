{include file='header.tpl'}


<h1>{$title}</h1>

<p><a alt="{$title}" href="{$url}">View this image on {$site}</a></p>

<p id="preview"><a href="{$url}"><img alt="{$title}" src="{$image}" /></a></p>

<p id="license-html-rdf"><a rel="license" href="{$license_url}"><img alt="Creative Commons License" style="border-width:0" src="{$license_icon}" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="{$dcmitype}" property="dct:title" rel="dct:type">{$title}</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="{$author_url}" property="cc:attributionName" rel="cc:attributionURL">{$author}</a> is licensed under a <a rel="license" href="{$license_url}">{$license_string}</a>.</p>

<br /><textarea id="copy-well" readonly="true"></textarea><br />
<script>
    document.getElementById('copy-well').value =
     document.getElementById('preview').innerHTML
     + "\n"
     + document.getElementById('license-html-rdf').innerHTML;

  var supported = true;//document.queryCommandSupported("copy");

  if (supported) {
    document.write('<button type="button" id="copy">Copy to clipboard with attribution.</button>');
    document.getElementById('copy').addEventListener("click", function(event) {
      event.preventDefault();
      document.getElementById("copy-well").select();
      var succeeded;
      try {
        succeeded = document.execCommand("copy");
      } catch (e) {
        succeeded = false;
      }
      if (succeeded) {
        // The copy was successful
      } else {
        // The copy failed
      }
      document.getElementById("copy-well").setSelectionRange(0, 0);
    });
  } else {
    document.write("Copy this text to include and attribute the work.");
  }
</script>
</div>
<div class="container">
<hr class="clearfix" />

{include file='footer.tpl'}