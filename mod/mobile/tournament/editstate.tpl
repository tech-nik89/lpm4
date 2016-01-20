<div class="headline">{$lang.tournament_edit_state}</div>

<form action="" method="post">
	<p>
		<label>
			<input type="radio" name="state" value="0"{if $tournament.state == 0} checked="checked"{/if} />
			{$lang.state_inactive}
		</label>
		<br />
		<label>
			<input type="radio" name="state" value="1"{if $tournament.state == 1} checked="checked"{/if} />
			{$lang.state_joining}
		</label>
		<br />
		<label>
			<input type="radio" name="state" value="2"{if $tournament.state == 2} checked="checked"{/if} />
			{$lang.state_running}
		</label>
		<br />
		<label>
			<input type="radio" name="state" value="3"{if $tournament.state == 3} checked="checked"{/if} />
			{$lang.state_finished}
		</label>
	</p>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>