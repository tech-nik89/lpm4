<div class="headline">{$lang.formmaker}</div>

{if count($forms) > 0}
	<table width="100%" border="0">
		<tr>
			<th>{$lang.form}</th>
			<th>{$lang.key}</th>
			<th>{$lang.options}</th>
		</tr>
		{foreach from=$forms item=form}
			<tr>
				<td>
					<a href="{$form.url}">{$form.title}</a>
				</td>
				<td>{$form.key}</td>
				<td align="right">
					<a href="{$form.records_url}">{$lang.records}</a>
					|
					<a href="{$form.edit_url}">{$lang.edit}</a>
				</td>
			</tr>
		{/foreach}
	</table>
{else}
	<p>
		{$lang.no_forms}
	</p>
{/if}