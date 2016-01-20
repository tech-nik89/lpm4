<div class="headline">
	{$lang.headline}
</div>
<form action="" method="POST" name="ad" enctype="multipart/form-data">
	<table>
		<tr>
			<th align="center">
				{$lang.delete}
			</th>
			<th align="center">
				{$lang.img}
			</th>
			<th align="center">
				{$lang.link}
			</th>
		</tr>
		{foreach from=$ads item=ad}
			<tr>
				<td align="center">
					<input type="checkbox" name="ads[{$ad.adid}][delete]" />
				</td>
				<td>
					<a href="{$ad.url}" target="_blank"><img src="./media/boximages/ad/{$ad.img}"  border="0" alt="{$ad.img}" /></a>
				</td>
				<td>
					<input type="text" name="ads[{$ad.adid}][url]" value="{$ad.url}" size="30" />
				</td>
			</tr>
		{/foreach}
		<tr>
			<td>
				{$lang.add}
			</td>
			<td>
				<input type="file" name="image" id="image" size="15"/>
			</td>
			<td>
				<input type="text" name="newurl" value="" size="30" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<input type="hidden" name="send" value="1" />
				<input type="submit" value="{$lang.save}" />
			</td>
		</tr>
	</table>
</form>

