<div class="headline">{$lang.view_year}</div>
{include file='../mod/default/calendar/switch_view.tpl' url=$url lang=$lang view=$view}
<p>{$prev} | {$next}</p>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th width="8%">{$lang.jan}</th>
		<th width="8%">{$lang.feb}</th>
		<th width="8%">{$lang.mar}</th>
		<th width="8%">{$lang.apr}</th>
		<th width="8%">{$lang.may}</th>
		<th width="8%">{$lang.jun}</th>
		<th width="8%">{$lang.jul}</th>
		<th width="8%">{$lang.aug}</th>
		<th width="8%">{$lang.sep}</th>
		<th width="8%">{$lang.oct}</th>
		<th width="8%">{$lang.nov}</th>
		<th width="8%">{$lang.dec}</th>
	</tr>
	
	<tr>
		{section name=j loop=$month}
		<td align="left" valign="top">
			{section name=i loop=$month[j]}
				<div style="{if $month[j][i].flag == 'Sun'}background-color:#CCCCCC;{/if}{if $month[j][i].flag == 'Sat'}background-color:#DDDDDD;{/if}">{$month[j][i].content}&nbsp;&nbsp;&nbsp;{$month[j][i].flag}</div>
			{/section}
		</td>
		{/section}
	</tr>
	
</table>