<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		{$core.head}
		<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
		<link rel="stylesheet" href="{$css_path}/style.css" type="text/css" />
	</head>
	
	<body>
		
		<div id="main">
			
			<div id="header">
				Higher For Hire<br />
				<div id="subheader">The home of the LAN Party Manager IV</div>
			</div>
			
			<div id="menu">
				
				{foreach from=$menu item=item}
					{include file='../templates/default/default/menu.tpl' menuitem=$item}
				{/foreach}
				
				<div>
					{section name=i loop=$box.left}
						<div class="headline">{$box.left[i].title}</div>
						<div class="box">
						{include file=$box.left[i].file}
						</div>
					{/section}
				</div>
				
			</div>
			
			<div id="content">
				
				<p>
					{$breadcrumbs}
				</p>
				
				{if count($submenu) > 0}
					<div align="left" id="submenu">
						{foreach from=$submenu item=item}
							&raquo;&nbsp; <a href="{$item.url}">{$item.title}</a>
						{/foreach}
					</div>
				{/if}
				
				{section name=notify loop=$notify}
						<div class="notification">
							<div><strong>{$notify[notify].subject}</strong></div>
							<div style="margin-left:10px;">{$notify[notify].message}</div>
						</div>
				{/section}

				<div>
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
				
			</div>
			
			<div id="right">
				{section name=i loop=$box.right}
					<div class="headline">{$box.right[i].title}</div>
					<div class="box">
					{include file=$box.right[i].file}
					</div>
				{/section}
			</div>
			
			<div style="clear:left;"></div>
			
			<div id="footer">
				&copy; 2010 ApfelSOFT | visit <a href="http://www.apfelsoft.net" target="_blank">apfelsoft.net</a>
			</div>
			
		</div>
		
		<p align="center">
		    <a href="http://validator.w3.org/check?uri=referer"><img
			src="media/w3c/valid-xhtml10.png" border="0" 
			alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
		   <a href="http://jigsaw.w3.org/css-validator/check/referer">
			<img style="border:0;width:88px;height:31px"
			    src="media/w3c/vcss.gif"
			    alt="CSS ist valide!" />
		    </a>

		  </p>

		
	</body>

</html>