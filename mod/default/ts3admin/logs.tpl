{section name=notify loop=$notify}
    <div class="notification">
        <div><strong>{$notify[notify].subject}</strong></div>
        <div style="margin-left:10px;">{$notify[notify].message}</div>
    </div>
{/section}

<script type="text/javascript">
	function setlimit() {
		var limit = document.getElementById("limit").value;
		document.getElementById("vServerManageContent").innerHTML=loading;
		$("#vServerManageContent").load("ajax_request.php?mod=ts3admin&file=logs.ajax&sid="+sid+"&vsid="+vsid+"&limit="+encodeURIComponent(limit), 
				toggleCategoryByCookie);
	}
	function handleKeyPress(e){
	var key=e.keyCode || e.which;
	if (key==13){
		setlimit();
	}
}
</script>
{if not $nologs}
{$lang.count} [1-500]: <input type="number" id="limit" value="{$limit}" onkeypress="javascript:handleKeyPress(event);" /> <input type="submit" value="{$lang.refresh}" onclick="javascript:setlimit();return false;" /><br />
<br />
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th>{$lang.createdon}</th>
        <th>{$lang.level}</th>
        <th>{$lang.channel}</th>
        <th>{$lang.message}</th>
    </tr>
    {foreach from=$logs item=log}
    <tr class="{cycle values='highlight_row,row'}">
        <td>{$log.timestamp|date_format:"%A, %B %e, %Y %H:%M:%S"}</td>
        <td>{if $log.level eq 1}{$lang.log_error}{else if $log.level eq 2}{$lang.log_warning}{else if $log.level eq 3}{$lang.log_debug}{else if $log.level eq 4}{$lang.log_info}{/if}</td>
        <td>{$log.channel}</td>
        <td>{$log.msg}</td>
    </tr>
    {/foreach}
</table>
{/if}