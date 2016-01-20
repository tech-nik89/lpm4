<h1>{if $action == 'add'}{$lang.add}{else}{$lang.edit}{/if}</h1>

<form action="" method="post">
	<table width="100%" border="0">
		<colgroup>
			<col width="150" />
			<col width="*" />
		</colgroup>
		<tr>
			<td>{$lang.name}:</td>
			<td>
				<input type="text" name="name" id="name" value="{$item.name}" style="width:85%;" />
				<a id="btnMetaPresets" href="#metapresets" style="float:right;">{$lang.presets}</a>
				<div style="clear:right;"></div>
			</td>
		</tr>
		<tr>
			<td>HTTP-Equiv:</td>
			<td>
				<input type="text" name="http_equiv" id="http_equiv" value="{$item.http_equiv}" style="width:85%;" />
				<a id="btnEquivPresets" href="#equivpresets" style="float:right;">{$lang.presets}</a>
				<div style="clear:right;"></div>
			</td>
		</tr>
		<tr>
			<td>{$lang.language}:</td>
			<td>
				<select name="language" style="width:100%;">
					<option value="">{$lang.all}</option>
					{foreach from=$languages item=lng}
						<option value="{$lng}"{if $item.language == $lng} selected="selected"{/if}>{$lng}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$lang.domain}</td>
			<td>
				<select name="domainid" style="width:100%;">
					<option value="0">{$lang.all}</option>
					{foreach from=$dlist item=d}
						<option value="{$d.domainid}"{if $item.domainid == $d.domainid} selected="selected"{/if}>{$d.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$lang.content}:</td>
			<td>
				<input type="text" name="content" value="{$item.content}" style="width:100%;" />
			</td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{if $action == 'add'}{$lang.add}{else}{$lang.edit}{/if}" />
	</p>
</form>

<div style="display:none;">
	<div id="metapresets">
		<h1>{$lang.presets}</h1>
		<ul>
			{foreach from=$meta_names item=preset}
				<li>
					<a href="javascript:void(0);" onclick="$('#name').val('{$preset}'); $.fancybox.close();">{$preset}</a>
				</li>
			{/foreach}
		</ul>
	</div>
	<div id="equivpresets">
		<h1>{$lang.presets}</h1>
		<ul>
			{foreach from=$equiv_names item=preset}
				<li>
					<a href="javascript:void(0);" onclick="$('#http_equiv').val('{$preset}'); $.fancybox.close();">{$preset}</a>
				</li>
			{/foreach}
		</ul>
	</div>
</div>

<script type="text/javascript">
	$('#btnMetaPresets').fancybox();
	$('#btnEquivPresets').fancybox();
</script>