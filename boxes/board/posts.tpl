{foreach from=$box_board_posts item=post}
	<p>&raquo; <a href="{$post.url}">{$post.thread}</a> {$post.info}</p>
{/foreach}