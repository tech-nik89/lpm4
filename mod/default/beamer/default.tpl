<div class="headline">{$lang.beamer_list}</div>

<script type="text/javascript">

	function openPopUp (beamerid) {
		newWindow = window.open('?mod=beamer&mode=run&beamerid='+beamerid+'&tpl=none', "abc", "width=800,height=600,status=yes,scrollbars=yes,resizable=yes");
		newWindow.focus();
	}

</script>

<table width="100%" border="0" cellpadding="5" cellspacing="0">

	<tr>
    	<th>{$lang.name}</th>
        <th>{$lang.edit}</th>
        <th>{$lang.run}</th>
    </tr>

	{foreach from=$beamerlist item=beamer}
    	
        <tr>
        	<td>{$beamer.name}</td>
            <td><a href="{$beamer.url}">{$lang.edit}</a></td>
            <td><input type="button" value="{$lang.run}" onClick="javascript:openPopUp({$beamer.beamerid});" /></td>
        </tr>
        
    {/foreach}

</table>