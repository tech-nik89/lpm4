<table width="100%" cellspacing="0" cellpadding="3">
	<tr>
    	<th>&nbsp;</th>
		<th>{$lang.board}</th>
		<th>{$lang.threads}</th>
		<th>{$lang.posts}</th>
        <th>{$lang.last_post}</th>
	</tr>
	
	{section name=i loop=$bl}
    
	<tr>
    	<td style="border-top:1px solid #CCCCCC;" rowspan="2" width="50"><img src="{$image_path}/{$bl[i].newposts}" alt="newposts" /></td>
		<td style="border-top:1px solid #CCCCCC;"><strong><a href="{$bl[i].url}">{$bl[i].board}</a></strong></td>
		<td style="border-top:1px solid #CCCCCC;" width="80">{$bl[i].threads}</td>
		<td style="border-top:1px solid #CCCCCC;" width="80">{$bl[i].posts}</td>
        <td style="border-top:1px solid #CCCCCC;">{$bl[i].lastpost}</td>
	</tr>
    
    <tr>
		<td colspan="4">{$bl[i].description}</td>
    </tr>
    
	{/section}
	
</table>

<div class="headline">{$lang.legend}</div>
<table width="100%" border="0">
	<tr>
		<td>
			<img src="{$image_path}/thread_old.png" border="0" alt="Old Thread" />
		</td>
		<td>
			{$lang.legend_old}
		</td>
		<td>
			<img src="{$image_path}/thread_new.png" border="0" alt="Old Thread" />
		</td>
		<td>
			{$lang.legend_new}
		</td>
	</tr>
</table>