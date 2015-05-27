{include file='header.tpl'}

{if empty($results)}
<h1>No results found</h1>
{/if}

{include file='search-form.tpl'}

{if $results}
<ul id="results" class="clearfix">
{foreach $results as $r}
  <li style="background-image: url({$r.thumb});"><a href="/viewer.php?search={$query}&l={$license}&amp;type={$type}&amp;u={$r.image}&id={$r.identifier}&s={$r.site}" title="{$r.title}"></a></li>
{foreachelse}
  No results.
{/foreach}
</ul>
{/if}

</div>

<div class="container">
<p>Things not working as you expect? Or do you have any suggestions? <a href="https://github.com/creativecommons/garmonbozia/issues/new?body=Search:%20{$query|escape:'url'}%0A%0A">Click here</a>!</p>
<hr class="clearfix" />
<p class="text-muted"><small>approx. {$search_time}</small><br/>
   <small>{$from}</small></p>
{include file='footer.tpl'}
