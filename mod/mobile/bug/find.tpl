<div class="headline">{$lang.find}</div>

<form action="{$find_url}" method="post">
	<p>
		{$lang.search_for}:
		<input type="text" style="width:300px;" name="search_string" />
		<input type="submit" name="find" value="{$lang.find}" />
	</p>
</form>