{if $action == ""}
	
	{literal}
	<script type="text/javascript">
	  function contentloader() {
		$("#searchresults").load("ajax_request.php?mod=admin&file=userlist.ajax&search="+$("#find").val());
	  }
	  
	  function toggleOptions(userid) {
		$("#divOptions_" + userid).fadeToggle();
	  }
	</script>
	
	<style type="text/css">
		.liNoPadding {
			padding-left:1em;
		}
	</style>
	{/literal}
	
    <div style="border-bottom:1px solid #CCCCCC; padding:8px;">
        <form action="" method="post">
            <strong>{$lang.find}:</strong>&nbsp;&nbsp;&nbsp;<input autocomplete="off" type="text" id="find" name="find" value="{$find}" onkeyup="contentloader();" style="width:50%" />
            <input type="submit" name="go" value="{$lang.go}" />
        </form>
    </div>
    
	<p>{$lang.users}: {$usercount}</p>
    <p>&nbsp;</p>	
	
	<div id="searchresults">
		
		<p>{$pages}</p>
		
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
			
			<tr>
				<th><a href="{$order_nickname}">{$lang.nickname}</a></th>
				<th><a href="{$order_email}">{$lang.email}</a></th>
				<th><a href="{$order_lastname}">{$lang.name}</a></th>
				<th width="120">&nbsp;</th>
			</tr>
			
			{section name=users loop=$users}
				<tr class="{cycle values=',highlight_row'}" valign="top">
					<td><a href="{$users[users].url_edit}">{$users[users].nickname}</a></td>
					<td>{$users[users].email}</td>
					<td>{$users[users].prename} {$users[users].lastname}</td>
					<td>
						<a href="#" onclick="toggleOptions({$users[users].userid}); return false;">{$lang.options}</a>
						<div id="divOptions_{$users[users].userid}" style="display:none;">
							&raquo; <a href="{$users[users].url_edit}">{$lang.options_edit}</a><br />
							&raquo; <a href="{$users[users].url_memberships}">{$lang.options_memberships}</a><br />
							&raquo; <a href="{$users[users].url_delete}">{$lang.options_delete}</a><br />
							&raquo; <a href="{$users[users].url_deposit}">{$lang.options_deposit}</a>
						</div>
					</td>
				</tr>
			{/section}
			
		</table>
		
		<p>{$pages}</p>
	
	</div>
{/if}

{if $action == "memberships"}
	
	{include file='../mod/default/admin/usermenu.tpl' userid=$smarty.get.userid}
	
	<div class="headline">{$lang.options_memberships}</div>
	
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.group}</th>
			<th>{$lang.description}</th>
			<th width="100">{$lang.options}</th>
		</tr>
		
		{section name=groups loop=$groups}
			
		<tr class="{cycle values=',highlight_row'}">
			<td><a href="{makeurl mod='admin' mode='groups' action='edit' groupid=$groups[groups].groupid}">{$groups[groups].name}</a></td>
			<td>{$groups[groups].description}</td>
			<td>
				<form action="" method="post">
					<p class="vcenter">
					<input type="hidden" name="groupid" value="{$groups[groups].groupid}" />
					<input type="submit" name="delete" value="{$lang.options_delete}" />
					</p>
				</form>
			</td>
		</tr>
			
		{/section}
		
	</table>
	
	<div class="headline">{$lang.membership_new}</div>
	
	<form action="" method="post">
		<select name="group_new">
			{section name=group_new loop=$group_new}
			<option value="{$group_new[group_new].groupid}">{$group_new[group_new].name}</option>
			{/section}
		</select>
		<input type="submit" name="add" value="{$lang.add}" />
	</form>
	
{/if}

