<div class="headline">{$category.name}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<colgroup>
		<col width="*" />
		<col width="160" />
		<col width="100" />
	</colgroup>
	
	{section name=i loop=$list}
		<tr class="{cycle values='highlight_row,'}">
			<td><strong><a href="{$list[i].url}">{$list[i].name}</a></strong></td>
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
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	{section name=i loop=$pictures}
		<tr>
			{section name=j loop=$pictures[i]}
				<td align="center">
					<a href="media/images/{$category.folder}/{$pictures[i][j]}" 
						rel="gallery"
						title="{$pictures[i][j]}">
							<img src="mod/default/media/thumbs.php?width={$thumbnailwidth}&file=../../../media/images/{$category.folder}/{$pictures[i][j]}" 
							border="0" />
					</a>
				</td>
			{/section}
		</tr>
	{/section}
</table>

<script type="text/javascript">
	$("a[rel=gallery]").fancybox({
		'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'titlePosition' 	: 'over',
		'titleFormat'       : function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Image ' +  (currentIndex + 1) + ' / ' + currentArray.length + ' ' + title + '</span>';
		}
	});
</script>
{/if}


{if $isallowed && $category.folder|trim != ''}
	<p>
		{$lang.folder}: {$category.folder}
	</p>
{/if}