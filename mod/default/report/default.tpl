<div class="headline">{$lang.reports}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="50%" valign="top">
			<fieldset>
				<legend>{$lang.user}</legend>
				<ul>
					<li>
						<a href="{$url.event_users|replace:'%eventid%':0|replace:'%mode%':all}" target="_blank">{$lang.all_users}</a>
					</li>
					{foreach from=$events item=event}
						<p><strong>{$event.name}</strong></p>
						<li>
							<a href="{$url.event_users|replace:'%eventid%':$event.eventid|replace:'%mode%':registered}" target="_blank">{$lang.registered_users}</a>
						</li>
						<li>
							<a href="{$url.event_users|replace:'%eventid%':$event.eventid|replace:'%mode%':payed}" target="_blank">{$lang.payed_users}</a>
						</li>
						<li>
							<a href="{$url.event_users|replace:'%eventid%':$event.eventid|replace:'%mode%':not_payed}" target="_blank">{$lang.not_payed_users}</a>
						</li>
						<li>
							<a href="{$url.event_users|replace:'%eventid%':$event.eventid|replace:'%mode%':payed_pre}" target="_blank">{$lang.payed_pre}</a>
						</li>
						<li>
							<a href="{$url.event_users|replace:'%eventid%':$event.eventid|replace:'%mode%':payed_box_office}" target="_blank">{$lang.payed_box_office}</a>
						</li>
					{/foreach}
				</ul>
			</fieldset>
		</td>
		<td valign="top">
			<form action="{$url.guestcards}" method="post" target="_blank">
				<fieldset>
					<legend>{$lang.guestcards}</legend>
					<p>
					{$lang.event}:
						<select name="eventid">
							{foreach from=$events item=event}
								<option value="{$event.eventid}">{$event.name}</option>
							{/foreach}
						</select>
					</p>
					<p>
						{$lang.show}:
						<select name="viewid" onchange="showSingleUsers();" id="viewid">
							{foreach from=$views item=view}
								<option value="{$view.viewid}">{$view.name}</option>
							{/foreach}
						</select>
					</p>
					<script type="text/javascript">
						function showSingleUsers() {
							if ($("#viewid").val() == '7') {
								$("#divSingleUsers").show();
							}
							else {
								$("#divSingleUsers").hide();
							}
						}
					</script>
					<div style="display:none;" id="divSingleUsers">
						{$lang.single_users}:
						<input type="text" name="single_users" value="" /><br />
						{$lang.seperate_ids}
					</div>
					<p>
						<input type="submit" name="show" value="{$lang.show}" />
					</p>
				</fieldset>
			</form>
		</td>
	</tr>
</table>