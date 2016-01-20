<p>
	<strong>{$lang.current_event}</strong><br />
	<a href="{$calendar_box_current.url}">{$calendar_box_current.title}</a>
</p>
<p style="font-size:2.5em; font-family:Courier New; font-weight:bold;">
	{if $calendar_box_current.time_left_hours > 0}{$calendar_box_current.time_left_hours}h {/if}
	{if $calendar_box_current.time_left_minutes < 10}0{/if}{$calendar_box_current.time_left_minutes}:{if $calendar_box_current.time_left_seconds < 10}0{/if}{$calendar_box_current.time_left_seconds}
</p>