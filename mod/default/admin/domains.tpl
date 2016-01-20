<div class="headline">{$lang.domains}</div>
<table width="100%" border="0">
	<tr>
		<th>#</th>
		<th>{$lang.domain}</th>
		<th>{$lang.template}</th>
		<th>{$lang.language}</th>
		<th>{$lang.alias}</th>
		<th>{$lang.options}</th>
	</tr>
	{foreach from=$domains item=domain}
		<tr{cycle values=', class="highlight_row"'}>
			<td>{$domain.domainid}</td>
			<td><a href="http://www.{$domain.name}" target="_blank">{$domain.name}</a></td>
			<td>{if $domain.template == ''}-{else}{$domain.template}{/if}</td>
			<td>{$domain.language}</td>
			<td>{$domain.alias_name}</td>
			<td>
				<a href="{$core.self_url}?debug-domain={$domain.name}" target="_blank">Debug</a>
				| <a href="{makeurl mod='admin' mode='domains' action='edit' domainid=$domain.domainid}">{$lang.edit}</a>
				| <a href="{makeurl mod='admin' mode='domains' action='delete' domainid=$domain.domainid}">{$lang.delete}</a>
			</td>
		</tr>
	{/foreach}
</table>