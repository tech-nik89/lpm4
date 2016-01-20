<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//DE" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />

    <title>{$title}</title>
    
    <link href="{$css_path}/default.css" rel="stylesheet" type="text/css" />
	<script src="js/reflection.js" type="text/javascript"></script>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/jquery-ui/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
	<script src="thickbox/thickbox.js" type="text/javascript"></script>
</head>

<body>
	{section name=notify loop=$notify}
			<div class="notification">
				<div><strong>{$notify[notify].subject}</strong></div>
				<div style="margin-left:10px;">{$notify[notify].message}</div>
			</div>
	{/section}
	<div style="">
	{if $path != ""}
		{include file="$path"}
	{else}
		{if $str_output != ""}
			{$str_output}
		{else}
			&nbsp;
		{/if}
	{/if}
	</div>
	{section name=menu loop=$menu}
		{if $menu[menu].headline == true}
			<div class="headline"><strong>{$menu[menu].title}</strong></div>
		{else}
			{if $menu[menu].submenu == true}
				<div>
					<a href="{$menu[menu].url}" class="menu">&nbsp;&nbsp; &raquo; {$menu[menu].title}</a>
				</div>
			{else}
				<div>
					<a href="{$menu[menu].url}" class="menu">{$menu[menu].title}</a>
				</div>
			{/if}
		{/if}
	{/section}
</body>
</html>