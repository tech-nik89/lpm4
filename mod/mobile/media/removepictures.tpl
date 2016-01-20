<div class="headline">{$lang.removepictures}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		{foreach from=$images item=image}
			<tr{cycle values=', class="highlight_row"'}>
				<td>
					<input type="checkbox" name="{$image}" id="{$image}" value="1" />
				</td>
				<td>
					<label for="{$image}">
						{$image}
					</label>
				</td>
				<td align="right">
					<label for="{$image}">
						<img src="mod/default/media/thumbs.php?width=100&file=../../../media/images/{$folder}/{$image}" 
							border="0" />
					</label>
				</td>
			</tr>
		{/foreach}
	</table>
	<p>
		<input type="submit" name="submit" value="{$lang.delete}" />
	</p>
</form>