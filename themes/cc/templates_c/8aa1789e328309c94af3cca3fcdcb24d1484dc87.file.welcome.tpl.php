<?php /* Smarty version Smarty-3.1-DEV, created on 2014-09-03 19:13:40
         compiled from "/home/mattl/dev/garmonbozia/themes/cc/templates/welcome.tpl" */ ?>
<?php /*%%SmartyHeaderCode:155057615953f7aca27e2752-13414315%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8aa1789e328309c94af3cca3fcdcb24d1484dc87' => 
    array (
      0 => '/home/mattl/dev/garmonbozia/themes/cc/templates/welcome.tpl',
      1 => 1409785181,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '155057615953f7aca27e2752-13414315',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_53f7aca2865599_19938926',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53f7aca2865599_19938926')) {function content_53f7aca2865599_19938926($_smarty_tpl) {?><form action="search.php">

<input type="text" name="search" />
<select name="license">
<option value="4">CC BY</option>
<option value="5">CC BY-SA</option>
<option value="2">CC BY-NC</option>
<option value="1">CC BY-NC-SA</option>
<option value="6">CC BY-ND</option>
<option value="3">CC BY-NC-ND</option>
<option value="0">CC Zero/Pubic Domain</option>
</select>
<select name="type">
<option value="i">Image</option>
</select>
<input type="submit" />

</form><?php }} ?>