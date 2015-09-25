{include file='header.tpl'}

{if empty($results)}
<h1>No results found</h1>
{/if}

{include file='search-form.tpl'}

{if $results}
<ul id="results" class="clearfix">
{foreach $results as $r}
  <li style="background-image: url({$r.preview_url});"><a href="/viewer.php?license={$r.license}&amp;type={$type}&amp;site={$r.site}&amp;title={$r.titler|urlencode};&amp;url={$r.urlr|urlencode};author={$r.author|urlencode}&amp;author_url={$r.author_url|urlencode}&amp;preview_url={$r.preview_url|urlencode}&amp;full_url={$r.full_url|urlencode}&amp;check={$r.hash}" title="{$r.title}"></a></li>
{foreachelse}
  No results.
{/foreach}
</ul>
{/if}

</div>

<div class="container">
<p>Things not working as you expect? Or do you have any suggestions? <a href="https://github.com/creativecommons/garmonbozia/issues/new?body=Search:%20{$query|escape:'url'}%0A%0A">Click here</a>!</p>
<p class="text-muted"><small>approx. {$search_time}</small>
   <small>{$from}</small></p>
<hr class="clearfix" />
{include file='footer.tpl'}
