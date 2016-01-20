<div class="headline">{$lang.polls}</div>
<table style="border-collapse:collapse; width:100%;">
	<colgroup>
		<col width="3%" />
		<col width="5%" />
		<col width="30%" />
		<col width="*" />
		<col width="10%" />
		{if !empty($rights.editor)}
			<col width="1%" />
			<col width="1%" />
		{/if}
	</colgroup>
	<tr>
		<th>
			&nbsp;
		</th>
		<th>
			&nbsp;
		</th>
		<th>
			Titel
		</th>
		<th>
			Beschreibung
		</th>	
		<th>
			Status
		</th>		
		{if !empty($rights.editor)}
			<th colspan="2">
				Aktion
			</th>		
		{/if}
	</tr>
	{foreach from=$polls item=poll}
		{if !empty($rights.editor) OR $poll.state==1 OR $poll.state==2}
			<tr {cycle values='class="highlight_row",'}>
				<td>
					{if $poll.hasVoted}&radic;{/if}
				</td>
				<td>
					{$poll.answerCount}
				</td>
				<td>
					<a href="{$poll.url}">{$poll.title}</a>
				</td>
				<td>
					{$poll.description}
				</td>
				<td>
					{$lang.state.{$poll.state}}
				</td>
				{if !empty($rights.editor)}
					<td style="text-align:center;">
						{if !empty($poll.url_edit)}
							<a href="{$poll.url_edit}">#</a>
						{/if}
					</td>
					<td style="text-align:center;">
						{if !empty($rights.editor)}
							<a href="{$poll.url_delete}" onClick="return confirm('{$lang.poll_delete_really}');">X</a>
						{/if}
					</td>		
				{/if}
			</tr>
		{/if}
	{/foreach}
</table>