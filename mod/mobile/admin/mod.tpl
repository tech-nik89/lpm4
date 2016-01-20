{if $selected == ""}
	<div class="headline">{$lang.mods_installed}</div>

	<table width="100%" border="0" cellpadding="5" cellspacing="1">

		<tr>
			<th width="25%">{$lang.mod}</th>
			<th width="25%">{$lang.config}</th>
			<th>{$lang.version}</th>
			<th><div align="right">{$lang.uninstall}</div></th>
		</tr>

		{section name=m loop=$modlist}
			
			<tr class="{cycle values=',highlight_row'}">
				<td>{$modlist[m].mod}</td>
				<td>
					{if $modlist[m].config_count > 0}
						<a href="{$modlist[m].config_url}">{$lang.config}</a> ({$modlist[m].config_count})
					{else}
						{$lang.config} ({$modlist[m].config_count})
					{/if}
				</td>
				<td>{$modlist[m].version}</td>
				<td>
					<div align="right">
						{if $modlist[m].mod != 'core' && $modlist[m].mod != 'admin' && $modlist[m].mod != 'login'
							&& $modlist[m].mod != 'contact' && $modlist[m].mod != 'maintenance' && $modlist[m].mod != 'stat'
							&& $modlist[m].mod != 'pmbox' && $modlist[m].mod != 'usercp' && $modlist[m].mod != 'content'
							&& $modlist[m].mod != 'favorites' && $modlist[m].mod != '404'}
							<a href="{$modlist[m].uninstall_url}">{$lang.uninstall_mod}</a>
						{else}
							&nbsp;
						{/if}
					</div>
				</td>
			</tr>
			
		{/section}
		
	</table>
	
	
	
	<div class="headline">{$lang.mods_available}</div>

	<table width="100%" border="0" cellpadding="5" cellspacing="1">

		<tr>
			<th width="25%">{$lang.mod}</th>
			<th>{$lang.version}</th>
			<th width="25%">
				<div align="right">{$lang.setup_mod}</div>
			</th>
		</tr>

		{section name=m loop=$available}
			
			<tr class="{cycle values=',highlight_row'}">
				<td>{$available[m].mod}</td>
				<td>{$available[m].version}</td>
				<td>
					<div align="right">
						<a href="{$available[m].setup}">{$lang.setup_mod}</a>
					</div>
				</td>
			</tr>
			
		{/section}	
		
	</table>
	
{else}
	
	<div class="headline">{$lang.mod} {$modul}</div>
	
	<form action="" method="post">
	
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
			
			<tr>
				<th width="25%">{$lang.key}</th>
                <th width="70">{$lang.type}</th>
				<th>{$lang.value}</th>
				<th>{$lang.description}</th>
			</tr>
			
			{section name=l loop=$configlist}
				<tr class="{cycle values=',highlight_row'}">
					<td>{$configlist[l].key}</td>
                    <td>({$configlist[l].type})</td>
					<td>
                  
                    {if $configlist[l].type == '' || $configlist[l].type == 'int' || $configlist[l].type == 'string'}
	                    <input type="text" name="value_{$configlist[l].key}" value="{$configlist[l].value}" />
                    {elseif $configlist[l].type == 'bool'}
                    	<!-- <input type="checkbox" name="value_{$configlist[l].key}" value="1" {if $configlist[l].value == 1} checked="checked"{/if} /> -->
                        
                        <label>
	                        <input type="radio" name="value_{$configlist[l].key}" value="1"{if $configlist[l].value == 1} checked="checked"{/if} />
                            {$lang.true}
                        </label>
                        
                        <label>
	                        <input type="radio" name="value_{$configlist[l].key}" value="0"{if $configlist[l].value == 0} checked="checked"{/if} />
                            {$lang.false}
                        </label>
                        
                    {elseif $configlist[l].type == 'text'}
                    	<textarea name="value_{$configlist[l].key}" style="width:100%; height:150px;">{$configlist[l].value}</textarea>
					{elseif $configlist[l].type == 'list'}
						<select name="value_{$configlist[l].key}">
						{foreach from=$configlist[l].list item=cfg}
							<option value="{$cfg}"{if $cfg == $configlist[l].value} selected="selected"{/if}>{$cfg}</option>
						{/foreach}
						</select>
                    {/if}

                    </td>
					 <td>{$configlist[l].description}</td>
				</tr>
			{/section}
			
		</table>
		
		<p>
			<input type="submit" name="save" value="{$lang.save}" />
		</p>
		
	</form>
	
{/if}