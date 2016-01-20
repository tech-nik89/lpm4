<div style="width:400px;">
	{if count($ingredients) > 0}
		<div class="headline">{$lang.ingredients}</div>
		<ul>
		{foreach from=$ingredients item=item}
			<li>{$item.name}</li>
		{/foreach}
		</ul>
	{/if}
	<div class="headline">{$lang.state}</div>
	<form action="" method="post">
		<input type="hidden" name="changeItemStateId" value="{$smarty.get.itemid}" />
		<select name="newItemState">
			{foreach from=$lang.order_state item=state name=stateloop}
				<option value="{$smarty.foreach.stateloop.index}"{if $product.state == $smarty.foreach.stateloop.index} selected="selected"{/if}>{$state}</option>
			{/foreach}
		</select>
		<input type="submit" name="changeStateSubmitButton" value="{$lang.change_order_state}" />
	</form>
</div>