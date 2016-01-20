<div id="div_{$item.menuid}" onmouseover="$('#div_{$item.menuid}').addClass('highlight_row');" onmouseout="$('#div_{$item.menuid}').removeClass('highlight_row');">
	<div style="float:left;">
		{if $item.mod == 'headline'}
			<strong><u>
		{/if}
		<a href="{$item.edit_url}">{$item.title}</a>
		{if $item.mod == 'headline'}
			</u></strong>
		{/if}
		
		{if $item.language != ''}({$item.language}){/if}
		{if $item.domainid > 0}({$item.name}){/if}
		{if $item.home == 1}({$lang.startpage}){/if}
	</div>
	<div style="float:right;">
		<input type="text" name="order_{$item.menuid}" value="{$item.order}" style="width:35px; margin-right:70px;" />
		<input type="checkbox" name="delete_{$item.menuid}" value="1" />
	</div>
	<div style="clear:both;"></div>
</div>
{if count($item.children) > 0}
	<div style="margin-left:25px;">
		{foreach from=$item.children item=child}
			{include file='../mod/default/admin/menu.treeelement.tpl' item=$child}
		{/foreach}
	</div>
{/if}
