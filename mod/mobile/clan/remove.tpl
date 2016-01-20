<div class="headline">{$lang.removeclan}</div>

<form action="" method="post">
	<p>{$lang.removeclan_confirm}</p>
	<p>
		<input type="submit" name="yes" value="{$lang.yes}" />
		<input type="button" name="no" value="{$lang.no}" onclick="document.location.href='{$url.no}'" />
	</p>
</form>