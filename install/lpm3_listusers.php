<input type="hidden" name="host" id="host" value="<?php echo $_GET['host']; ?>" />
<input type="hidden" name="user" id="user" value="<?php echo $_GET['user']; ?>" />
<input type="hidden" name="password" id="password" value="<?php echo $_GET['password']; ?>" />
<input type="hidden" name="database" id="database" value="<?php echo $_GET['db']; ?>" />

<?php
	
	$c = @mysql_connect($_GET['host'], $_GET['user'], $_GET['password']);
	if ($c)
	{
		$d = @mysql_select_db($_GET['db'], $c);
		if ($d)
		{	
			$result = mysql_query("SELECT * FROM `".$_GET['db']."`.`tbl_users`", $c) or die(mysql_error($c));
			echo '<p><strong>' . mysql_num_rows($result) . '</strong> Users found.</p>
				<p><input type="button" name="do_import" value="Import users now" onClick="javascript:import();" /></p>';
		}
		else
			echo '<font color="#AA0000">Database not found.</font>';
	}
	else
		echo '<font color="#AA0000">Connection to database server failed.</font>';
	
	@mysql_close($c);
?>