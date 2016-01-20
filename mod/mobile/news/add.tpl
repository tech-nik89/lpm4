<div class="headline">{$lang.news_add}</div>

<script type="text/javascript">
	
	function toggleBB() {
		if (!$("#divBBCodes").is(':visible')) {
			$("#divBBCodes").load('templates/default/bbcode.html');
			$("#divBBCodes").show('fast');
		}
		else {
			$("#divBBCodes").hide('fast');
		}
	}
	
</script>


<form action="" method="post">
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr class="highlight_row">
			<td width="130"><strong>{$lang.news_title}:</strong></td>
			<td><input type="text" name="title" style="width:100%;" /></td>
		</tr>
		<tr>
			<td><strong>{$lang.language}:</strong></td>
			<td>{html_options name=language values=$languages output=$languages}</td>
		</tr>
		<tr class="highlight_row">
			<td>&nbsp;</td>
			<td><textarea style="width:100%; height:250px;" name="text" id="textarea_id"></textarea></td>
		</tr>
	</table>
	
	<fieldset>
		<legend>{$lang.send}</legend>
		<table width="100%" border="0">
			<tr class="highlight_row">
				<td width="130"><strong>{$lang.send_to}:</strong></td>
				<td>
					<select name="send_to" id="send_to">
						<option value="-1">{$lang.nobody}</option>
						<option value="0">{$lang.all}</option>
						{foreach from=$groups item=group}
							<option value="{$group.groupid}">{$group.name}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td><strong>{$lang.copy_to_me}:</strong></td>
				<td><input type="checkbox" name="copy_to_me" id="copy_to_me" value="true" /></td>
			</tr>
			<tr class="highlight_row">
				<td><strong>{$lang.send_pm}:</strong></td>
				<td><input type="checkbox" name="send_pm" id="send_pm" value="true" /></td>
			</tr>
			<tr>
				<td><strong>{$lang.send_mail}:</strong></td>
				<td><input type="checkbox" name="send_mail" id="send_mail" value="true" /></td>
			</tr>
		</table>
	</fieldset>
	<p align="right">
		<a href="javascript:toggleBB();">BB Codes</a>
	</p>
	<div id="divBBCodes" style="display:none;"></div>
	<p><input type="submit" name="save" value="{$lang.add}" /></p>
	
</form>

<!--
<script type="text/javascript">
	CKEDITOR.replace( 'textarea_id', {
		skin : 'office2003'
	});
</script>
-->