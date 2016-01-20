{if !$loggedin}
	<form action="{$link.form}" method="post">
		<table width="100%" border="0">
			<tr>
				<td>{$lang.email}:</td>
			</tr>
			<tr>
				<td><input type="email" name="email" value="" style="width:100%;" /></td>
			</tr>
			<tr>
				<td>{$lang.password}:</td>
			</tr>
			<tr>
				<td><input type="password" name="password" value="" style="width:100%;" /></td>
			</tr>
		</table>
		<p>
			<input type="submit" name="login" value="{$lang.login}" />
		</p>
	</form>
	<p>
		{if $login_box_register}
			&raquo; <a href="{$link.register}">{$lang.register}</a><br />
		{/if}
		&raquo; <a href="{$link.lostpw}">{$lang.password_lost}</a>
	</p>
{else}
	<p>
		&raquo; <a href="{$link.logout}">{$lang.logout}</a>
	</p>
	<p>
		{if $link.overview != ''}
			&raquo; <a href="{$link.overview}">{$lang.overview}</a><br />
		{/if}
		{if $link.personal != ''}
			&raquo; <a href="{$link.personal}">{$lang.personal}</a><br />
		{/if}
		{if $link.avatar != ''}
			&raquo; <a href="{$link.avatar}">{$lang.avatar}</a><br />
		{/if}
		{if $link.comments != ''}
			&raquo; <a href="{$link.comments}">{$lang.my_comments}</a><br />
		{/if}
		{if $link.changepw != ''}
			&raquo; <a href="{$link.changepw}">{$lang.changepw}</a><br />
		{/if}
	</p>
{/if}