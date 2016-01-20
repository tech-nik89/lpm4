{section name=notify loop=$notify}
    <div class="notification">
        <div><strong>{$notify[notify].subject}</strong></div>
        <div style="margin-left:10px;">{$notify[notify].message}</div>
    </div>
{/section}

{if $clearcomplist}
<script type="text/javascript">
	function clearcomplist() {
		$("#clearcompContent").load("ajax_request.php?mod=ts3admin&file=complains.ajax&clearcomplistack=1&sid={$sid}&vsid={$vsid}");
	}
</script>
<div id="clearcompContent">
    <table style="width:300px;">        
        <tr>
            <td colspan="3">{$lang.askclearcomplist}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: clearcomplist(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>


{else if $clearedcomplist}
<center>
    {$lang.clearedcomplist}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); return false;" />
</center>
{else if $deletecomp}
<script type="text/javascript">
	function deletecomp() {
		$("#deletecompContent").load("ajax_request.php?mod=ts3admin&file=complains.ajax&deletecompack=1&sid={$sid}&vsid={$vsid}&tcldbid="+
			encodeURIComponent("{$tcldbid}")+"&fcldbid="+encodeURIComponent("{$fcldbid}"));
	}
</script>
<div id="deletecompContent">
    <table style="width:300px;">        
        <tr>
            <td colspan="3">{$lang.askdeletecomp}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: deletecomp(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $deletedcomp}
<center>
    {$lang.deletedcomp}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); return false;" />
</center>
{else if not $nocomplains}
<div id="complainlistContent">
	{if $ts3vserver_rights.r_remove_complaints==1}
    <a href="ajax_request.php?mod=ts3admin&file=complains.ajax&clearcomplist=1&sid={$sid}&vsid={$vsid}" id="clearComplainListA" title="{$lang.clearlist}">{$lang.clearlist}</a><br/>
    {/if}
    <br/>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th>{$lang.about}</th>
            <th>{$lang.from}</th>
            <th>{$lang.complain}</th>
            <th>{$lang.createdon}</th>
            <th>{$lang.options}</th>
        </tr>
        {foreach from=$complains item=complain name=complains}
        <tr class="{cycle values='highlight_row,row'}">
            <td>{$complain.tname}</td>
            <td>{$complain.fname}</td>
            <td>{$complain.message}</td>
            <td>{$complain.timestamp|date_format:"%B %e, %Y %H:%M:%S"}</td>
            <td align="right">
            	{if $ts3vserver_rights.r_remove_complaints==1}
                <a href="ajax_request.php?mod=ts3admin&file=complains.ajax&deletecomp=1&sid={$sid}&vsid={$vsid}" title="{$lang.deletecomplain}"  id="delcomp{$smarty.foreach.complains.iteration}" >
                    <img src="{$imgsrc}/delete.png" />
                </a>
                <script type="text/javascript">
					document.getElementById("delcomp{$smarty.foreach.complains.iteration}").href += 
									"&tcldbid="+encodeURIComponent("{$complain.tcldbid}")+"&fcldbid="+encodeURIComponent("{$complain.fcldbid}");
                    $("#delcomp{$smarty.foreach.complains.iteration}").fancybox();
                </script>
                {/if}
            </td>
        </tr>
        {/foreach}
    </table>
</div>
<script type="text/javascript">
	$("#clearComplainListA").fancybox();
</script>
{/if}