<script src="js/jquery.js" type="text/javascript"></script>
<script src="mod/default/calendar/tooltip.js" type="text/javascript"></script>

<div class="headline">{$lang.view_day}</div>
{include file='../mod/default/calendar/switch_view.tpl' url=$url lang=$lang view=$view}
<p>{$prev} | {$next}</p>
{if $birthdays != null}
	{foreach item=birthday from=$birthdays}
		<strong>{$lang.birthday}</strong>
		<div style="padding: 5px 5px 5px 10px;">
			<a href="?mod=profile&userid={$birthday.userid}">{$birthday.nickname}</a> <br />
		</div>
	{/foreach}
{/if}

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="1">
<colspan>
	<col width="*" />
	{foreach item=width from=$tbl_info}
		<col width="{$width}%" />
	{/foreach}
</colspan>
	{foreach item=hour from=$day_view}
		<tr class="{cycle values=',highlight_row'}">
			{if $hour.quarter==0}
				<td rowspan="4" style="padding:5px; background-color:#e8e8e8;">{$hour.hour}<sup>{$hour.quarter}</sup> {$lang.o_clock}</td>
			{/if}
			{section name=i loop=$hour}
				{if $hour[i].rowspan!=0}
					{if $hour[i].title!=''}
						<td {if $hour[i].description != ''}onMouseMove="showTooltip('entry_{$hour[i].calandarid}');" onMouseOut="hideTooltip('entry_{$hour[i].calandarid}');" {/if} rowspan="{$hour[i].rowspan}" style="line-height:0px; overflow:hidden; vertical-align:top; {if $hour[i].title != ''}background-color: #57FF7E; cursor:pointer; border:1px solid #19D144;{/if}">	
							<div style="line-height:15px; height:15px; padding: 3px 2px 2px 3px;">
								<strong>
									{$hour[i].start.hour}<sup>{$hour[i].start.min}</sup>
									-
									{$hour[i].end.hour}<sup>{$hour[i].end.min}</sup>
									&nbsp;
									{$hour[i].title}<br />
								</strong>
								{$hour[i].description}
							</div>
						</td>

					{else}
						<td style="height:7px; line-height:0px;">&nbsp;</td>
					{/if}
				{/if}
			{/section}
		</tr>
	{/foreach}
</table>

{foreach item=hour from=$day_view}
	{section name=i loop=$hour}
		{if $hour[i].rowspan!=0}
			{if $hour[i].title!=''}
				<div id="entry_{$hour[i].calandarid}" style="max-width:300px; border:1pt solid black; padding: 5px; background-color:#88ff88; display:none;">
					<strong>
						{$hour[i].start.hour}<sup>{$hour[i].start.min}</sup>
						-
						{$hour[i].end.hour}<sup>{$hour[i].end.min}</sup>
						&nbsp;
						{$hour[i].title}<br />
					</strong>
					{$hour[i].description}
				</div>
			{/if}
		{/if}
	{/section}
{/foreach}