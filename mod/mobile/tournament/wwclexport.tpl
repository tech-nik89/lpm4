{literal}
<script type="text/javascript">
	function setSelectOptions(the_form, the_select, do_check)
	{
		var selectObject = document.forms[the_form].elements[the_select];
		var selectCount  = selectObject.length;

		for (var i = 0; i < selectCount; i++) {
			selectObject.options[i].selected = do_check;
		}

		return true;
	}
</script>
{/literal}

<div class="headline">{$lang.wwcl_export} {$eventname}</div>

{if $eventname > -1}
	<form name="export" action="" method="post">
	<input type="hidden" name="eventid" value="{$eventid}"/>
	<div>
		<table>
			<tr>
				<td>
					{$lang.party_name}
				</td>
				<td>
					<input type="text" name="eventname" value="{$eventinformation.eventname}" size="30"/>
				</td>
			</tr>
			<tr>
				<td>
					{$lang.partyid}
				</td>
				<td>
					<input type="text" name="partyid" value="{$eventinformation.partyid}" size="30"/>
				</td>
			</tr>
			<tr>
				<td>
					{$lang.party_organizerid}
				</td>
				<td>
					<input type="text" name="organizerid" value="{$eventinformation.organizerid}" size="30"/>
				</td>
			</tr>
			<tr>
				<td>
					{$lang.party_city}
				</td>
				<td>
					<input type="text" name="partycity" value="{$eventinformation.partycity}" size="30"/>
				</td>
			</tr>
			<tr>
				<td>
					{$lang.wwcluserid}
				</td>
				<td>
					<select name="wwcluserid" id="wwcluserid" style="width: 200px;">
						<option value="-1">&nbsp;</option>
						{foreach from=$userfields item=field}
							<option value="{$field.fieldid}" {if $eventinformation.wwcliduserfield == $field.fieldid} selected="selected" {/if}>
								{$field.value}
							</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</table>
	</div>

	<div style="float:left; width:50%; padding-top:7px;">
		<select name="tournaments[]" size="8" id="tournaments" style="width:100%;" multiple="multiple">
			{foreach from=$tournamentlist item=tournament}
				<option value="{$tournament.tournamentid}">{$tournament.title}</option>
			{/foreach}
		</select>
	</div>

	<div style="float:left; width:45%; padding-left:7px; padding-top:7px;">
		<p>
			<input type="button" name="selectAll" value="{$lang.select_all}" onclick="setSelectOptions('export', 'tournaments', true);"/>
		</p>
		<p>
			<input type="button" name="deSelectAll" value="{$lang.deselect_all}" onclick="setSelectOptions('export', 'tournaments', false);" />
		</p>
		<p>
			<label>
				<input type="checkbox" name="download" value="1" />
				{$lang.download_backup}
			</label>
		</p>
		<p><input type="submit" name="doBackup" value="{$lang.make_wwclexport}" /></p>
	</div>

	<div style="clear:both;"></div>
	
	</form>
	<div style="padding-top:7px;">
		<textarea cols="50" rows="20" style="width:100%;">{$exportdata}</textarea>
	</div>
{else}
	<form name="selectevent" action="" method="post">
		<table>
			<tr>
				<td>
					{$lang.party_name}
				</td>
				<td>
					<select name="eventid">
						{foreach from=$partys item=party}
							<option value="{$party.eventid}">{$party.name}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="selectEvent" value="{$lang.select_event}"/>
				</td>
			</tr>
		</table>
	</form>
{/if}