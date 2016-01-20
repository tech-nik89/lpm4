<?php
	
	$c = @mysql_connect($_GET['host'], $_GET['user'], $_GET['password']);
	if ($c)
	{
		$d = @mysql_select_db($_GET['db'], $c);
		if ($d) {
			echo '<font color="#00AA00">Connection to database server successfully established.</font>';
			echo '<script type="text/javascript">$("#next").removeAttr("disabled");</script>';
		}
		else {
			echo '<font color="#AA0000">Database not found.</font>';
		}
	}
	else
		echo '<font color="#AA0000">Connection to database server failed.</font>';
	
	@mysql_close($c);
?>