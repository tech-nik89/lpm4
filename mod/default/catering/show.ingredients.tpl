{if !empty($ingredients)}
	<table style="width:100%; border-collapse:collapse;">
		{foreach from=$ingredients item=ingredient}
			<tr {cycle values=',class="highlight_row"'}>
				<td style="padding:3px;" id="ingredient_{$ingredient.ingredientid}" >
					<label for="input_ingredient_{$ingredient.ingredientid}" style="display:block;">
						{$ingredient.name}
					</label>
				</td>
				<td>
					<input type="checkbox" id="input_ingredient_{$ingredient.ingredientid}" value="{$ingredient.ingredientid}" name="ingredient" class="ingredient" />
				</td>
				<td>
					{({$ingredient.price} / 100)|number_format:2:",":"."} &euro;
				</td>
			</tr>
		{/foreach}
	</table>
{/if}