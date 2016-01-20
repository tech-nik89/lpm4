<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		{$core.head}
		<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
		<link rel="stylesheet" href="{$css_path}/main.css" />
	</head>

<body>
	
	<div align="center">
		
		<div id="main">
			
			<div id="header">
			  <img src="{$image_path}/header.png" border="0" align="left" style="margin:0px;" /><img src="{$image_path}/header_right.gif" border="0" align="right" style="margin:0px;" />
			</div>
			
	    <div id="middle">
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top" width="233px">
						
						<div class="box">
							{foreach from=$menu item=item}
								{include file='../templates/default/lpm3/menu.tpl' menuitem=$item}
							{/foreach}
						</div>
						
					</td>
                    <td align="right" valign="top">
					
						<div id="content">
							
							<div id="contentheader" style="border-bottom:1px solid #000000;">
								<div class="caption">{$breadcrumbs}</div>
							</div>
							
							{if count($submenu) > 0}
								<div align="left" style="padding:5px; border-bottom:1px solid #000;">
									{foreach from=$submenu item=item}
										&raquo;&nbsp; <a href="{$item.url}">{$item.title}</a>
									{/foreach}
								</div>
							{/if}
							
							{section name=notify loop=$notify}
								<div class="notification" align="left">
									<div><strong>{$notify[notify].subject}</strong></div>
									<div style="margin-left:10px;">{$notify[notify].message}</div>
								</div>
							{/section}
							
							<div id="contentcontent">
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
						
					</td>
				</tr>
                </table>
			</div>
			
			<div id="footer">
				&copy; 2008 - 2009 | <a href="http://www.apfelsoft.net/" class="bottom_menu" target="_blank">ApfelSOFT</a><a class="bottom_menu" href="?page=about"> LAN Party Manger 3</a> | Design by Marc Gazivoda</div>
			
	  </div>
		
	</div>
</body>
</html>
