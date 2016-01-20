<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		{$core.head}
		<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
		<link rel="shortcut icon" href="{$image_path}/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="{$css_path}/style.css" />
	</head>
	
	<body>
    	
        <div style="height:22px; background-color:#c1d72e; line-height:18px;">
            &nbsp;
        </div>
        
        <div style="background-image:url({$image_path}/header-bg.gif); background-repeat:repeat-x;">
            <img src="{$image_path}/header.gif" border="0" alt="header" />
        </div>
        
        <div style="height:22px; background-color:#D5DF3D; line-height:18px; padding-left:225px;">
            &nbsp;
        </div>
    
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	
            <tr>
            	<td align="left" valign="top" width="240px">
					
					<div style="background-color:#CCCCCC;">
						{foreach from=$menu item=item}
							{include file='../templates/default/dodlan/menu.tpl' menuitem=$item}
						{/foreach}
					</div>
					
					<div>
						{section name=i loop=$box.left}
							<div class="headline">{$box.left[i].title}</div>
							<div class="box">
							{include file=$box.left[i].file}
							</div>
						{/section}
					</div>
                    
                </td>
                <td align="left" valign="top">
			
                    <div id="content">
                        
						{if $breadcrumbs != ''}
						<div style="border:1px solid #DDDDDD; padding:3px; border-left:7px solid #D5DF3D; margin-bottom:10px;">
							{$breadcrumbs}
						</div>
						{/if}
						
						{if count($submenu) > 0}
							<div style="border:1px solid #DDDDDD; padding:3px; border-left:7px solid #D5DF3D; margin-bottom:10px;">
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
			
				</td>
				
				<td valign="top" width="180px">
					<div>
						{section name=i loop=$box.right}
							<div class="headline">{$box.right[i].title}</div>
							<div class="box">
							{include file=$box.right[i].file}
							</div>
						{/section}
					</div>
				</td>
            </tr>
			
        </table>
        
			<div id="footer">
				&copy; 2009 ApfelSOFT | visit <a href="http://www.apfelsoft.net" target="_blank">apfelsoft.net</a> | Version: {$version.string}
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