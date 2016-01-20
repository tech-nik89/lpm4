<script type="text/javascript" language="javascript">
		function selectAll()
		{
		{section name=i loop=$list}
			document.getElementById('pm_{$list[i].pmid}').checked = true;
		{/section}
		}
		
		function deselectAll()
		{
		{section name=i loop=$list}
			document.getElementById('pm_{$list[i].pmid}').checked = false;
		{/section}
		}
</script>

<div class="headline">{$lang.outbox}</div>
	
<form action="" method="post">
	
	<table width="100%" border="0" cellpadding="5" cellspacing="1">
		
		<tr>
			<th width="20"><input type="checkbox" name="MasterCheckBox" id="MasterCheckBox" value="1" onclick="if(document.getElementById('MasterCheckBox').checked){ selectAll(); } else { deselectAll(); }" /></th>
			<th>{$lang.subject}</th>
			<th>{$lang.reciever}</th>
			<th>{$lang.timestamp}</th>
		</tr>
		
		{section name=i loop=$list}
			
			<tr class="{cycle values=',highlight_row'}">
				<td><input type="checkbox" name="pm_{$list[i].pmid}" id="pm_{$list[i].pmid}" value="1" />
				<td><a href="{$list[i].pm_url}">{$list[i].subject}</a></td>
				<td><a href="{$list[i].reciever_url}">{$list[i].reciever}</a></td>
				<td>{$list[i].timestamp}</td>
			</tr>
			
		{/section}
		
	</table>
	
	<p>
		<input type="submit" name="delete" value="{$lang.remove}" />
	</p>
	
</form>