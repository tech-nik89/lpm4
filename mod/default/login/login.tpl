<div class="headline">{$lang.login}</div>

{if $logged_in == true}
	<p>
		{$lang.logged_in}<br />
        {$lang.nickname}: {$user.nickname}
	</p>
	<p>
		<a href="{$logout_url}">{$lang.logout}</a>
	</p>
{else}
	<table width="100%" border="0" cellpadding="5">
		<tr>
			<td width="50%">
				<fieldset>
					<legend>{$lang.login}</legend>
					<form action="" method="post">
						<table width="97%" border="0" cellpadding="5" cellspacing="0">
							<tr>
								<td width="150">{$lang.email}:</td>
								<td><input type="text" name="email" id="email" style="width:99%;" /></td>
							</tr>
							<tr>
								<td>{$lang.password}:</td>
								<td><input type="password" name="password" id="password" style="width:99%;" /></td>
							</tr>
							{if $save_login_disabled == 0}
							<tr>
								<td><label for="save_login">{$lang.save_login}:</label></td>
								<td>
									<input type="checkbox" id="save_login" name="save_login" value="1" />
								</td>
							</tr>
							{/if}
						</table>
						<p align="right" style="width:97%;">
							<input type="submit" name="login" value="{$lang.login}" />
						</p>
					</form>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<fieldset>
					<legend>{$lang.account_options}</legend>
					<ul>
						{if !$disable_register}
							<li>
								<a href="{makeurl mod='login' mode='register'}">{$lang.register}</a><br />
								{$lang.register_descr_short}
							</li>
						{/if}
						<li>
							<a href="{makeurl mod='login' mode='lostpw'}">{$lang.password_lost}</a><br />
							{$lang.lostpw_descr_short}
						</li>
					</ul>
				</fieldset>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		$("#email").focus();
	</script>
{/if}