<div class="headline">{$lang.message_from} {$message.sender}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	
	<tr>
		<td width="120"><strong>{$lang.sender}:</strong></td>
		<td><a href="{$message.sender_url}">{$message.sender}</a></td>
	</tr>
	
	<tr>
		<td width="120"><strong>{$lang.reciever}:</strong></td>
		<td><a href="{$message.reciever_url}">{$message.reciever}</a></td>
	</tr>
    
    <tr>
		<td width="120"><strong>{$lang.timestamp}:</strong></td>
		<td>{$message.timestamp_str}</td>
	</tr>
	
	<tr>
		<td><strong>{$lang.subject}:</strong></td>
		<td>{$message.subject}</td>
	</tr>
	
	<tr>
		<td colspan="2">{$message.message}</td>
	</tr>
</table>

{if $mode=="inbox"}
	
	<form action="" method="post">
		<p>
			<input type="submit" name="delete" value="{$lang.delete}" /> <input type="button" name="answer" value="{$lang.answer}" onclick="location.href='{$answer_url}'" />
		</p>
	</form>
{/if}