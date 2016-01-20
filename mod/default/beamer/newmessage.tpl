<div class="headline">{$lang.newmessage}</div>

<form action="" method="post">
	<textarea cols="80" rows="12" name="txtMessage" style="width:100%;"{if $done} readonly="readonly"{/if}></textarea>
	<p>
		<input type="submit" name="btnAdd" value="{$lang.add}"{if $done} disabled="true"{/if} />
	</p>
</form>