<div>	
	<a href="{$pollbox.poll.url}">{$pollbox.poll.name}</a>
</div>

{if $layout=='boxes'}
	<div>
		<table width="100%" cellpadding="5" border="0">
			<colspan>
			<col width="10%">
			<col width="*">
			</colspan>
			{foreach from=$pollbox.poll.questions item=question}
				<tr>
					{if $tworower==1}
						<tr>
							<td>
								{$question.text}
							</td>
						</tr>
						<tr>
							<td style="padding-top:0px;">
							{section name=foo start=0 loop=$question.bar_length/10 step=1}
								<div style="background-color:{$question.color}; width:9px; height:15px; float:left; margin-right:1px;"></div>
							{/section}
								<div style="float:left; margin-left:2px;">{$question.percentage}%</div>
								<div style="float:none;"> </div>
							</td>
						</tr>
					{else}
						<tr>
							<td>
								{$question.text}
							</td>
							<td style="padding-top:0px;">
							{section name=foo start=0 loop=$question.bar_length/10 step=1}
								<div style="background-color:{$question.color}; width:9px; height:15px; float:left; margin-right:1px;"></div>
							{/section}
								<div style="float:left; margin-left:2px;">{$question.percentage}%</div>
								<div style="float:none;"> </div>
						</tr>
					{/if}
				</tr>
			{/foreach}
		</table>
	</div>

{else}

	<div>
		<table width="100%" cellpadding="5" border="0">
			<colspan>
			<col width="10%">
			<col width="*">
			</colspan>
			{foreach from=$pollbox.poll.questions item=question}
				<tr>
					{if $tworower==1}
						<tr>
							<td>
								{$question.text}
							</td>
						</tr>
						<tr>
							<td style="padding-top:0px;">
								<div style="width:{$question.bar_length}%; height:15px; line-height:15px; background-color:{$question.color}; vertical-align: middle; text-align:right;">&nbsp;{$question.percentage}%&nbsp;</div>
							</td>
						</tr>
					{else}
						<tr>
							<td>
								{$question.text}
							</td>
							<td>
								<div style="width:{$question.bar_length}%; height:15px; line-height:15px; background-color:{$question.color}; vertical-align: middle; text-align:right;">&nbsp;{$question.percentage}%&nbsp;</div>
							</td>
						</tr>
					{/if}
				</tr>
			{/foreach}
		</table>
	</div>

{/if}