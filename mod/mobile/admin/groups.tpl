{if $action == ""}

<div class="headline">{$lang.groups}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th>{$lang.group}</th>
		<th>{$lang.description}</th>
		<th>{$lang.display}</th>
		<th width="250">{$lang.options}</th>
	</tr>
	
	{section name=groups loop=$groups}
	
	<tr class="{cycle values=',highlight_row'}">
		<td>{$groups[groups].name}</td>
		<td>{$groups[groups].description}</td>
		<td>{if $groups[groups].display == 1}{$lang.yes}{else}{$lang.no}{/if}</td>
		<td><a href="{$groups[groups].url_memberships}">{$lang.options_memberships}</a> | <a href="{$groups[groups].url_edit}">{$lang.edit}</a> | <a href="{$groups[groups].url_remove}">{$lang.remove}</a></td>
	</tr>
	
	{/section}
	
</table>

<div class="headline">{$lang.group_new}</div>

<form action="" method="post">
		<table border="0" width="100%">
			<tr>
				<td width="20%">{$lang.name}:</td>
				<td><input type="text" name="name" value="" /></td>
			</tr>
			<tr>
				<td>{$lang.description}:</td>
				<td><input type="text" name="description" value="" style="width:100%;" /></td>
			</tr>
			<tr>
				<td>{$lang.display}:</td>
				<td><input type="checkbox" name="display" value="1" /></td>
			</tr>
		</table>
		<p><input type="submit" name="group_new" value="{$lang.add}" /></p>
	</form>

{/if}

{if $action == "edit"}
	
	<div class="headline">{$lang.group_details}</div>
	
	<form action="" method="post">
		<table border="0" width="100%">
			<tr>
				<td width="20%">{$lang.name}:</td>
				<td><input type="text" name="name" value="{$group.name}" /></td>
			</tr>
			<tr>
				<td>{$lang.description}:</td>
				<td><input type="text" name="description" value="{$group.description}" style="width:100%;" /></td>
			</tr>
			<tr>
				<td>{$lang.display}:</td>
				<td><input type="checkbox" name="display" value="1"{if $group.display == 1} checked="checked"{/if} /></td>
			</tr>
		</table>
		<p><input type="submit" name="group_save" value="{$lang.save_group_details}" /></p>
	</form>
	
	<div class="headline">{$lang.rights}</div>
	
	{literal}
	<script language="javascript" type="text/javascript">
		
		function selectAll()
		{
		{/literal}
		{section name=i loop=$rightlist}
			document.getElementById('{$rightlist[i].mod}_{$rightlist[i].name}').checked = true;
		{/section}
		{literal}
		}
		
		function deselectAll()
		{
		{/literal}
		{section name=i loop=$rightlist}
			document.getElementById('{$rightlist[i].mod}_{$rightlist[i].name}').checked = false;
		{/section}
		{literal}
		}
		
	</script>
	{/literal}
	
    <form action="" method="post">
			
        <table width="100%" border="0" cellpadding="5" cellspacing="1">
            
            <tr>
                <th width="20"><input type="checkbox" id="MasterCheckBox" onclick="{literal}if(document.getElementById('MasterCheckBox').checked){selectAll();}else{deselectAll();}{/literal}" value="1" /></th>
                <th width="15%">{$lang.mod}</th>
                <th width="15%">{$lang.right}</th>
                <th>{$lang.description}</th>
            </tr>
            
            {section name=i loop=$rightlist}
            
            <tr class="{cycle values=',highlight_row'}">
                <td>
                <input type="checkbox" name="{$rightlist[i].mod}_{$rightlist[i].name}" value="1" 
                	id="{$rightlist[i].mod}_{$rightlist[i].name}"{if $rightlist[i].isallowed == 1} checked="checked"{/if} />
                    
                </td>  
                <td><label for="{$rightlist[i].mod}_{$rightlist[i].name}">{$rightlist[i].mod}</label></td>
                <td><label for="{$rightlist[i].mod}_{$rightlist[i].name}">{$rightlist[i].name}</label></td>
                <td><label for="{$rightlist[i].mod}_{$rightlist[i].name}">{$rightlist[i].description}</label></td>
            </tr>
            
            {/section}
			
        </table>
		
		<div style="padding-top:15px;">
			<input type="submit" name="rights_save" value="{$lang.save_group_rights}" />
        </div>
		
    </form>
    
{/if}