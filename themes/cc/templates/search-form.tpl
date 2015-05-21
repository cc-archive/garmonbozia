<form action="search.php" class="form" role="form">
  <div class="form-group form-group-lg">
    <input class="form-control input-lg" size="64" maxlength="64" type="text"
    name="search" value="{$query}" autofocus placeholder="Monkeys" />
  </div>
  <div class="form-group form-group-sm">
    <select name="license">
      <option{if $license == 4} selected{/if} value="4">Creative Commons Attribution</option>
      <option{if $license == 5} selected{/if}  value="5">Creative Commons Attribution-ShareAlike</option>
      <option{if $license == 2} selected{/if}  value="2">Creative Commons Attribution-NonCommercial</option>
      <option{if $license == 1} selected{/if}  value="1">Creative Commons Attribution-NonCommercial-ShareAlike</option>
      <option{if $license == 6} selected{/if}  value="6">Creative Commons Attribution-NoDerivs</option>
      <option{if $license == 3} selected{/if}  value="3">Creative Commons Attribution-NonCommercial-NoDerivs</option>
      <option{if $license == 0} selected{/if}  value="0">Creative Commons Zero/Pubic Domain</option>
    </select>
    <select name="type">
      <option{if $type == "i"} selected{/if} value="i">Images</option>
      <option{if $type == "v"} selected{/if} value="v">Videos</option>
      <option{if $type == "a"} selected{/if} value="a">Audio</option>
      <option{if $type == "b"} selected{/if} value="b">Books</option>
    </select>
    <input type="submit" />
  </div>

</form>
