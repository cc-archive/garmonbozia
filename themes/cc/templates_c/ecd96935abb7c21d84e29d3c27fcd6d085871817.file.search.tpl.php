<?php /* Smarty version Smarty-3.1-DEV, created on 2014-09-03 20:07:36
         compiled from "/home/mattl/dev/garmonbozia/themes/cc/templates/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33116281553fb87823d8688-83719352%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecd96935abb7c21d84e29d3c27fcd6d085871817' => 
    array (
      0 => '/home/mattl/dev/garmonbozia/themes/cc/templates/search.tpl',
      1 => 1409789254,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33116281553fb87823d8688-83719352',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_53fb878245d3f9_06004903',
  'variables' => 
  array (
    'results' => 0,
    'r' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53fb878245d3f9_06004903')) {function content_53fb878245d3f9_06004903($_smarty_tpl) {?><?php if (empty($_smarty_tpl->tpl_vars['results']->value)){?>
<h1>No results found</h1>
<?php }?>

<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['results']->value;?>
<?php $_tmp1=ob_get_clean();?><?php echo $_tmp1;?>


<?php  $_smarty_tpl->tpl_vars['r'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['r']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['results']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['r']->key => $_smarty_tpl->tpl_vars['r']->value){
$_smarty_tpl->tpl_vars['r']->_loop = true;
?>
  <li><img src="<?php echo $_smarty_tpl->tpl_vars['r']->value['thumb'];?>
" width="100" title="<?php echo $_smarty_tpl->tpl_vars['r']->value['title'];?>
" /></li>
<?php }
if (!$_smarty_tpl->tpl_vars['r']->_loop) {
?>
   No results 
<?php } ?>
<?php }} ?>