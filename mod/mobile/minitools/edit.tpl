<div class="headline">
	{$lang.minitoolsedit}
</div>

<form action="" method="POST">
	<table>
		<tr>
			<th>
				{$lang.visible}
			</th>
			<th>
				{$lang.modename}
			</th>
			<th>
				{$lang.name}
			</th>
		</tr>
		{if !empty($minitools)}
			{foreach from=$minitools item=minitool}
				<tr>
					<td>
						<input type="checkbox" name="data[{$minitool.modename}]" id="{$minitool.modename}"{if !empty($minitool.visible)} checked="checked"{/if} />
						
					</td>
					<td>
						{$minitool.modename}
					</td>
					<td>
						{$lang.{$minitool.modename}}
					</td>
				</tr>
			{/foreach}
			<tr>
				<td colspan="2">
					<input type="hidden" name="send" value="1" />
					<input type="submit" value="{$lang.save}" />
				</td>
			</tr>
		{/if}
	</table>
</form>

