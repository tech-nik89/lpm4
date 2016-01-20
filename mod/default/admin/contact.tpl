{if $contactid==0}
	
	<div class="headline">{$lang.contact_requests}</div>
	
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th>{$lang.subject}</th>
			<th>{$lang.sender}</th>
			<th>{$lang.timestamp}</th>
			<th>{$lang.read} / {$lang.done}</th>
			<th>{$lang.comments}</th>
		</tr>	
		
		{section name=i loop=$requests}
			
			<tr class="{cycle values=',highlight_row'}">
				<td><a href="{$requests[i].url}">{$requests[i].subject}</a></td>
				<td>{$requests[i].sender}</td>
				<td>{$requests[i].timestamp}</td>
				<td>{$requests[i].read} / {$requests[i].done}</td>
				<td>{$requests[i].comments}</td>
			</tr>
			
		{/section}
		
	</table>
	
{else}

<div class="headline">{$request.subject}</div>
    
    <table width="100%" border="0">
    
        <tr>
            <td width="20%"><strong>{$lang.sender}:</strong></td>
            <td>{$request.sender}</td>
        </tr>
        <tr>
            <td><strong>{$lang.timestamp}:</strong></td>
            <td>{$request.timestamp}</td>
        </tr>
    	<tr valign="top">
            <td><strong>{$lang.message}:</strong></td>
            <td>{$request.text}</td>
        </tr>
        
    </table>
	
<div class="headline">{$lang.state}</div>
	
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		<tr>
            <td width="20%"><strong>{$lang.read}:</strong></td>
            <td>{$request.read}</td>
        </tr>
        <tr>
            <td><strong>{$lang.done}:</strong></td>
            <td>
				{if $request.done == 0}
					<form action="" method="post">
						{$lang.no} <input type="submit" name="done" value="{$lang.done}" />
					</form>
				{else}
					{$lang.done_by}{$request.done_by}
				{/if}
			</td>
        </tr>
		<tr>
            <td width="20%"><strong>{$lang.delete}:</strong></td>
            <td>				
				<form action="" method="post">
					<input type="submit" name="delete" value="{$lang.delete}" />
				</form>
			
			</td>
        </tr>
        {if $request.done == 0 && $request.isInGroupware == false}
		<tr>
			<td><strong>{$lang.contact_move_to_groupware}:</strong></td>
			<td>
                <form action="" method="post">
                    <input type="submit" name="move_to_groupware" value="{$lang.copy}" />
                </form>
			</td>
		</tr>
        {/if}
	</table>
	
	<div class="headline">{$lang.comments}</div>
	
	{section name=i loop=$comments}
		
		<p>
			<strong>{$comments[i].nickname}</strong> ({$comments[i].time})<br />
			{$comments[i].text}
		</p>
		
	{/section}
	
	<form action="" method="post">
		
		<textarea name="text" style="width:100%; height:150px;"></textarea>
		
		<p>
			<input type="submit" name="addcomment" value="{$lang.add}" />
		</p>
		
	</form>
	
{/if}