<p align="right"><a href="{$url.thread_add}">{$lang.thread_add}</a></p>

<p>{$pages}</p>
<table width="100%" border="0" cellspacing="0" cellpadding="5">

	<tr>
    	<th>&nbsp;</th>
		<th>{$lang.thread}</th>
        <th width="150">{$lang.answers} / {$lang.hits}</th>
		<th>{$lang.last_post}</th>
	</tr>
	
	{section name=i loop=$tl}
	
	<tr>
    	<td style="border-top:1px solid #CCCCCC;" valign="top">
			<img src="{$image_path}/{$tl[i].newposts}" alt="newposts" />
		</td>
		<td style="border-top:1px solid #CCCCCC;" valign="top">
        	<a href="{$tl[i].url}">{$tl[i].thread}</a><br />
            {$lang.created_by} {$tl[i].nickname}
        </td>
        <td style="border-top:1px solid #CCCCCC;" valign="top">
        	{$lang.answers}: {$tl[i].answers}
            <br />
            {$lang.hits}: {$tl[i].hits}
        </td>
		<td style="border-top:1px solid #CCCCCC;" valign="top">
			{$tl[i].last_post}
		</td>
	</tr>
	
	{/section}
	
</table>
<p>{$pages}</p>

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
			<img src="{$image_path}/thread_old_inv.png" border="0" alt="Old Thread" />
		</td>
		<td>
			{$lang.legend_old_inv}
		</td>
	</tr>
	<tr>
		<td>
			<img src="{$image_path}/thread_new.png" border="0" alt="Old Thread" />
		</td>
		<td>
			{$lang.legend_new}
		</td>
		<td>
			<img src="{$image_path}/thread_new_inv.png" border="0" alt="Old Thread" />
		</td>
		<td>
			{$lang.legend_new_inv}
		</td>
	</tr>
</table>