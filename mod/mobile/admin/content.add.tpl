<div class="headline">{$lang.content_add}</div>

<form action="" method="post">

    <p>
        <strong>{$lang.title}:</strong>&nbsp;&nbsp;&nbsp;<input type="text" name="title" value="{$thetitle}" />
    </p>
    
    <p>
        <textarea name="text" style="width:100%; height:200px;">{$text}</textarea>
    </p>
		
	<p>
		{$lang.assigned_group}: 
		<select name="assigned_groupid">
			{section name=i loop=$groups}
			<option value="{$groups[i].groupid}"{if $groupid == $groups[i].groupid} selected="selected"{/if}>{$groups[i].name}</option>
			{/section}
		</select>
	</p>

    <p>
        <input type="submit" name="submit" value="{$lang.add}" />
    </p>

</form>