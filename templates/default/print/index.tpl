<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		{$core.head}
		<title>{if $breadcrumbs != ''}{$breadcrumbs|strip_tags} | {/if}{$core.title}</title>
		<link rel="stylesheet" href="{$css_path}/style.css" type="text/css" />
	</head>
	
	<body>
		
		<div id="">

			<div id="content">

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
			
		</div>
		
	</body>

</html>