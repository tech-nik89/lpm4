<div class="headline">{$lang.event_paystate}</div>

{literal}
<script type="text/javascript">
  function contentloader() {
	$("#searchresults").load("ajax_request.php?mod=admission&file=userlist.ajax&search_string="+$("#search_string").val()+"&eventid="+{/literal}{$event.eventid}{literal});
  }
</script>
{/literal}

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
		<td width="100">{$lang.event_reg_count}:</td>
		<td>{$event.registered}</td>
	</tr>
	<tr>
		<td>{$lang.event_pay_count}:</td>
		<td>{$event.payed} ({$event.payed_pre} {$lang.prepayment}, {$event.payed_box_office} {$lang.box_office})</td>
	</tr>
</table>
<p>&nbsp;</p>
<form action="" method="post">
	<p>
		<strong>{$lang.search}:</strong>
		<input type="text" autocomplete="off" name="search_string" onkeyup="contentloader();" id="search_string" style="width:350px;" value="{$search_string}" />
		<input type="submit" name="search" value="{$lang.go}" />
	</p>
</form>

    <table width="100%" border="0" cellpadding="5" cellspacing="1">
        
        <tr>
			<th width="10%">User ID</th>
            <th width="15%">{$lang.nickname}</th>
            <th width="15%">{$lang.lastname}</th>
            <th width="20%">{$lang.prename}</th>
            <th width="20%">{$lang.event_paystate}</th>
            <th>{$lang.appeared}</th>
            <th>&nbsp;</th>
        </tr>
     
     </table>
    
	<div id="searchresults">
        {section name=i loop=$list}
        <form action="" method="post">
        
             <table width="100%" border="0" cellpadding="5" cellspacing="1">
             
                <tr class="{cycle values=',highlight_row'}">
					<td width="10%">{$list[i].userid|string_format:"%04d"}</td>
                    <td width="15%">{$list[i].nickname}</td>
                    <td width="15%">{$list[i].lastname}</td>
                    <td width="20%">{$list[i].prename}</td>
                    <td width="20%">
                        <input type="hidden" name="search" />
                        <input type="hidden" name="search_string" value="{$search_string}" />
                        {html_options name=paystate options=$paystates selected=$list[i].payed}
                        <input type="hidden" name="userid" value="{$list[i].userid}" />
                    </td>
                    <td><input type="checkbox" name="appeared" {if $list[i].appeared==1}checked="checked"{/if} value="1" /></td>
                    <td align="right"><input type="submit" name="save" value="{$lang.save}" /></td>
                </tr>
             </table>
     	
        </form>
        {/section}
	</div>