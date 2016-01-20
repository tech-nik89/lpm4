<?php

	// connect to lpm3 db
	$c = @mysql_connect($_GET['host'], $_GET['user'], $_GET['password']);
	if ($c)
	{
		$d = @mysql_select_db($_GET['db'], $c);
		if ($d)
		{
			
			// connect to hfh db
			require_once('../config/database.config.php');
			$hfh_connection = @mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
			if ($hfh_connection)
			{
				$hfh_db = mysql_select_db(MYSQL_DATABASE);
				if ($hfh_db)
				{
					// list old users
					$result = mysql_query("SELECT * FROM `".$_GET['db']."`.`tbl_users`", $c) or die(mysql_error($c));
					while ($row = mysql_fetch_assoc($result))
					{
						// import user
						$bd = mktime(0, 0, 0, $row['month'], $row['day'], $row['year']);
						$sql = "INSERT INTO `".MYSQL_DATABASE."`.`".MYSQL_TABLE_PREFIX."users`
								(`email`, `password`, `nickname`, `prename`, `lastname`, `birthday`, `ban`, `activated`, `comment`)
								VALUES
								('".$row['email']."', '".$row['pswd']."', '".$row['nick']."', '".$row['prename']."', '".$row['lastname']."', ".(int)$bd.", ".(int)$row['banned'].", 1, '".$row['comment']."');";
						mysql_query($sql, $hfh_connection) or die(mysql_error($hfh_connection));
						
					}
					
					// done
					echo '<strong>'.mysql_num_rows($result).'</strong> users successfully imported. Click <em>Next</em> to proceed.';
				}
			}
		}
		else
			echo '<font color="#AA0000">Database not found.</font>';
	}
	else
		echo '<font color="#AA0000">Connection to database server failed.</font>';
	
	@mysql_close($c);
	@mysql_close($hfh_connection);
?>