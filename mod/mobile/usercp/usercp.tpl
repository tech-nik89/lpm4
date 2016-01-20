{if $mode == "overview"}

	<div class="headline">{$lang.account}</div>
	
	<p align="center">
		{$avatar}
	</p>
	<form action="" method="post">
	
		<table width="100%" border="0" cellspacing="5">
			
			<tr>
				<td width="20%"><strong>{$lang.email}:</strong></td>
				<td>{$user.email}</td>
			</tr>
			
			<tr>
				<td><strong>{$lang.nickname}:</strong></td>
				<td>{$user.nickname}</td>
			</tr>
			
			<tr>
				<td><strong>{$lang.prename}:</strong></td>
				<td><input type="text" name="prename" value="{$user.prename}" /></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.lastname}:</strong></td>
				<td><input type="text" name="lastname" value="{$user.lastname}" /></td>
			</tr>
			
			<tr>
				<td><strong>{$lang.birthday}:</strong></td>
				<td>{html_select_date start_year='-70' end_year='-8' time=$user.birthday}</td>
			</tr>
			
		</table>
		
		<p><input type="submit" name="save" value="{$lang.save}" /></p>
	</form>
	
{/if}

{if $mode == "personal"}
	
	<div class="headline">{$lang.personal}</div>
	
	<form acton="" method="post">
		
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
			{section name=i loop=$list}
			
			<tr>
				<td width="20%">{$list[i].name}:</td>	
				<td><input type="text" name="value_{$list[i].fieldid}" value="{$list[i].value}" style="width:100%;" /></td>
			</tr>
			
			{/section}
			
		</table>
		
		<p>
			<input type="submit" name="save" value="{$lang.save}" />			
		</p>
		
	</form>
	
{/if}

{if $mode == "avatar"}
	
    <div class="headline">{$lang.avatar}</div>
    
	<p align="center">
		{$avatar}
	</p>
	
	<strong>{$lang.avatar_upload}</strong><br />
	{$upload}<br />
	<ul>
		<li>{$lang.max_file_size}: 1024 kb (1 mb)</li>
		<li>{$lang.max_width}: {$img_width} Pixel</li>
		<li>{$lang.max_height}: {$img_height} Pixel</li>
	</ul>
	
{/if}

{if $mode == "changepw"}
	
    <div class="headline">{$lang.changepw}</div>
    
	<form action="" method="post">
	
		<table width="100%" border="0" cellpadding="1" cellspacing="5">
			
			<tr>
				<td width="50%">{$lang.password_old}:</td>
				<td><input type="password" name="password_old" id="password_old" /></td>
			</tr>
			
			<tr>
				<td>{$lang.password_new}:</td>
				<td><input type="password" name="password_new" id="password_new" /></td>
			</tr>
			
			<tr>
				<td>{$lang.password_new_repeat}:</td>
				<td><input type="password" name="password_new_repeat" id="password_new_repeat" /></td>
			</tr>
			
		</table>
		
		<p>
			<input type="submit" name="save" value="{$lang.changepw}" onClick="javascript:var p=document.getElementById('password_old'); var pp=document.getElementById('password_new'); var ppp=document.getElementById('password_new_repeat'); p.value=MD5(p.value); pp.value=MD5(pp.value); ppp.value=MD5(ppp.value);" />
		</p>
	
	</form>
	
{/if}