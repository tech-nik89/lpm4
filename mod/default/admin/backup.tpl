<div class="headline">{$lang.backup}</div>

{literal}
<script type="text/javascript">
	function setSelectOptions(the_form, the_select, do_check)
	{
		var selectObject = document.forms[the_form].elements[the_select];
		var selectCount  = selectObject.length;

		for (var i = 0; i < selectCount; i++) {
			selectObject.options[i].selected = do_check;
		}

		return true;
	}
</script>
{/literal}

<form name="backup" action="" method="post">

<div style="float:left; width:50%;">
	<select name="tables[]" size="7" id="tables" style="width:100%;" multiple="multiple">
		{foreach from=$tables item=table}
			<option value="{$table}">{$table}</option>
		{/foreach}
	</select>
</div>

<div style="float:left; width:45%; padding-left:5px;">
	<input type="button" name="selectAll" value="{$lang.select_all}" onclick="setSelectOptions('backup', 'tables', true);" />
	<input type="button" name="deSelectAll" value="{$lang.deselect_all}" onclick="setSelectOptions('backup', 'tables', false);" />
	<p>
		<label>
			<input type="checkbox" name="download" value="1" />
			{$lang.download_backup}
		</label>
	</p>
	<p>&nbsp;</p>
	<p><input type="submit" name="doBackup" value="{$lang.do_backup}" /></p>
</div>

<div style="clear:both;"></div>

</form>

<div style="padding-top:5px;">
	<textarea name="backupOutput" style="width:100%; height:200px;">{$backup}</textarea>
</div>