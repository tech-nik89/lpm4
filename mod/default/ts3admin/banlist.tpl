{section name=notify loop=$notify}
    <div class="notification">
        <div><strong>{$notify[notify].subject}</strong></div>
        <div style="margin-left:10px;">{$notify[notify].message}</div>
    </div>
{/section}

{if $clearbanlist}
<script type="text/javascript">
	function clearbanlist() {
		$("#delbanContent").load("ajax_request.php?mod=ts3admin&file=banlist.ajax&clearbanlistack=1&sid={$sid}&vsid={$vsid}");
	}
</script>
<div id="delbanContent">
    <table style="width:300px;">        
        <tr>
            <td colspan="3">{$lang.askclearbanlist}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: clearbanlist(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $clearedbanlist}
<center>
    {$lang.clearedbanlist}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); return false;" />
</center>
{else if $bandel}
<script type="text/javascript">
	function delban() {
		$("#delbanContent").load("ajax_request.php?mod=ts3admin&file=banlist.ajax&deletebanack=1&sid={$sid}&vsid={$vsid}&bid={$bid}");
	}
</script>
<div id="delbanContent">
    <table style="width:300px;">        
        <tr>
            <td colspan="3">{$lang.askdelban}</td>
        </tr>
        <tr>
            <td><input type="submit" value="{$lang.yes}" style="width:50px;" onclick="javascript: delban(); return false;"/></td>
            <td width="100%"></td>
            <td align="right"><input type="submit" value="{$lang.no}"  style="width:50px;" onclick="javascript: $.fancybox.close(); return false;" /></td>
        </tr>
    </table>
</div>
{else if $bandeleted}
<center>
    {$lang.bandeleted}<br />
    <input type="submit" value="{$lang.ok}" onclick="javascript:$.fancybox.close(); return false;" />
</center>
{else if not $nobans}
<div id="banlistContent">
	{if $ts3vserver_rights.r_remove_bans==1}
    <a href="ajax_request.php?mod=ts3admin&file=banlist.ajax&clearbanlist=1&sid={$sid}&vsid={$vsid}" id="clearBanListA" title="{$lang.clearbanlist}">{$lang.clearbanlist}</a><br/>
    {/if}
    <br/>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th>{$lang.name}</th>
            <th>{$lang.uid}</th>
            <th>{$lang.createdon}</th>
            <th>{$lang.duration}</th>
            <th>{$lang.remaining}</th>
            <th>{$lang.invokername}</th>
            <th>{$lang.reason}</th>
            <th>{$lang.enforcements}</th>
            <th>{$lang.options}</th>
        </tr>
    {foreach from=$banlist item=ban name=bans}
        <tr class="{cycle values='highlight_row,row'}">
            <td>{$ban.name}</td>
            <td>{$ban.uid}</td>
            <td>{$ban.created|date_format:"%B %e, %Y %H:%M:%S"}</td>
            <td>{$ban.duration}</td>
            <td>{$ban.remaining}</td>
            <td>{$ban.invokername}</td>
            <td>{$ban.reason}</td>
            <td>{$ban.enforcements}</td>
            <td align="right">
                {if $ts3vserver_rights.r_remove_bans==1}
                <a href="ajax_request.php?mod=ts3admin&file=banlist.ajax&deleteban=1&sid={$sid}&vsid={$vsid}&bid={$ban.banid}" title="{$lang.deleteban}" 
                        id="delban{$smarty.foreach.bans.iteration}" >
                    <img src="{$imgsrc}/delete.png" />
                </a>
                <script type="text/javascript">
                    $("#delban{$smarty.foreach.bans.iteration}").fancybox();
                </script>
                {/if}
            </td>
        </tr>
    {/foreach}
    </table>
</div>
<script type="text/javascript">
	$("#clearBanListA").fancybox();
</script>
{/if}
