<div align="center" class="highlight_row">
	<strong>{$lang.user}</strong>
	|
	<a href="{makeurl mod='profile' userid=$userid}">{$lang.profile}</a>
	|
	<a href="{makeurl mod='admin' mode='users' action='edit' userid=$userid}">{$lang.edit}</a>
	|
	<a href="{makeurl mod='admin' mode='users' action='memberships' userid=$userid}">{$lang.options_memberships}</a>
	|
	<a href="{makeurl mod='admin' mode='users' action='deposit' userid=$userid}">{$lang.options_deposit}</a>
	|
	<a href="{makeurl mod='admin' mode='users' action='delete' userid=$userid}">{$lang.options_delete}</a>
</div>