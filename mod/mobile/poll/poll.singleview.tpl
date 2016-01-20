<div class="headline">{$headline}</div>

{if $no_poll==""}
	<div>	
		{$poll.name}
	</div>
	{if $poll.allready_voted == 1}
		<div>
			<table width="100%" cellpadding="5" border="0">
				<colspan>
				<col width="10%">
				<col width="*">
				</colspan>
				{foreach from=$poll.questions item=question}
					<tr>
						<td>
							{$question.text}
						</td>
						{if $main_layout=='boxes'}
							<td style="padding-top:0px;">
								{section name=foo start=0 loop=$question.bar_length/10 step=1}
									<div style="background-color:{$question.color}; width:9px; height:15px; float:left; margin-right:1px;"></div>
								{/section}
								<div style="float:left; margin-left:2px;">{$question.percentage}%</div>
								<div style="float:none;"> </div>
							</td>
						{else}
							<td>
								<div style="width:{$question.bar_length}px; height:20px; line-height:20px; background-color:{$question.color}; vertical-align: middle; text-align:right;">&nbsp;{$question.percentage}%&nbsp;</div>
							</td>
						{/if}
					</tr>
				{/foreach}
			</table>
		</div>
		{$number_of_voters} {$poll.votes}{if $poll.given_answers!=$poll.votes}/{$poll.given_answers}{/if}
	{else}
		<div>
			<form action="" method="POST">
				<input type="hidden" name="send" value="1">
				<input type="hidden" name="poll" value="{$poll.ID}">
				<table width="100%" cellpadding="5" border="0">
					<colspan>
						<col width="1px">
						<col width="*">
					</colspan>
					{foreach from=$poll.questions item=question}
						<tr>
							<td style="text-align:center; padding:0;">
								<input name="question{$poll.ID}[]" type="{$poll.button}" value="{$question.ID}"/>
							</td>
							<td style="text-align:left;">
								{$question.text}
							</td>
						</tr>
					{/foreach}
					<tr>
						<td colspan="2">
							<input type="submit" value="{$submit_value}">
						</td>
					</tr>
				</table>
			</form>
		</div>
	{/if}

	{if $editor==true}
		<table style="padding-top:8px;">
			<tr> 
				<td>
					<form action="{$edit}" method="POST">
						<input type="hidden" name="edit" value="{$poll.ID}"/>
						<input type="submit" value="{$submit_edit}"/>
					</form> 
				</td>
				<td>
					<form action="" method="POST">
						<input type="hidden" name="inactive" value="{$poll.ID}"/>
						<input type="submit" value="{if $poll.active==0}{$active}{else}{$inactive}{/if}"/>
					</form> 
				</td>
				<td>
					<form action="{$delete}" method="POST">
						<input type="hidden" name="delete" value="{$poll.ID}"/>
						<input type="submit" value="{$submit_delete}"/>
					</form> 
				</td>
			</tr>
		</table>
	{/if}
	
	{if count($all_comments) > 0}
		<div id="comments" class="headline">{$lang.comments}</div>
		{foreach from=$all_comments item=comment}
			<div class="comment">
				<p>
					<strong>{$comment.nickname}</strong> ({$comment.time})<br />
					{$comment.text}
				</p>
			</div>
		{/foreach}
	{/if}
	
	<div class="headline">{$lang.comment_add}</div>
	<form action="" method="POST">
		<input type="hidden" name="send_comment" value="1" />
		<textarea name="message" style="width:100%; height:80px;"></textarea
		<br />
		<input type="submit" value="{$lang.add}" />
	</form>
{else}
	<div>
		{$no_poll}
	</div>
{/if}
