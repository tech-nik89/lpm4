<div class="headline">{$headline}</div>

<div>
	{$poll_name}
</div>
<div>
	<form action="#" method="POST">
		<input type="hidden" name="send" value="1">
		<table width="100%" cellpadding="5" border="0">
			<colspan>
				<col width="1px">
				<col width="*">
			</colspan>
			{foreach from=$poll_questions item=question}
				<tr>
					<td style="text-align:center; padding:0;">
						<input name="question[]" type="{$button_type}" value="{$question.ID}"/>
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

