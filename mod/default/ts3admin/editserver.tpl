<div class="headline">{$lang.teamspeak3} <pre>{$serveralias}</pre></div>

<form action="" method="post">
	<input type="hidden" name="id" value="{$serverid}" />
	<table>
		<tr>
        	<td>{$lang.name}:</td>
            <td><input type="text" name="name" value="{$servername}" /></td>
        </tr>
        <tr>
        	<td>{$lang.address}:</td>
            <td><input type="text" name="address" value="{$serveraddresse}" /></td>
        </tr>
        <tr>
        	<td>{$lang.query}:</td>
            <td><input type="text" name="query" value="{$serverqueryport}" /></td>
        </tr>
        <tr>
        	<td>{$lang.adminname}:</td>
            <td><input type="text" name="user" value="{$serveradminname}" /></td>
        </tr>
        <tr>
        	<td>{$lang.adminpasswd}:</td>
            <td><input type="password" name="passwd" value="{$serveradminpasswd}" /></td>
        </tr>
	</table>
    
	<input type="submit" name="save" value="{$lang.save}"/>
</form>