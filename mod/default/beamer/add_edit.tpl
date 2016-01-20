<div class="headline">{$action}</div>

<form action="" method="post">
	
    <table width="100%" border="0" cellpadding="0" cellspacing="5">
    	<tr>
        	<td width="150">{$lang.name}:</td>
            <td><input type="text" name="name" value="{$beamer.name}" /></td>
        </tr>
        <tr>
        	<td>{$lang.description}:</td>
            <td><textarea name="description" style="width:100%;" rows="8">{$beamer.description}</textarea></td>
        </tr>
    </table>
    
    <p>
    	<input type="submit" name="save" value="{$lang.save}" />
    </p>
    
</form>