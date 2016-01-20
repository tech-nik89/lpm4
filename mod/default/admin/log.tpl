<div class="headline">{$lang.log}</div>
<script language="javascript" type="text/javascript">
	{literal}
	function show(id)
	{
		if (id == 'file')
		{
			document.getElementById('file').style.display='block';
			document.getElementById('mysql').style.display='none';
		}
		else
		{
			document.getElementById('file').style.display='none';
			document.getElementById('mysql').style.display='block';
		}
	}
	{/literal}
</script>
<p><a href="#" onclick="javascript:show('file');">{$lang.log_view_file}</a> | <a href="#" onclick="javascript:show('mysql');">{$lang.log_view_mysql}</a></p>

<div id="file" style="display:none; font-family:'Courier New', Courier, monospace';">
	
	{section name=i loop=$filelist}
	<a href="{$filelist[i].url}" target="_blank">{$filelist[i].name}</a><br />
	{/section}
    
</div>

<div id="mysql" style="display:none;">
	
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    	
        <tr>
        	<th>Time</th>
            <th>User ID</th>
            <th>Mod</th>
            <th>Description</th>
        </tr>
        
        {section name=i loop=$list}
        <tr>
        	<td>{$list[i].time}</td>
            <td>{$list[i].userid}</td>
            <td>{$list[i].mod}</td>
            <td>{$list[i].description}</td>
        </tr>
        {/section}
        
    </table>
    
</div>