<script type="text/javascript">
	function openPopUp () {
	 newWindow = window.open(location.href+"&tpl="+$("#template").val(), "abc", "width=800,height=600,status=yes,scrollbars=yes,resizable=yes");
	 newWindow.focus();
	}
</script>

<form action="" method="post">

	<div class="headline">{$lang.system}</div>
	
	<div id="system" style="display:block;">
		<table width="100%" cellpadding="5" cellspacing="1" border="0">
			
			<tr class="highlight_row">
				<td width="30%">{$lang.maintenance_mode}:</td>
				<td>
					<input id="maintenance" type="checkbox" name="maintenance" value="1"{if $maintenance == 1} checked="checked"{/if} />
				</td>
			</tr>
			
			<tr>
				<td>{$lang.reason}:</td>
				<td><textarea name="maintenance_description" rows="20" cols="80" style="width:100%; height:100px;">{$maintenance_description}</textarea></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.default_template}:</td>
				<td>
					<select name="template" id="template">
					{section name=tlist loop=$tlist}
						
						{if $tlist[tlist] == $template}
							<option selected="selected" value="{$tlist[tlist]}">{$tlist[tlist]}</option>
						{else}
							<option value="{$tlist[tlist]}">{$tlist[tlist]}</option>
						{/if}
						
					{/section}
					</select>
					
					<input type="button" value="{$lang.preview}" onClick="javascript:openPopUp();"/>
				
				</td>
			</tr>
			
			<tr>
				<td>{$lang.language}:</td>
				<td>
					<select name="language">
					{section name=llist loop=$llist}
						
						{if $llist[llist] == $language}
							<option selected="selected" value="{$llist[llist]}">{$llist[llist]}</option>
						{else}
							<option value="{$llist[llist]}">{$llist[llist]}</option>
						{/if}
						
					{/section}
					</select>
				</td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.mod_rewrite}:</td>
				<td><input type="checkbox" name="mod_rewrite" value="1" {if $mod_rewrite=="1"}checked="checked"{/if} /></td>
			</tr>
			
			<tr>
				<td>{$lang.config_bbcode}:</td>
				<td><input type="checkbox" name="bbcode" value="1" {if $bbcode=="1"}checked="checked"{/if} /></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.email_sender}:</td>
				<td><input type="text" name="register-mail-sender" value="{$register_mail_sender}" /></td>
			</tr>
			
		</table>
	</div>
	
    <div class="headline">{$lang.registration}</div>
    
	<div id="registration" style="display:block;">
		<table width="100%" cellpadding="5" cellspacing="1" border="0">
		
			<tr class="highlight_row">
				<td width="30%">{$lang.activation_required}:</td>
				<td><input type="checkbox" name="register-activation-required" {if $activation_required == 1} checked="checked"{/if} value="1" /></td>
			</tr>
			
			<tr>
				<td>{$lang.email_subject}:</td>
				<td><input type="text" name="register-mail-subject" value="{$register_mail_subject}" style="width:100%;" /></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.email_text}:</td>
				<td>
					<textarea name="register-mail-text" style="width:100%; height:150px;">{$register_mail_text}</textarea>
					<p>{$lang.placeholder}<strong>:</strong> %key%, %url%, %nickname%, %prename%, %lastname%</p>
				</td>
			</tr>
			
		</table>
    </div>
	
    <div class="headline">{$lang.password_lost}</div>
    
	<div id="password_lost" style="display:block;">
		<table width="100%" cellpadding="5" cellspacing="1" border="0">
		
			<tr>
				<td width="30%">{$lang.email_subject}:</td>
				<td><input type="text" name="lostpw-mail-subject" value="{$lostpw_mail_subject}" style="width:100%;" /></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.email_text}:</td>
				<td>
					<textarea name="lostpw-mail-text" style="width:100%; height:150px;">{$lostpw_mail_text}</textarea>
					<p>{$lang.placeholder}<strong>:</strong> %newpassword%, %nickname%, %prename%, %lastname%</p>
				</td>
			</tr>
			
		</table>
    </div>
	
	<p><input type="submit" name="save" value="{$lang.save}" /></p>
	
</form>