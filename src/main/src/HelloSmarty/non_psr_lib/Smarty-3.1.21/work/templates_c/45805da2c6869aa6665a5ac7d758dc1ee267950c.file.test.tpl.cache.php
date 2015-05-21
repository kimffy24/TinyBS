<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-08 12:43:11
         compiled from "/server/web/TinyBS/src/main/src/HelloSmarty/non_psr_lib/Smarty-3.1.21/work/templates/test.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1734435431554c9dfc2ffc16-99421944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45805da2c6869aa6665a5ac7d758dc1ee267950c' => 
    array (
      0 => '/server/web/TinyBS/src/main/src/HelloSmarty/non_psr_lib/Smarty-3.1.21/work/templates/test.tpl',
      1 => 1431086173,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1734435431554c9dfc2ffc16-99421944',
  'function' => 
  array (
  ),
  'cache_lifetime' => 3600,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_554c9dfc35f369_74305392',
  'variables' => 
  array (
    'title' => 0,
    'name' => 0,
    'now' => 0,
    'url' => 0,
    'foo' => 0,
    'bar' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_554c9dfc35f369_74305392')) {function content_554c9dfc35f369_74305392($_smarty_tpl) {?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
</head>
<body>


<!-- HTML 注释会发送到浏览器 -->








<!-- 显示 main content 块 -->




<!-- 变量被注释了 -->




<h1>第一次使用smarty哦</h1>
<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
<br />
<?php echo $_smarty_tpl->tpl_vars['now']->value;?>
<br />
<?php $_smarty_tpl->tpl_vars['foo'] = new Smarty_variable(array($_smarty_tpl->tpl_vars['url']->value,'__invoke'), null, 0);?>
<?php $_smarty_tpl->tpl_vars['bar'] = new Smarty_variable(array('map360.sys',array('action'=>'secondPage')), null, 0);?>
<?php echo call_user_func_array($_smarty_tpl->tpl_vars['foo']->value,$_smarty_tpl->tpl_vars['bar']->value);?>

</body>
</html><?php }} ?>
