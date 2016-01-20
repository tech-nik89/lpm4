<p>
	{if $view!='day'}<a href="{$url.view_day}">{/if}{$lang.view_day}{if $view!='day'}</a>{/if} | 
	{if $view!='week'}<a href="{$url.view_week}">{/if}{$lang.view_week}{if $view!='week'}</a>{/if} | 
	{if $view!='month'}<a href="{$url.view_month}">{/if}{$lang.view_month}{if $view!='month'}</a>{/if} | 
	{if $view!='year'}<a href="{$url.view_year}">{/if}{$lang.view_year}{if $view!='year'}</a>{/if} | 
	{if $view!='next'}<a href="{$url.view_next}">{/if}{$lang.view_next}{if $view!='next'}</a>{/if}
</p>