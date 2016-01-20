<div class="headline">{$lang.options_memberships}</div>

<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
    	<th>{$lang.nickname}</th>
        <th>{$lang.prename}</th>
        <th>{$lang.lastname}</th>
    </tr>
    {foreach from=$list item=u}
    	<tr{cycle values=', class="highlight_row"'}>
        	<td><a href="{$u.url}">{$u.nickname}</a></td>
            <td>{$u.prename}</td>
            <td>{$u.lastname}</td>
        </tr>
    {/foreach}
</table>