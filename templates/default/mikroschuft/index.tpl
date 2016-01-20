<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	{$core.head}
	<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
	<link href="{$css_path}/style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div align="center" style="padding-top:40px;">
	<div style="width:934px;">
		<img src="{$image_path}/header1.jpg" alt="header" border="0" />
	</div>
	<div id="content_bg" align="left">
		<div style="float:left; padding:15px; width:20%;">
			{foreach from=$menu item=item}
				{include file='../templates/default/mikroschuft/menu.tpl' menuitem=$item}
			{/foreach}
		</div>
		{if count($submenu) > 0}
			<div align="left" id="submenu">
				{foreach from=$submenu item=item}
					&raquo;&nbsp; <a href="{$item.url}">{$item.title}</a>
				{/foreach}
			</div>
		{/if}
		<div id="content" style="margin-left:20%;padding:15px; ">
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
		<div style="clear:left;"></div>
	</div>
	<div style="width:932px; overflow:hidden;" align="left">
		<img src="{$image_path}/Top_Footer.jpg" border="0" alt="footer-top" />
		<img src="{$image_path}/Bottom_Footer.gif" border="0" alt="footer-top" />
	</div>
</div>
</body>

</html>