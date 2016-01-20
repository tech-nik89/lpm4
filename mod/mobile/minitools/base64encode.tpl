<div class="headline">
	{$lang.base64} - {$lang.base64encode}
</div>

<form action="" enctype="multipart/form-data" method="POST">
	<table style="width:100%;">
		<colgroup>
			<col width="50%" />
			<col width="50%" />
		</colgroup>
		<tr>
			<th>
				{$lang.text}
			</th>
			<th>
				{$lang.file}
			</th>
		</tr>
		<tr>
			<td>
				<input name="text" type="text" style="width:100%"/>
			</td>
			<td>
				<input name="file" type="file" style="width:100%"/>
			</td>
		</tr>
		<tr>
			<th>
				{$lang.wrapcount}: <input name="wrapcount" type="number" size="3" />
			</th>
			<td>
				
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="hidden" name="send" value="1" />
				<input type="submit" value="{$lang.base64convert}" />
			</td>
		</tr>
	</table>
</form>

{if !empty($base64encoded)}
	<table style="width:100%; table-layout:fixed;">
		<tr>
			<th>
				{$lang.base64encoded}
			</th>
		</tr>
		<tr>
			<td>
				<textarea style="width:100%; height:100px;">{$base64encoded}</textarea>
			</td>
		</tr>
		{if !empty($presrcdata)}
			<tr>
				<th>
					{$lang.base64convertedHTML}
				</th>
			</tr>
			<tr>
				<td>
					<textarea style="width:100%; height:100px;"><img src="{$presrcdata}{$base64encoded}" /></textarea>
				</td>
			</tr>
			<tr>
				<th>
					{$lang.img}
				</th>
			</tr>
			<tr>
				<td>
					<img style="max-width:100%;" src="{$presrcdata}{$base64encoded}" />
				</td>
			</tr>
		{/if}
	</table>
	
{/if}
