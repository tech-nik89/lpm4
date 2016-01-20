<script src="js/jquery.js" type="text/javascript"></script>
<script src="mod/default/calendar/tooltip.js" type="text/javascript"></script>

<div id="divInfo" style="border:1pt solid black; padding: 5px; background-color:#88ff88 ; display:none;">
&nbsp;
</div>

<div class="headline">{$lang.view_week}</div>
{include file='../mod/default/calendar/switch_view.tpl' url=$url lang=$lang view=$view}
<p>{$prev} | {$next}</p>
<table width="100%" border="0" cellspacing="1" cellpadding="3" style="width:100%; border:1px solid #999999;">
	<colspan>
		{foreach item=width from=$tbl_info}
			<col width="{$width}%" />
		{/foreach}
	</colspan>

	<tr>
		<th colspan="{$colspans[0]}">{$lang.sunday}</th>
		<th colspan="{$colspans[1]}">{$lang.monday}</th>
		<th colspan="{$colspans[2]}">{$lang.tuesday}</th>
		<th colspan="{$colspans[3]}">{$lang.wednesday}</th>
		<th colspan="{$colspans[4]}">{$lang.thursday}</th>
		<th colspan="{$colspans[5]}">{$lang.friday}</th>
		<th colspan="{$colspans[6]}">{$lang.satturday}</th>
	</tr>
	<tr>
		<td colspan="{$colspans[0]}" class="highlight_row">{$fields[0].date}</td>
		<td colspan="{$colspans[1]}" class="highlight_row">{$fields[1].date}</td>
		<td colspan="{$colspans[2]}" class="highlight_row">{$fields[2].date}</td>
		<td colspan="{$colspans[3]}" class="highlight_row">{$fields[3].date}</td>
		<td colspan="{$colspans[4]}" class="highlight_row">{$fields[4].date}</td>
		<td colspan="{$colspans[5]}" class="highlight_row">{$fields[5].date}</td>
		<td colspan="{$colspans[6]}" class="highlight_row">{$fields[6].date}</td>
	</tr>
	{section name=y loop=$fields[0].hours}
		<tr>
			{section name=x loop=$fields}
				{section name=e loop=$fields[x].hours[y]}
					{if $fields[x].hours[y][e].rowspan!=0}
						{if $fields[x].hours[y][e].title!=''}
							<td onMouseMove="showTooltip('entry_{$fields[x].hours[y][e].calendarid}');" onMouseOut="hideTooltip('entry_{$fields[x].hours[y][e].calendarid}');" rowspan="{$fields[x].hours[y][e].rowspan}" style="line-height:0px; overflow:hidden; vertical-align:top; {if $fields[x].hours[y][e].title != ''}cursor:pointer; background-color: {$fields[x].hours[y][e].backgroundcolor}; color: {$fields[x].hours[y][e].fontcolor};{/if}">
								<div style="line-height:15px; height:15px; width:0px;">
									{$fields[x].hours[y][e].title}
								</div>
							</td>
						{else}
							<td style="height:7px; line-height:0px;">&nbsp;</td>
						{/if}
					{/if}
				{/section}
			{/section}
		</tr>
	{/section}
</table>

{section name=y loop=$fields[0].hours}
	{section name=x loop=$fields}
		{section name=e loop=$fields[x].hours[y]}
			{if $fields[x].hours[y][e].rowspan!=0}
				{if $fields[x].hours[y][e].title!=''}
					<div id="entry_{$fields[x].hours[y][e].calendarid}" style="max-width:300px; border:1pt solid black; padding: 5px; display:none; background-color: {$fields[x].hours[y][e].backgroundcolor}; color: {$fields[x].hours[y][e].fontcolor};">
						<strong>
							{$fields[x].hours[y][e].start.hour}<sup>{$fields[x].hours[y][e].start.min}</sup>
							-
							{$fields[x].hours[y][e].end.hour}<sup>{$fields[x].hours[y][e].end.min}</sup>
							&nbsp;{$fields[x].hours[y][e].title}
						</strong>
						<br />
						{$fields[x].hours[y][e].description}
					</div>
				{/if}
			{/if}
		{/section}
	{/section}
{/section}

{if $birthdays != null}
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed; width:100%; border-collapse:collapse;">
		<colspan>
			<col width="14.58%">
			<col width="14.58%">
			<col width="14.58%">
			<col width="14.58%">
			<col width="14.58%">
			<col width="14.58%">
			<col width="14.58%">

		</colspan>
		<tr>
			{foreach item=birthday from=$birthdays}
				<td style="overflow:hidden; padding: 0px 0px 0px 3px; border-left: 1pt solid #999999; border-bottom: 1pt solid #999999; border-right: 1pt solid #999999;">
					{if $birthday != null}
						<div>
							{foreach item=user from=$birthday}
								<a href="?mod=profile&userid={$user.userid}">{$user.nickname}</a> <br />
							{/foreach}
						</div>
					{else}
						&nbsp;
					{/if}
				</if>
			{/foreach}
		</tr>
	</table>
{/if}
