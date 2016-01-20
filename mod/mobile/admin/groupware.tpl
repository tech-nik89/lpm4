<div class="headline">{$lang.groupware}</div>

<p align="right"><a href="{$url_show_done}"><input type="checkbox" {if $show_done != 0} checked="checked"{/if} /> &nbsp;{$lang.show_done}</a></p>

<table width="100%" cellspacing="1" cellpadding="5">
	
	<tr>
		<th width="25%"><a href="{$url_order_title}">{$lang.title}</a></th>
		<th width="25%"><a href="{$url_order_end}">{$lang.done_at}</a></th>
		<th width="25%"><a href="{$url_order_priority}">{$lang.priority}</a></th>
		<th width="25%"><a href="{$url_order_state}">{$lang.state}</a></th>
	</tr>
	
	{section name=i loop=$tasks}
		
		<tr>
			<td><a href="{$tasks[i].url}">{$tasks[i].title}</a></td>
			<td>{$tasks[i].end}</td>
			<td>{$tasks[i].priority}</td>
			<td>{$tasks[i].state}</td>
		</tr>
		
	{/section}
	
</table>