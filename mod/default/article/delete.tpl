<div class="headline">{$lang.delete}</div>
<p>{$lang.delete_question}</p>
<form action="" method="post">
	<p>
		<input type="submit" name="yes" value="{$lang.yes}" />
		<input type="button" name="no" value="{$lang.no}" onclick="location.href='{$no_url}'" />
	</p>
</form>