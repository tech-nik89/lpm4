<div class="headline">{$lang.server}</div>

<form action="" method="post">

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<td width="25%">{$lang.name}:</td>
			<td><input type="text" name="name" value="{$server.name}" /></td>
		</tr>
		
		<tr>
			<td>{$lang.description}:</td>
			<td><textarea name="description" cols="30" rows="10" style="width:100%; height:100px;">{$server.description}</textarea></td>
		</tr>
		
		<tr>
			<td>{$lang.game}:</td>
			<td><input type="text" name="game" value="{$server.game}" /></td>
		</tr>
		
		<tr>
			<td>GameQ:</td>
			<td>
				<select name="gameq">
					<option value="">-</option>
					{foreach from=$games item=game}
						<option{if $game.gameq == $server.gameq} selected="selected"{/if} value="{$game.gameq}">{$game.gameq} ( {$game.name} )</option>
					{/foreach}
				</select>
			</td>
		</tr>
		
		<tr>
			<td>{$lang.ipadress}:</td>
			<td><input type="text" name="ipadress" value="{$server.ipadress}" /></td>
		</tr>
		
		<tr>
			<td>{$lang.port}:</td>
			<td><input type="text" name="port" value="{$server.port}" /></td>
		</tr>
		
	</table>

	<p><input type="submit" name="save" value="{$lang.save}" /></p>

</form>