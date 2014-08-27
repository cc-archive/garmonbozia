{if empty($results)}
<h1>No results found</h1>
{/if}

{{$results}}

{foreach $results as $r}
  <li><img src="{$r.image}" width="100" title="{$r.title}" /></li>
{foreachelse}
   No results 
{/foreach}
