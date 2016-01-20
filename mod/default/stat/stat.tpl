
{if $st.config.stats_enabled}
	<link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery.jqplot.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery-ui/examples.css" />

	<!-- BEGIN: load jqplot -->
	<!--[if IE]><script language="javascript" type="text/javascript" src="js/jquery-ui/excanvas.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="js/jquery-ui/jquery.jqplot.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui/plugins/jqplot.categoryAxisRenderer.min.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui/plugins/jqplot.barRenderer.min.js"></script>

	<script language="javascript" type="text/javascript" src="js/jquery-ui/plugins/jqplot.pieRenderer.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui/plugins/jqplot.highlighter.js"></script>
	<script language="javascript" type="text/javascript" src="js/jquery-ui/plugins/jqplot.pointLabels.js"></script>

	<!-- END: load jqplot -->
	  
	<style type="text/css" media="screen">
		.jqplot-axis {
		  font-size: 1em;
		}
		.jqplot-legend {
		  font-size: 1em;
		}
		.jqplot-target {
			margin-bottom: 1em;
		}
		  
		.note {
			font-size: 0.8em;
		}
		  div.jqplot-target { margin-bottom: 0px; margin-top: 0px;}
	</style>
	  
	<script type="text/javascript" language="javascript">
		
		{if $st.config.visitors_year_enabled}
			$(document).ready(function(){
				$.jqplot.config.enablePlugins = true;

				var years = [];
					{foreach from=$st.table_this_year.elements item=year}
						years.push({$year.value});
					{/foreach}
				
				var yearticks = [];
					{foreach from=$st.table_this_year.elements item=year}
						yearticks.push('{$year.month}');
					{/foreach}
				
				p_viewsyear = $.jqplot('viewsyear', [years], {
					seriesDefaults:{
						renderer:$.jqplot.BarRenderer,
						pointLabels: { show: false, 
										formatString: '%d'}
					},
					axes: {
						xaxis: {
							renderer: $.jqplot.CategoryAxisRenderer,
							ticks: yearticks
						},  
						yaxis: {
							min: 0,
							max: {$st.table_this_year.max}*1.02,
							formatString: '%d'
						}
					},
					highlighter: { 
						show: true,
						tooltipAxes: 'y'
					}
				});
			});
		{/if}
		
		{if $st.config.visitors_month_enabled}
			$(document).ready(function(){
				var months = [];
					{foreach from=$st.table_this_month.elements item=month}
						months.push({$month.value});
					{/foreach}
				
				var monthticks = [];
					{foreach from=$st.table_this_month.elements item=month}
						monthticks.push('{$month.day}<br /><span{if $month.weekday == 'Sun'} style="color:#CC0000; font-weight:bold;"{/if}{if $month.weekday == 'Sat'} style="color:#CC0000;"{/if}>{$month.weekday|substr:0:1}</span>');
					{/foreach}
				
				p_viewsmonth = $.jqplot('viewsmonth', [months], {
					seriesDefaults:{
						renderer:$.jqplot.BarRenderer,
						pointLabels: { show: false, 
									formatString: '%d'
						},
						rendererOptions: {
							shadowOffset: 1
						}
					},
					axes: {
						xaxis: {
							renderer: $.jqplot.CategoryAxisRenderer,
							ticks: monthticks,
							formatString: '%s'
						},  
						yaxis: {
							min: 0,
							max: {$st.table_this_month.max}*1.02,
							formatString: '%d'
						}
					},
					highlighter: { 
						show: true,
						tooltipAxes: 'y'
					}
				});
			});
		{/if}
		
		
		{if $st.config.browseragent AND $st.table_browseragent}
			$(document).ready(function(){
				var browseragents = [];
					{foreach from=$st.table_browseragent item=browseragent}
						browseragents.push(['{$browseragent.name}', {$browseragent.value}]);
					{/foreach}		

				p_browseragents= $.jqplot('browseragents', [browseragents], {
					seriesDefaults:{
						renderer: $.jqplot.PieRenderer,
						rendererOptions: {
							showDataLabels: true
						}
					},
					legend: {
						show: true,
						location: 'ne',
						xoffset: 15,
						yoffset: 15
					}
				});
			});
		{/if}
		

	
		{if $st.config.os AND $st.table_os}		
			$(document).ready(function(){
				var os = [];
					{foreach from=$st.table_os item=os}
						os.push(['{$os.name}', {$os.value}]);
					{/foreach}		

				p_os= $.jqplot('os', [os], {
					seriesDefaults:{
						renderer: $.jqplot.PieRenderer,
						rendererOptions: {
							showDataLabels: true
						}
					},
					legend: {
						show: true,
						location: 'ne',
						xoffset: 15,
						yoffset: 15
					},
					highlighter: {
						show: false
					}
				});
			});
		{/if}
		
		{if $st.config.referer AND $st.table_referer}		
			$(document).ready(function(){
				var referers = [];
					{foreach from=$st.table_referer item=referer}
						referers.push(['{$referer.name}', {$referer.value}]);
					{/foreach}		

				p_referer= $.jqplot('referer', [referers], {
					seriesDefaults:{
						renderer: $.jqplot.PieRenderer,
						rendererOptions: {
							showDataLabels: true
						}
					},
					legend: {
						show: true,
						location: 'ne',
						xoffset: 15,
						yoffset: 15
					},
					highlighter: {
						show: false
					}
				});
			});
		{/if}
		</script>
		
	<div class="headline">{$lang.stat}</div>
		
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td width="25%">{$lang.user_online}:</td>
			<td width="25%">{$st.user_online}</td>
			<td width="25%">{$lang.visitors_today}:</td>
			<td>{$st.visitors_today}</td>
		</tr>
		<tr>
			<td>{$lang.visitors_yesterday}:</td>
			<td>{$st.visitors_yesterday}</td>
			<td>{$lang.visitors_this_month}:</td>
			<td>{$st.visitors_this_month}</td>
		</tr>
		<tr>
			<td>{$lang.visitors_overall}:</td>
			<td>{$st.visitors_overall}</td>
			<td>{$lang.visitors_per_day}:</td>
			<td>{$st.visitors_per_day}</td>
		</tr>
		<tr>
			<td>{$lang.running_since}:</td>
			<td>{$st.running_since|date_format:"%d.%m.%Y"}</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
{/if}
	
