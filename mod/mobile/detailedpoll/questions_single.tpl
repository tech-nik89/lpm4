<tr {if $data.depth==0} class="highlight_row" {else} class="highlight_row_{$data.depth}" {/if}>
	<td style="text-align:center;">
		{if $questionid == 'new'}
			{$lang.new}
		{else}
			<input type="checkbox" style="width:100%; margin:0px; padding:0px;" name="data[{$questionid}][delete]" />
		{/if}
	</td>
	<td>
		{section name=arrows start=0 loop=$data.depth step=1}&gt;{/section}
	</td>
	<td onClick="setParent('{$data.questionid}');">
		{$data.questionid}
	</td>
	<td>
		<input type="number" style="width:100%;" name="data[{$questionid}][rank]" value="{$data.rank}" />
	</td>
	<td>
		<input type="number" style="width:100%;" {if $questionid == 'new'}id="new_parentid" {/if} name="data[{$questionid}][parentid]" value="{$data.parentid}" />
	</td>
	<td>
		<input type="text" style="width:100%;" name="data[{$questionid}][title]" value="{$data.title}" />
	</td>
	<td>
		<input type="text" style="width:100%;" name="data[{$questionid}][description]" value="{$data.description}" />
	</td>
	<td>
		<input type="number" style="width:100%;" name="data[{$questionid}][percent]" value="{$data.percent}" />
	</td>
</tr>
