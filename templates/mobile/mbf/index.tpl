<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//DE" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0;" />

    <title>{$core.title}</title>
    {$core.head}
    <link href="{$css_path}/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="header" align="center">
		Modellbaufreunde Filderstadt e.V.
	</div>
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
	<div class="headline">{$lang.menu}</div>
	{foreach from=$menu item=item}
		{include file='../templates/mobile/mbf/menu.tpl' menuitem=$item}
	{/foreach}
	<div class="headline">Optionen</div>
	{foreach from=$submenu item=item}
		<a href="{$item.url}" class="menu">{$item.title}</a>
	{/foreach}
</body>
</html>