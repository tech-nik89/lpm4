{if $adress_set}
	<div class="headline">{$lang.circular_mail}</div>

	<script language="javascript" type="text/javascript">
		function send() {
			$("#status").html('<img src="mod/default/admin/working.gif" border="0" />');
			
			$.post("ajax_request.php?mod=admin&file=circular.ajax",
						{ subject: $("#subject").val(), message: $("#message").val(),
						copy: $("#copy_to_me").attr('checked'), pm: $("#send_pm").attr('checked'),
						mail: $("#send_mail").attr('checked'), send_to: $("#send_to").val() },
						function(data) {
							$("#status").html(data);
						});
		}
	</script>

	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<td width="20%"><strong>{$lang.subject}:</strong></td>
			<td><input type="text" name="subject" id="subject" style="width:100%;" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.message}:</strong></td>
			<td><textarea name="message" id="message" style="width:100%; height:150px;"></textarea></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.send_to}:</strong></td>
			<td>
                <select name="send_to" id="send_to">
                    <option value="0">{$lang.all}</option>
                    {foreach from=$groups item=group}
                        <option value="{$group.groupid}">{$group.name}</option>
                    {/foreach}
                </select>
            </td>
		</tr>
		
		
		<tr>
			<td><strong>{$lang.copy_to_me}:</strong></td>
			<td><input type="checkbox" name="copy_to_me" id="copy_to_me" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.send_pm}:</strong></td>
			<td><input type="checkbox" name="send_pm" id="send_pm" checked="checked" /></td>
		</tr>
		
		<tr>
			<td><strong>{$lang.send_mail}:</strong></td>
			<td><input type="checkbox" name="send_mail" id="send_mail" /></td>
		</tr>
		
	</table>

	<div id="status" style="padding:5px;">
		<input type="button" name="send" id="send" onclick="send();" value="{$lang.send}" />
	</div>
{/if}