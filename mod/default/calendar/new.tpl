<link type="text/css" href="js/jquery-ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<link type="text/css" href="js/jquery-ui/jquery-ui-1.7.2.custom.min.js" rel="stylesheet" />
{literal}
<script type="text/javascript">
	$(function() {
		$("#start_date").datepicker();
	});
	$(function() {
		$("#end_date").datepicker();
	});
</script>
{/literal}

<div class="headline">{$head}</div>

<form action="" method="post">

<table width="100%" border="0" cellspacing="1" cellpadding="5">
	<tr>
		<td width="200"><strong>{$lang.title}:</strong></td>
		<td><input type="text" name="title" id="title" value="{$entry.title}" style="width:100%;" /></td>
	</tr>
	<tr class="highlight_row">
		<td><strong>{$lang.language}:</strong></td>
		<td>
			<select name="language" style="width:100%;">
				<option value="">-</option>
				{foreach from=$languages item=language}
					<option value="{$language}"{if $language == $entry.language} selected="selected"{/if}>{$language}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td><strong>{$lang.start}:</strong></td>
		<td><input type="text" name="start_date" id="start_date" value="{$entry.start_date}" onchange="$('#end_date').val($('#start_date').val());" /> {html_select_time prefix=start_ display_seconds=false time=$entry.start minute_interval=5} {$lang.o_clock}</td>
	</tr>
	<tr class="highlight_row">
		<td><strong>{$lang.end}:</strong></td>
		<td><input type="text" name="end_date" id="end_date" value="{$entry.end_date}" /> {html_select_time prefix=end_ display_seconds=false time=$entry.end minute_interval=5} {$lang.o_clock}</td>
	</tr>
	<tr>
		<td><strong>{$lang.visibility}:</strong></td>
		<td><label><input type="radio" name="visibility" id="public" value="2" {if $entry.visible == 2}checked="checked"{/if} />
			{$lang.public} </label>
			<label><input type="radio" name="visibility" id="registered" value="1" {if $entry.visible == 1}checked="checked"{/if} />
			{$lang.registered} </label>
			<label><input type="radio" name="visibility" id="private" value="0" {if $entry.visible == 0}checked="checked"{/if} />
			{$lang.private}</label>
		</td>
	</tr>
	<tr class="highlight_row">
		<td><strong>{$lang.category}:</strong></td>
		<td>
			<select name="category" style="width:100%;">
				<option value="">-</option>
				{foreach from=$categories item=category}
					<option value="{$category.categoryId}"{if $entry.categoryId == $category.categoryId} selected="selected"{/if}>{$category.title}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr valign="top">
		<td><strong>{$lang.description}:</strong></td>
		<td><textarea name="description" id="description" style="width:100%;height:150px;">{$entry.description}</textarea></td>
	</tr>
</table>

<p><input type="submit" name="save" value="{$lang.save}" /></p>

</form>