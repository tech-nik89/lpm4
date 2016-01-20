<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<div class="headline">{$category.name}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	{section name=i loop=$list}
		<tr class="{cycle values='highlight_row,'}">
			<td width="30%"><strong><a href="{$list[i].url}">{$list[i].name}</a></strong></td>
			{if $hide_submedia != '1'}
    			<td><strong>{$list[i].subcategoriescount}</strong> {$lang.subcategories}</td>
    			<td><strong>{$list[i].mediacount}</strong> {$lang.media}</td>
    		{/if}
		</tr>
	{/section}
	
</table>

{if count($downloads) > 0}
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		{section name=i loop=$downloads}
			
			<tr class="{cycle values='highlight_row,'}">
				<td colspan="2"><a href="{$downloads[i].url}">{$downloads[i].name}</a></td>
			</tr>
			
		{/section}
		
	</table>
	
{/if}

{if count($movies) > 0}
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		{section name=i loop=$movies}
			
			<tr class="{cycle values='highlight_row,'}">
				<td width="30%"><a href="{$movies[i].url}">{$movies[i].name}</a></td>
				<td>{$movies[i].description}</td>
			</tr>
			
		{/section}
		
	</table>
	
{/if}


{if count($pictures) > 0}
	<p>&nbsp;</p>
	{section name=i loop=$pictures}
		{section name=j loop=$pictures[i]}
			<div align="center" style="padding-bottom:5px;">
				<a href="media/images/{$category.folder}/{$pictures[i][j]}" 
					title="{$pictures[i][j]}">
						<img src="mod/default/media/thumbs.php?width={$thumbnailwidth}&file=../../../media/images/{$category.folder}/{$pictures[i][j]}" 
						border="0" />
				</a>
			</div>
		{/section}
	{/section}
{/if}


{if $isallowed && $category.folder|trim != ''}
	<p>
		{$lang.folder}: {$category.folder}
	</p>
{/if}