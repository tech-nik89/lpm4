<table width="100%" border="0" cellpadding="5" cellspacing="1">
	<tr>
        {section name=i loop=$areas}
        	<td valign="top" width="50%">
                <div class="headline">{$areas[i].title}</div>
                <div>
                    {$areas[i].content}
                </div>
            </td>
            {cycle values=",</tr><tr>"}
        {/section}
    </tr>
</table>