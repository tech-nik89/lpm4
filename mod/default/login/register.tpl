<div class="headline">{$lang.register}</div>

{if $success != ""}
	<p>
		<font color="#00CC00">{$success}</font>
	</p>
{/if}

<form action="" method="post">
	<span style="display:none;">
		<input type="text" name="mail" value="" />
	</span>
	<span style="display:none;">
		<input type="text" name="ts" value="{$timestamp}" />
	</span>
	<p>&raquo; {$lang.register_notification}</p>
	<table border="0" width="100%">
		<tr>
			<td width="20%">*{$lang.email}:</td>
			<td><input type="text" name="email" value="{$email}" style="width:100%;" />
					{if $email_notify != ""}
						<font color="#CC0000">
							{$email_notify}
						</font>
					{/if}
			</td>
		</tr>
		{if !$disable_second_email}
			<tr>
				<td>*{$lang.email_repeat}:</td>
				<td><input type="text" name="email_repeat" value="{$email_repeat}" style="width:100%;" />
						{if $email_repeat_notify != ""}
							<font color="#CC0000">
								{$email_repeat_notify}
							</font>
						{/if}
				</td>
			</tr>
		{/if}
		{if !$disable_nickname}
			<tr>
				<td>*{$lang.nickname}:</td>
				<td><input type="text" name="nickname" value="{$nickname}" style="width:100%;" />
						{if $nickname_notify != ""}
							<font color="#CC0000">
								{$nickname_notify}
							</font>
						{/if}
				</td>
			</tr>
		{/if}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>*{$lang.password}:</td>
			<td><input type="password" name="password" value="{$password}" id="password" style="width:100%;" />
					{if $password_notify != ""}
						<font color="#CC0000">
							{$password_notify}
						</font>
					{/if}
			</td>
		</tr>
		<tr>
			<td>*{$lang.password_repeat}:</td>
			<td><input type="password" name="password_repeat" value="{$password_repeat}" id="password_repeat" style="width:100%;" />
					{if $password_repeat_notify != ""}
						<font color="#CC0000">
							{$password_repeat_notify}
						</font>
					{/if}
			</td>
		</tr>
	</table>
	
	<div class="headline">{$lang.personal}</div>
	<table border="0" width="100%">
		<tr>
			<td width="20%">*{$lang.prename}:</td>
			<td><input type="text" name="prename" value="{$prename}" style="width:100%;" />
					{if $prename_notify != ""}
						<font color="#CC0000">
							{$prename_notify}
						</font>
					{/if}
			</td>
		</tr>
		<tr>
			<td>*{$lang.lastname}:</td>
			<td><input type="text" name="lastname" value="{$lastname}" style="width:100%;" />
					{if $lastname_notify != ""}
						<font color="#CC0000">
							{$lastname_notify}
						</font>
					{/if}
			</td>
		</tr>
		{if !$disable_birthday}
			<tr>
				<td>{$lang.birthday}:</td>
				<td>{html_select_date start_year='-70' end_year='-10' time=$birthday}</td>
			</tr>
		{/if}
	</table>
	<p>
		<input type="submit" name="create" value="{$lang.register}" />
	</p>
</form>