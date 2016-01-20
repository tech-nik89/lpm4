{if $calendar_box_show_counter}
	<p>
		<strong>{$lang.current_event}</strong><br />
		<a href="{$calendar_box_current.url}">{$calendar_box_current.title}</a>
	</p>
	<p style="font-size:2.0em; font-family:Courier New; font-weight:bold;" id="CountdownOutput"></p>
	
	<div id="calendar_box_current_event" style="display:none;">
		&nbsp;
	</div>
	<script type="text/javascript">
		var seconds;
		var countdownStarted = false;

		function startTimer(newseconds) {
			seconds = newseconds;
			countdown();
		};

		function countdown() {
			var tmpSeconds = seconds;
			var hours = Math.floor(tmpSeconds / 3600);
			hours = (hours < 10 ? "0" : "") + hours;
			tmpSeconds = seconds % 3600;
			var minutes = Math.floor(tmpSeconds / 60);
			minutes = (minutes < 10 ? "0" : "") + minutes;
			tmpSeconds = seconds % 60;
			tmpSeconds = (tmpSeconds < 10 ? "0" : "") + tmpSeconds;
			
			var clock;
			if (hours > 0) {
				clock = hours + "h " + minutes + ":" + tmpSeconds;
			}
			else {
				var clock = minutes + ":" + tmpSeconds;
			}
			$("#CountdownOutput").html(clock);
			
			if(seconds <= 0) {
				
			} else {
				seconds = seconds -1;
				window.setTimeout("countdown()", 1000);
			}
		}

		function refreshCalendarBox() {
			$("#calendar_box_current_event").load('ajax_request.php?mod=calendar&file=current_event.box.ajax');
		}
		refreshCalendarBox();
		window.setInterval("refreshCalendarBox()", {$calendar_box_refresh_time * 1000});
		
	</script>
{/if}

{foreach from=$calendar_box_entries item=entry}
<p>
	&raquo; <a href="{$entry.url}">{$entry.title}</a><br />
	{$entry.timeleft}
</p>
{/foreach}