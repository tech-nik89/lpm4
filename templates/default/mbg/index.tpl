<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		{$core.head}
		<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
		<link rel="stylesheet" href="{$css_path}/style.css" type="text/css" />
	</head>
	
	<body>
		<div align="center">
			<div style="width:1000px;" align="left">
			
				
				<div id="header" align="right">
					Modellbaugruppe Filderstadt e.V.
				</div>
				
				<div>
					<img src="{$image_path}/header.png" border="0" alt="header" />
				</div>
				
				
            	<div style="border:1px solid #0078ff; background-color:#FFFFFF;">
                
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					
					<tr>
						<td align="left" valign="top" width="170px">
					
							{foreach from=$menu item=item}
								{include file='../templates/default/mbg/menu.tpl' menuitem=$item}
							{/foreach}
							<p>
								&nbsp;
							</p>
                            <div>
                                {section name=i loop=$box.left}
									<div style="padding:5px; color:#000000; font-size:12px;"><strong>&raquo; {$box.left[i].title}</strong></div>
                                    <div class="box">
                                    {include file=$box.left[i].file}
                                    </div>
                                {/section}
                            </div>
							
						</td>
						<td align="left" valign="top">
					
							<div id="content">
								
								{if $breadcrumbs != ''}
									<p>{$breadcrumbs}</p>
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
					
						</td>
					</tr>
					
				</table>
				
                </div>
                
					<div id="footer">
						&copy; 2010 ApfelSOFT | visit <a href="http://www.apfelsoft.net" target="_blank">apfelsoft.net</a> | Version: {$core.version.string} | <a href="?mod=imprint">Impressum</a>
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
	
			</div>
		</div>
	</body>

</html>