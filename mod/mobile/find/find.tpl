<div class="headline">{$lang.find}</div>
<form action="" method="post">
<p>
	<strong>{$lang.search_for}:</strong>
	<input type="text" name="search_string" value="{$search_string}" style="width:250px;" />
	<select name="engine">
		{section name=i loop=$engines}
			<option value="{$engines[i].key}"{if $engines[i].key == $engine} selected="selected"{/if}>{$engines[i].name}</option>
		{/section}
	</select>
	<input type="submit" name="find" value="{$lang.find}" />
</p>

{if count($results) > 0}
	
    <div class="headline">{$lang.results}</div>
    
    {section name=i loop=$results}
		<div style="padding-bottom:8px;">
			<table width="100%" border="0">
				<tr>
					<td width="25"><font color="#999999">{$results[i].i}.</font></td>
					<td>
						<strong>
							{$results[i].engine}:
							<a href="{$results[i].url}">{$results[i].title}</a>
						</strong>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>{$results[i].description|truncate:300:" ...":false}</td>
				<tr>
			</table>
		</div>
    {/section}
    
    
{/if}

</form>