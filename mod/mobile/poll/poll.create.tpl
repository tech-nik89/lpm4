<div class="headline">{$headline}</div>
{if $info NEQ ""}
	<div>
		{$info}
	</div>
{/if}
{if $only_info!=1}
	<div>
		<form action="#" method="POST">
			<input type="hidden" name="send" value="1"/>
			<table width="100%" cellpadding="5" border="0">
				<colspan>
				<col width="10%">
				<col width="*">
				</colspan>
				<tr>
					<td style="vertical-align:top;">
						{$header_pollname}
					</td>
					<td>
						<textarea rows="3" cols="40" style="width:300px;" name="pollname" maxlength="500">{$value_pollname}</textarea>
					</td>
				</tr>
				{section name=question start=0 loop=$count_questions step=1}
					<tr>
						<td style="padding-left:5px;">
							{$header_question}&nbsp;{$smarty.section.question.index+1}
						</td>
						<td>
							<input type="text" size="50" style="width:300px;" name="question[{$smarty.section.question.index}]" id="{$smarty.section.question.index}" value="{$value_questions[$smarty.section.question.index]}"maxlength="200"/>
						</td>
					</tr>
				{/section}
				<tr>
					<td colspan="2">
						<input type="checkbox" name="buttontype" {if $value_checkbox=="on"}checked="checked"{/if}>{$multiple_answers}</input>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit_add" value="{$submit_add}" />
					</td>
				</tr>
			</table>
		</form>
	</div>
{/if}
