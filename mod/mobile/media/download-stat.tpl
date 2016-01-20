<div class="headline">{$lang.download_stat}</div>

<table width="100%">
	<tr>
		<td width="35%">{$lang.downloads_today}:</td>
		<td>{$dl_stat.today}</td>
		<td width="35%">{$lang.downloads_this_week}:</td>
		<td>{$dl_stat.this_week}</td>
	</tr>
	<tr>
		<td>{$lang.downloads_this_month}:</td>
		<td>{$dl_stat.this_month}</td>
		<td>{$lang.downloads_this_year}:</td>
		<td>{$dl_stat.this_year}</td>
	</tr>
</table>

<div class="headline">{$lang.month}</div>
<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td width="35%">{$lang.month}:</td>
			<td>
				{html_select_date display_years=false display_days=false time=$selected_month_ts}
				<input type="submit" name="select_month" value="{$lang.go}" />
			</td>
		</tr>
		<tr>
			<td>{$lang.stat_export}:</td>
			<td><a href="{$dl_stat.export_url}" target="_blank">{$lang.download}</a></td>
		</tr>
	</table>
</form>

{if count($dl_stat.days) > 0}
	<div class="headline">{$lang.downloads_day_overview}</div>
	<table border="0" width="100%">
		{foreach from=$dl_stat.days item=day}
			{if $day.counter > 0}
				<tr{cycle values=', class="highlight_row"'}>
					<td width="60">
						{$day.day}. {$dl_stat.month}
					</td>
					<td width="30">
						<strong>{$day.counter}</strong>
					</td>
					<td>
						<div style="border:1px solid #000; background-color:#CCC; height:5px; width:{$day.counter / $dl_stat.max * 300}px;">
						</div>
					</td>
				</tr>
			{/if}
		{/foreach}
	</table>
{/if}

{if count($dl_stat.last) > 0}
	<div class="headline">{$lang.downloads_last}</div>
	<table width="100%" border="0">
		{foreach from=$dl_stat.last item=item}
			<tr>
				<td width="15%">{$item.timestamp|date_format:"%e. %B %H:%M"}</td>
				<td>
					<a href="{makeurl mod='media' downloadid=$item.downloadid categoryid=$item.categoryid}">
						{$item.name}
					</a>
				</td>
			</tr>
		{/foreach}
	</table>
{/if}