{* 我是一个Smarty的注释, 显示输出时我不会存在  *}
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{$title}</title>
</head>
<body>

{* 另一个单行的注释例子  *}
<!-- HTML 注释会发送到浏览器 -->

{* 
 Smarty的多行
  注释
  不会发送到浏览器
*}

{*********************************************************
多行注释的说明栏
  @ author:         bg@example.com
  @ maintainer:     support@example.com
  @ para:           var that sets block style
  @ css:            the style output
**********************************************************}

{* 头部文件包括LOGO和其他东西  *}

{* 开发说明:  $includeFile是通过foo.php赋值的  *}
<!-- 显示 main content 块 -->

{* 这里的 <select> 块是多余的 *}
{*
<select name="company">
  {html_options options=$vals selected=$selected_id}
</select>
*}

<!-- 变量被注释了 -->
{* $affiliate|upper *}

{* 注释不能嵌套 *}

<h1>第一次使用smarty哦</h1>
{$name}<br />
{$now}<br />
{assign var=foo value=[ $url, '__invoke' ]}
{assign var=bar value=[ 'map360.sys', [ 'action'=>'secondPage' ] ]}
{call_user_func_array($foo, $bar)}
</body>
</html>