<div class="headline">{$lang.beamer_edit}</div>

<script type="text/javascript">
	
	function onChange(value) {
		if (value == 'NewMessage') {
			document.location.href = '{$NewMessageUrl}';
		}
		if (value == 'NewMovie') {
			document.location.href = '{$NewMovieUrl}';
		}
	}
	
	function openPopUp (beamerid) {
		newWindow = window.open('?mod=beamer&mode=run&beamerid='+beamerid+'&tpl=none', "abc", "width=800,height=600,status=yes,scrollbars=yes,resizable=yes");
		newWindow.focus();
	}
	
</script>

<form action="" method="post">
    <table width="100%" border="0" cellpadding="1" cellspacing="5">
        <tr>
            <th width="150">{$lang.add} / {$lang.remove}</th>
            <th width="50">{$lang.order}</th>
            <th width="50">{$lang.duration}</th>
            <th>{$lang.mod}</th>
        </tr>
        {foreach from=$list item=mod}
            <tr>
                <td><label><input type="checkbox" value="1" name="remove_{$mod.id}" /> {$lang.remove}</label></td>
                <td><input type="number" value="{$mod.order}" name="order_{$mod.id}" style="width:50px;" /></td>
                <td><input type="number" value="{$mod.duration}" name="duration_{$mod.id}" style="width:50px;" /></td>
                <td>
					{if $custom}
						<input type="text" value="" name="url_0" style="width:100%;" />
					{else}
						<select name="url_{$mod.id}" style="width:100%;">
							{foreach from=$available item=av}
								<option value="{$av.url}"{if $av.url == $mod.url} selected="selected"{/if}>{$av.name}</option>
							{/foreach}
						</select>
					{/if}
				</td>
            </tr>
        {/foreach}
		<tr>
            <td>{$lang.add}</td>
            <td><input type="number" value="0" name="order_0" style="width:50px;" /></td>
            <td><input type="number" value="5" name="duration_0" style="width:50px;" /></td>
            <td>
				{if $custom}
					<input type="text" value="" name="url_0" style="width:100%;" />
				{else}
					<select name="url_0" style="width:100%;" onchange="onChange(this.value);">
						{foreach from=$available item=av}
							<option value="{$av.url}">{$av.name}</option>
						{/foreach}
						<option value="-">---</option>
						<option value="NewMessage">{$lang.newmessage}</option>
						<!--<option value="NewMovie">{$lang.newmovie}</option>-->
					</select>
				{/if}
			</td>
        </tr>
    </table>
    
    <p>
		<input type="submit" name="save" value="{$lang.save}" />
    </p>
	
	<div class="headline">{$lang.options}</div>
	
	<p>
		<input type="button" value="{$lang.run}" onClick="javascript:openPopUp({$beamer.beamerid});" />
		<input type="submit" name="remove" value="{$lang.beamer_remove}" onclick="return window.confirm('{$lang.beamer_remove_ask}');" />
	</p>
	
</form>