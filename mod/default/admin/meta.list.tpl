<h1>{$lang.meta_tags}</h1>
<p align="right">
	<a href="{makeurl mod='admin' mode='meta' action='add'}">{$lang.add}</a>
</p>
<table width="100%" border="0">
	<colgroup>
		<col width="*" />
		<col width="*" />
		<col width="100" />
		<col width="150" />
	</colgroup>
	<tr>
		<th>{$lang.name}</th>
		<th>HTTP-Equiv</th>
		<th>{$lang.domain}</th>
		<th>{$lang.language}</th>
		<th>{$lang.options}</th>
	</tr>
	{foreach from=$items item=item}
		<tr>
			<td>{$item.name}</td>
			<td>{$item.http_equiv}</td>
			<td>{foreach from=$dlist item=d}{if $d.domainid == $item.domainid}{$d.name}{/if}{/foreach}</td>
			<td>{$item.language}</td>
			<td>
				<a href="{makeurl mod='admin' mode='meta' action='edit' tagid=$item.tagid}">{$lang.edit}</a>
				|
				<a href="{makeurl mod='admin' mode='meta' action='delete' tagid=$item.tagid}">{$lang.delete}</a>
			</td>
		</tr>
	{/foreach}
</table>