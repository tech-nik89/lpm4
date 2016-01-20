<div class="headline">{$lang.newmovie}</div>

<form action="" method="post">
	<p>
		{$lang.movie}:
		<select name="file">
			{foreach from=$files item=file}
				<option value="{$file}">{$file}</option>
			{/foreach}
		</select>
	</p>
	<p>
		<input type="submit" name="btnAdd" value="{$lang.add}" />
	</p>
</form>