<div class="headline">{$lang.admin_long}</div>

<p>
{$lang.admin_info}
</p>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<th width="50%">{$lang.system}</th>
		<th>{$lang.user}</th>
	</tr>
	
	<tr>
		<td>{if $right.config}<a href="{$url.config}">{$lang.config}</a>{else}{$lang.config}{/if}</td>
		<td>{if $right.users}<a href="{$url.users}">{$lang.user}</a>{else}{$lang.user}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.mod}<a href="{$url.mod}">{$lang.mods}</a>{else}{$lang.mods}{/if}</td>
		<td>{if $right.users}<a href="{$url.newuser}">{$lang.user_add}</a>{else}{$lang.user_add}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.log}<a href="{$url.log}">{$lang.log_view}</a>{else}{$lang.log_view}{/if}</td>
		<td>{if $right.groups}<a href="{$url.groups}">{$lang.groups}</a>{else}{$lang.groups}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.sessions}<a href="{$url.sessions}">{$lang.sessions}</a>{else}{$lang.sessions}{/if}</td>
		<td>{if $right.users}<a href="{$url.circular}">{$lang.circular_mail}</a>{else}{$lang.circular_mail}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.backup}<a href="{$url.backup}">{$lang.backup}</a>{else}{$lang.backup}{/if}</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<td>{if $right.config}<a href="{$url.domains}">{$lang.domains}</a>{else}{$lang.domains}{/if}</td>
		<td>&nbsp;</td>
	</tr>
	
	<tr>
		<th>{$lang.administration}</th>
		<th>{$lang.content}</th>
	</tr>
	
	<tr>
		<td>{if $right.menu}<a href="{$url.menu}">{$lang.menu_editor}</a>{else}{$lang.menu_editor}{/if}</td>
		<td>{if $right.content}<a href="{$url.content}">{$lang.content}</a>{else}{$lang.content}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.boxes}<a href="{$url.boxes}">{$lang.boxes}</a>{else}{$lang.boxes}{/if}</td>
		<td>{if $right.comments}<a href="{$url.comments}">{$lang.comments}</a>{else}{$lang.comments}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.personal_fields}<a href="{$url.personal_fields}">{$lang.personal_fields}</a>{else}{$lang.personal_fields}{/if}</td>
		<td>{if $right.contact}<a href="{$url.contact}">{$lang.contact_requests}</a>{else}{$lang.contact_requests}{/if}</td>
	</tr>
	
	<tr>
		<td>{if $right.groupware}<a href="{$url.groupware}">{$lang.groupware}</a>{else}{$lang.groupware}{/if}</td>
		<td>{if $right.content}<a href="{$url.meta}">{$lang.meta_tags}</a>{else}{$lang.meta_tags}{/if}</td>
	</tr>
	
	<tr>
		<th>{$lang.tools}</th>
		<th>&nbsp;</th>
	</tr>
	
	<tr>
		<td>{if $right.mod}<a href="{$url.add_key}">{$lang.add_key}</a>{else}{$lang.add_key}{/if}</td>
		<td></td>
	</tr>
	
	<tr>
		<td>{if $right.mod}<a href="{$url.add_salt}">{$lang.add_salt}</a>{else}{$lang.add_salt}{/if}</td>
		<td></td>
	</tr>
	
</table>

<div class="headline">{$lang.info}</div>
<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="25%"><strong>Core-Version:</strong></td>
		<td>{$core.version.string}</td>
	</tr>
	<tr>
		<td width="25%"><strong>Smarty-Version:</strong></td>
		<td>{$smarty.version}</td>
	</tr>
</table>