<div class="headline">{$lang.user_online}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
    <tr>
    	<th>{$lang.nickname}</th>
        <th>{$lang.prename}</th>
        <th>{$lang.duration}</th>
    </tr>
    
    {section name=i loop=$st.visitor_list}
    <tr>
    	<td><a href="{$st.visitor_list[i].url}">{$st.visitor_list[i].nickname}</a></td>
        <td>{$st.visitor_list[i].prename}</td>
        <td>{$st.visitor_list[i].duration_str}</td>
    </tr>
    {/section}
    
</table>

<div class="headline">{$lang.lastseen}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
    <tr>
    	<th>{$lang.nickname}</th>
        <th>{$lang.prename}</th>
        <th>&nbsp;</th>
    </tr>
    
    {section name=i loop=$st.lastseen}
    <tr>
    	<td><a href="{$st.lastseen[i].url}">{$st.lastseen[i].nickname}</a></td>
        <td>{$st.lastseen[i].prename}</td>
        <td>{$st.lastseen[i].timeelapsed}</td>
    </tr>
    {/section}
    
</table>

{if $st.config.stats_enabled}
	{assign var='y' value=$st.running_since|date_format:"%Y"}
	<div class="headline">{$lang.change_year_and_month}</div>
	<form action="" method="post">
		<table width="100%" border="0">
			<tr>
				<td width="100">{$lang.year}:</td>
				<td>{html_select_date display_months=false display_days=false start_year=$y time=$current_timestamp}</td>
			</tr>
			<tr>
				<td>{$lang.month}:</td>
				<td>
					{html_select_date display_years=false display_days=false time=$current_timestamp}
					<input type="submit" name="btnChangeYearAndMonth" value="{$lang.go}" />
				</td>
			</tr>
		</table>
	</form>
	
	<script language="JavaScript"><!--
	
		{if $st.config.visitors_year_enabled}
			document.write('<div class="headline">{$lang.this_year} ({$st.this_year_name})</div>');
			document.write('<div id="viewsyear" style="width:100%; height:300px;"></div>');
		{/if}
		
		{if $st.config.visitors_month_enabled}
			document.write('<div class="headline">{$lang.this_month} ({$st.this_month_name})</div>');
			document.write('<div id="viewsmonth" style="width:100%; height:300px;"></div>');
		{/if}
		
		{if $st.config.browseragent AND $st.table_browseragent}
			document.write('<div class="headline">{$lang.browseragents}</div>');
			document.write('<div id="browseragents" style="width:100%; height:300px;"></div>');
		{/if}

		{if $st.config.os AND $st.table_os}
			document.write('<div class="headline">{$lang.os}</div>');
			document.write('<div id="os" style="width:100%; height:300px;"></div>');
		{/if}
		
		{if $st.config.referer AND $st.table_referer}
			document.write('<div class="headline">{$lang.referer}</div>');
			document.write('<div id="referer" style="width:100%; height:300px;"></div>');
		{/if}
	//--></script>
	
	<div class="headline">{$lang.export_stat}</div>
	<p>
		<a target="_blank" href="{$url.export}">{$lang.download}</a>
	</p>

	<noscript>
		{if $st.config.visitors_month_enabled}
			<div class="headline">{$lang.this_month} ({$st.this_month_name})</div>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top"><strong>{$st.table_this_month.max}</strong></td>
					{foreach from=$st.table_this_month.elements item=month}
						<td valign="bottom" align="center" rowspan="2" style="width:22px;">
							<div style="width:8px; height:{($month.value * 150) / $st.table_this_month.max}px; background-color:#666666;" title="{$month.value} {$lang.visitors}"></div>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td valign="bottom"><strong>0</strong></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					{foreach from=$st.table_this_month.elements item=month}
						<td align="center" valign="top">
							{$month.day}.
						</td>
					{/foreach}
				</tr>
				<tr>
					<td>&nbsp;</td>
					{foreach from=$st.table_this_month.elements item=month}
						<td align="center" valign="top">
							<span{if $month.weekday == 'Sun'} style="color:#CC0000; font-weight:bold;"{/if}{if $month.weekday == 'Sat'} style="color:#CC0000;"{/if}>
								{$month.weekday|substr:0:1}
							</span>
						</td>
					{/foreach}
				</tr>
			</table>
		{/if}

		{if $st.config.visitors_year_enabled}
			<div class="headline">{$lang.this_year} ({$st.this_year_name})</div>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top"><strong>{$st.table_this_year.max}</strong></td>
					{foreach from=$st.table_this_year.elements item=year}
						<td valign="bottom" align="center" rowspan="2" style="width:55px;">
							<div style="width:8px; height:{($year.value * 150) / $st.table_this_year.max}px; background-color:#666666;" title="{$year.value} {$lang.visitors}"></div>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td valign="bottom"><strong>0</strong></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					{foreach from=$st.table_this_year.elements item=year}
						<td align="center" valign="top">
							{$year.month}
						</td>
					{/foreach}
				</tr>
			</table>
		{/if}
		
		{if $st.config.browseragent AND $st.table_browseragent}
			<div class="headline">{$lang.browseragents}</div>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top"><strong>{$st.table_browseragent[0].value}</strong></td>
					{foreach from=$st.table_browseragent item=browseragent}
						<td valign="bottom" align="center" rowspan="2" style="width:60px;">
							<div style="width:8px; height:{($browseragent.value * 150) / $st.table_browseragent[0].value}px; background-color:#666666;" title="{$browseragent.value}"></div>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td valign="bottom"><strong>0</strong></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					{foreach from=$st.table_browseragent item=browseragent}
						<td align="center" valign="top">
							{$browseragent.name}
						</td>
					{/foreach}
				</tr>
			</table>
		{/if}
		
		{if $st.config.os AND $st.table_os}
			<div class="headline">{$lang.os}</div>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top"><strong>{$st.table_os[0].value}</strong></td>
					{foreach from=$st.table_os item=os}
						<td valign="bottom" align="center" rowspan="2" style="width:60px;">
							<div style="width:8px; height:{($os.value * 150) / $st.table_os[0].value}px; background-color:#666666;" title="{$os.value}"></div>
						</td>
					{/foreach}
				</tr>
				<tr>
					<td valign="bottom"><strong>0</strong></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					{foreach from=$st.table_os item=os}
						<td align="center" valign="top">
							{$os.name}
						</td>
					{/foreach}
				</tr>
			</table>
		{/if}
		
		{if $st.config.referer AND $st.table_referer}
			<div class="headline">{$lang.referer}</div>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						&nbsp;
					</td>
					<td align="left">
						<strong>0</strong>
					</td>
					<td align="right">
						<strong>{$st.table_referer[0].value}</strong>
					</td>
				</tr>
				{foreach from=$st.table_referer item=referer}
					<tr>
						<td>
							{$referer.name}
						</td>
						<td valign="middle" align="left" colspan="2" style="width:150px">
							<div style="height:8px; width:{($referer.value * 150) / $st.table_referer[0].value}px; background-color:#666666;" title="{$referer.value}"></div>
						</td>
					</tr>
				{/foreach}
			</table>
		{/if}
	</noscript>
{/if}
	
	
{if $isallowed}
	<div class="headline">{$lang.options}</div>
	<form action="" method="post">
		<p>{$lang.count_myself_descr}</p>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>				
					{if $count_myself}
						<p><span style="color:#CC0000;">{$lang.count_myself_enabled}</span></p>
						<p><input type="submit" name="not_count_myself" value="{$lang.not_count_myself}" /></p>
					{else}
						<p><span style="color:#00AA00;">{$lang.count_myself_disabled}</span></p>
						<p><input type="submit" name="count_myself" value="{$lang.count_myself}" /></p>
					{/if}				
				</td>
				<td align="right">
					<p><input type="submit" name="clear_stat" value="{$lang.clear_stat}" onclick="return confirm('{$lang.clear_confirm}');" /></p>
				</td>
			</tr>
		</table>
	</form>
{/if}
