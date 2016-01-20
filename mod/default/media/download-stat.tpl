<img src="mod/default/media/working.gif" border="0" style="display:none;" />
<script type="text/javascript">
	var mouseX = 0, mouseY = 0;
	var infoVisible = false;
	
	$(document).ready(function() {    
		$(document).mousemove(function(e)
		{       
			mouseX=e.pageX;
			mouseY=e.pageY;
		});
	}) 
	
	function showInfo(day) {
		if (infoVisible) {
			hideInfo();
			return;
		}
		
		var info = $("#divInfo");
		info.html('<img src="mod/default/media/working.gif" border="0" />').fadeIn(500);
		info.load('ajax_request.php?mod=media&file=top-dls-per-day.ajax&start_day='+day+'&end_day='+day+'&month={$dl_stat.month_value}&year={$dl_stat.year}');
		
		var divInfo = document.getElementById("divInfo");
		
		var left = mouseX + 20;
		var top = mouseY + 10;
		
		divInfo.style.left = left+'px';
		divInfo.style.top = top+'px';
		divInfo.style.position = 'absolute';
		divInfo.style.display = 'block';
		
		infoVisible = true;
	}
	
	function hideInfo() {
		infoVisible = false;
		$("#divInfo").fadeOut(500);
	}
	
</script>
<div id="divInfo" style="width:320px; background-color:#FFF; border:1px solid #CCC; padding:3px; display:none; cursor:pointer;" onclick="hideInfo();"></div>

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
				{html_select_date start_year=$first_download_year display_days=false time=$selected_month_ts}
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
	{assign var='overall' value=0}
	<table border="0" width="100%">
		{foreach from=$dl_stat.days item=day}
			{if $day.counter > 0}
				<tr{cycle values=', class="highlight_row"'}>
					<td width="120">
						{$day.day}. {$dl_stat.month} {$dl_stat.year}
					</td>
					<td width="40">
						<strong>{$day.counter}</strong>
					</td>
					<td>
						{if !$smarty.get.downloadid > 0}
							<div style="border:1px solid #000; background-color:#CCC; height:7px; width:{$day.counter / $dl_stat.max * 98}%; cursor:pointer;" 
								onclick="showInfo({$day.day});" class="divDlBar">
							</div>
						{else}
							<div style="border:1px solid #000; background-color:#CCC; height:7px; width:{$day.counter / $dl_stat.max * 98}%;" class="divDlBar">
							</div>
						{/if}
					</td>
				</tr>
				{assign var='overall' value=$overall+$day.counter}
			{/if}
		{/foreach}
		<tr>
			<td>
				<strong>{$lang.sum}:</strong>
			</td>
			<td>
				<strong>{$overall}</strong>
			</td>
		</tr>
	</table>
	{if !$smarty.get.downloadid > 0}
		<div id="divTopThisMonth"></div>
		<script type="text/javascript">
			$("#divTopThisMonth").load('ajax_request.php?mod=media&file=top-dls-per-day.ajax&start_day=1&end_day={$dl_stat.month_last_day}&month={$dl_stat.month_value}&year={$dl_stat.year}&nolimit');
		</script>
	{/if}
{/if}
{if count($dl_stat.last) > 0}
	<div class="headline">{$lang.downloads_last}</div>
	<table width="100%" border="0">
		{foreach from=$dl_stat.last item=item}
			<tr>
				<td width="200">{$item.timestamp|date_format:"%e. %B %H:%M"} {$lang.o_clock}</td>
				<td>
					<a href="{makeurl mod='media' downloadid=$item.downloadid categoryid=$item.categoryid}">
						{$item.name}
					</a>
				</td>
			</tr>
		{/foreach}
	</table>
{/if}