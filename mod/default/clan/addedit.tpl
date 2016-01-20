<div class="headline">{$lang.clan}</div>

<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
			<td>{$lang.name}</td>
			<td>
				<input type="text" name="name" value="{$clan.name}" style="width:100%;" />
			</td>
		</tr>
		<tr>
			<td>{$lang.prefix}</td>
			<td>
				<input maxlength="5" type="text" name="prefix" value="{$clan.prefix}" style="width:50px;"{if $mode=='edit'} readonly="readonly"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$lang.password}</td>
			<td>
				<input type="password" name="password" value="" style="width:100%;" />
			</td>
		</tr>
		<tr>
			<td>{$lang.description}</td>
			<td>
				<textarea name="description" cols="60" rows="12" style="width:100%;">{$clan.description}</textarea>
			</td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
</form>