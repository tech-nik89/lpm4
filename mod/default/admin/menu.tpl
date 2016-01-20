{if $action == ""}
	
	<div class="headline">{$lang.menu_editor}</div>
	
	<fieldset>
		<legend>{$lang.filter}</legend>
		<form action="" method="post">
			<table width="100%" border="0">
				<tr>
					<td>
						{$lang.menu_language_filter}:
					</td>
					<td>
						<select name="filter_language" style="width:200px;">
							<option value="">-</option>
							{foreach from=$languages item=item}
								<option value="{$item}"{if $filter_language == $item} selected="selected"{/if}>{$item}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td>{$lang.menu_domain_filter}:</td>
					<td>
						<select name="filter_domain" style="width:200px;">
							<option value="0">{$lang.all}</option>
							{foreach from=$dlist item=domain}
								<option value="{$domain.domainid}"{if $filter_domain == $domain.domainid} selected="selected"{/if}>{$domain.name}</option>
							{/foreach}
						</select>
						<input type="submit" name="do_filter" value="{$lang.go}" />
					</td>
				</tr>
			</table>
			</form>
		<form action="" method="post">
	</fieldset>
	<div align="right" style="padding:8px;">
		<a href="{makeurl mod='admin' mode='menu' action='add'}">{$lang.add}</a>
	</div>
	<div align="right">
		<span style="margin-right:25px;">{$lang.order}</span>
		{$lang.delete}
	</div>
	<div>
		{foreach from=$m item=menuitem}
			{include file='../mod/default/admin/menu.treeelement.tpl' item=$menuitem}
		{/foreach}
	</div>
	
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
	
	</form>
	
{/if}