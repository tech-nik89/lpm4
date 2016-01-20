{if $action == ""}
	
	<div class="headline">{$lang.menu_editor}</div>
	
    <table width="100%" border="0">
    	<tr>
        	<td>
            	<form action="" method="post">
                    <div align="left">
                        {$lang.menu_language_filter}:
                        <select name="filter_language" style="width:200px;">
                            <option value="">-</option>
                            {foreach from=$languages item=item}
                                <option value="{$item}"{if $filter_language == $item} selected="selected"{/if}>{$item}</option>
                            {/foreach}
                        </select>
                        <input type="submit" name="do_filter_language" value="{$lang.go}" />
                    </div>
                </form>
            </td>
            <td align="right">
            	<a href="{makeurl mod='admin' mode='menu' action='add'}">{$lang.add}</a>
            </td>
        </tr>
    </table>
	
	
	<form action="" method="post">
	
	<div align="right">
		<span style="margin-right:25px;">{$lang.order}</span>
		{$lang.delete}
	</div>
	<div>
		{foreach from=$m item=menuitem}
			{include file='../mod/default/admin/menu.treeelement.tpl' item=$menuitem}
		{/foreach}
	</div>
	
	<p>
		<input type="submit" name="save" value="{$lang.save}" />
	</p>
	
	</form>
	
{/if}