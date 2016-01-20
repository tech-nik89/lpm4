<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>
<link type="text/css" href="js/jquery-ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
{literal}
<script type="text/javascript">
	$(function() {
		$("#date").datepicker();
	});
</script>
{/literal}

<div class="headline">{$lang.switch_date}</div>
<form method="post" action="">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td width="200"><strong>{$lang.current_date}:</strong></td>
			<td>{$current_date} {$lang.o_clock}</td>
		</tr>
		<tr>
			<td><strong>{$lang.switch_to}:</strong></td>
			<td><input type="text" name="date" id="date" value="{$current_day}" />
				{html_select_time display_seconds=false display_minutes=false display_hours=false}
			</td>
		</tr>	
	</table>
	<p><input type="submit" name="go" value="{$lang.ok}" /></p>
</form>