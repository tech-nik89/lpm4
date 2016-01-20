<div class="headline">{$lang.menu_entry}</div>

<form action="" method="post">
	<table width="100%" border="0">
		<tr>
			<td width="150">
				{$lang.name}:
			</td>
			<td>
				<input type="text" name="title" value="{$entry.title}" style="width:100%;" />
			</td>
		</tr>
		<tr class="highlight_row">
			<td>
				{$lang.mod} / {$lang.content}:
			</td>
			<td>
				<select name="mod" style="width:100%;">
					<option value="headline">{$lang.headline}</option>
					<optgroup label="{$lang.mods}">
						{foreach from=$modlist item=item}
							<option value="{$item.mod}"{if $item.mod == $entry.mod} selected="selected"{/if}>{$item.mod}</option>
						{/foreach}
					</optgroup>
					{if count($pagelist) > 0}
						<optgroup label="{$lang.static_pages}">
							{foreach from=$pagelist item=item}
								<option value="{$item.mod}"{if $item.mod == $entry.mod} selected="selected"{/if}>{$item.mod}</option>
							{/foreach}
						</optgroup>
					{/if}
					{if count($formlist) > 0}
						<optgroup label="{$lang.forms}">
							{foreach from=$formlist item=item}
								<option value="{$item.mod}"{if $item.mod == $entry.mod} selected="selected"{/if}>{$item.mod}</option>
							{/foreach}
						</optgroup>
					{/if}
				</select>
			</td>
		</tr>
		<tr>
			<td>
				{$lang.requires_login}:
			</td>
			<td>
				<input type="checkbox" name="requires_login" value="1"{if $entry.requires_login == 1} checked="checked"{/if} />
			</td>
		</tr>
		<tr class="highlight_row">
			<td>
				{$lang.assigned_group}:
			</td>
			<td>
				<select name="assigned_groupid" style="width:100%;">
					{foreach from=$groups item=group}
						<option value="{$group.groupid}"{if $group.groupid == $entry.assigned_groupid} selected="selected"{/if}>{$group.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>
				{$lang.parent}:
			</td>
			<td>
				<select name="parentid" style="width:100%;">
					<option value="0">-</option>
					{foreach from=$m item=item}
						{include file='../mod/default/admin/menu.entry.item.tpl' item=$item entry=$entry depth=1}
					{/foreach}
				</select>
			</td>
		</tr>
		<tr class="highlight_row">
			<td>
				{$lang.language}:
			</td>
			<td>
				<select name="language" style="width:100%;">
					<option value="">-</option>
					{foreach from=$languages item=item}
						<option value="{$item}"{if $entry.language == $item} selected="selected"{/if}>{$item}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>
				{$lang.startpage}:
			</td>
			<td>
				<input type="checkbox" name="startpage" value="1"{if $entry.home == 1} checked="checked"{/if} />
			</td>
		</tr>
		<tr class="highlight_row">
			<td>
				{$lang.template}:
			</td>
			<td>
				<select name="template" style="width:100%;">
					<option value="">-</option>
					{foreach from=$tlist item=item}
						<option value="{$item}"{if $entry.template == $item} selected="selected"{/if}>{$item}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr class="highlight_row">
			<td>
				{$lang.domain}:
			</td>
			<td>
				<select name="domainid" style="width:100%;">
					<option value="0">{$lang.all}</option>
					{foreach from=$dlist item=item}
						<option value="{$item.domainid}"{if $entry.domainid == $item.domainid} selected="selected"{/if}>{$item.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	</table>
	<p>
		<input type="submit" name="save" value="{$lang.save}"{if $locked} disabled="disabled"{/if} />
	</p>
</form>