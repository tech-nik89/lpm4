{assign var='grey_color' value='#999999'}

<div class="headline">{$lang.find}</div>
<form action="" method="get">
<p>
	<strong>{$lang.search_for}:</strong>
	<input type="text" name="q" value="{$search_string}" style="width:250px;" />
	<select name="engine">
		{section name=i loop=$engines}
			<option value="{$engines[i].key}"{if $engines[i].key == $engine} selected="selected"{/if}>{$engines[i].name}</option>
		{/section}
	</select>
	<input type="submit" name="find" value="{$lang.find}" />
</p>

{if count($results) > 0}
	
    <div class="headline">{$lang.results} ({if count($results) > $result_limit}{$result_limit}{else}{count($results)}{/if} / {count($results)})</div>
    
    {section name=i loop=$results max=$result_limit}
		<div style="padding-bottom:8px;">
			<table width="100%" border="0">
				<tr>
					<td width="25"><font color="{$grey_color}">{$results[i].i}.</font></td>
					<td>
						<strong>
							<a href="{$results[i].url}">{$results[i].title}</a>
						</strong>
						<font color="{$grey_color}">({$results[i].engine})</font>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>{$results[i].description|truncate:$result_string_length:" ...":false} <font color="{$grey_color}">({$results[i].relevance})</font></td>
				<tr>
			</table>
		</div>
    {/section}
{/if}

</form>