{if $action == "edit"}
	
    <script type="text/javascript">
		function openPopUp () {
		 newWindow = window.open(location.href+"&tpl="+$("#template").val(), "abc", "width=800,height=600,status=yes,scrollbars=yes,resizable=yes");
		 newWindow.focus();
		}
	</script>
    
	{include file='../mod/default/admin/usermenu.tpl' userid=$smarty.get.userid}
	
	<div class="headline">{$lang.user_details}</div>
	
	<form action="" method="post">
	
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
			
			<tr>
				<td width="20%">{$lang.nickname}:</td>
				<td><input type="text" name="nickname" value="{$user.nickname}" style="width:100%;" /></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.email}:</td>
				<td><input type="text" name="email" value="{$user.email}" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td>{$lang.prename}:</td>
				<td><input type="text" name="prename" value="{$user.prename}" style="width:100%;" /></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.lastname}:</td>
				<td><input type="text" name="lastname" value="{$user.lastname}" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td valign="top">{$lang.comment}:</td>
				<td><textarea name="comment" style="width:100%; height:80px;">{$user.comment}</textarea></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.company}:</td>
				<td><input type="text" name="company" value="{$user.company}" style="width:100%;" /></td>
			</tr>
			
			<tr>
				<td valign="top">{$lang.address}:</td>
				<td><textarea name="address" style="width:100%; height:80px;">{$user.address}</textarea></td>
			</tr>
			
			<tr class="highlight_row">
				<td>{$lang.birthday}:</td>
				<td>{$user.birthday|date_format:"%d-%m-%Y"}</td>
			</tr>
            
			<tr>
			  <td>{$lang.ban}:</td>
			  <td><input type="checkbox" value="1" name="ban" id="ban"{if $user.ban == 1} checked="checked"{/if} /></td>
		    </tr>
            
            <tr class="highlight_row">
			  <td>{$lang.activated}:</td>
			  <td><input type="checkbox" value="1" name="activated" id="activated"{if $user.activated == 1} checked="checked"{/if} /></td>
		    </tr>
			
            <tr>
			  <td>{$lang.template}:</td>
			  <td>
              		<select name="template" id="template">
                    	<option value="">{$lang.default}</option>
                        {section name=tlist loop=$tlist}
                            {if $tlist[tlist] == $user.template}
                                <option selected="selected" value="{$tlist[tlist]}">{$tlist[tlist]}</option>
                            {else}
                                <option value="{$tlist[tlist]}">{$tlist[tlist]}</option>
                            {/if}
                        {/section}
					</select>
					
					<input type="button" value="{$lang.preview}" onClick="javascript:openPopUp();"/>
                </td>
		    </tr>
            
		</table>
		
	  <p><input type="submit" name="edit_submit" value="{$lang.save}" /></p>
		
	</form>
	
	<div class="headline">{$lang.changepw}</div>
	
	<form action="" method="post">
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
			
			<tr>
				<td width="20%">{$lang.password}:</td>
				<td><input type="password" name="password" id="password" style="width:100%;" /></td>
			</tr>
			
		</table>
		
		<p><input type="submit" name="password_submit" value="{$lang.save}" onClick="javascript:var p=document.getElementById('password'); p.value=MD5(p.value);" /></p>
		
	</form>
	
	<div class="headline">{$lang.personal}</div>
	
	<form acton="" method="post">
		
		<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
			{section name=i loop=$list}
			
			<tr>
				<td width="20%">{$list[i].name}:</td>	
				<td><input type="text" name="value_{$list[i].fieldid}" value="{$list[i].value|escape:"html"}" style="width:100%;" /></td>
			</tr>
			
			{/section}
			
		</table>
		
		<p>
			<input type="submit" name="save" value="{$lang.save}" />			
		</p>
		
	</form>
	
	
{/if}

{if $action == 'deposit'}
	{include file='../mod/default/admin/usermenu.tpl' userid=$smarty.get.userid}
	
    <div class="headline">{$lang.options_deposit}</div>
    <p>{$lang.deposit_description}</p>
    <form action="" method="post">
    <p>
    	<strong>{$lang.amount}:</strong>
        <input type="text" name="amount" /> {$lang.subcurrency} 
    </p>
   	<p>
    	<input type="submit" name="add" value="{$lang.options_deposit}" />
    </p>
    </form>
    
{/if}