<?php

	function listAvailableMovies()
	{
		$list = scandir("./media/movie/");
		if (count($list) > 0)
		foreach($list as $l)
		{
			if (substr($l, 0, 1) != '.' && substr($l, strlen($l) - 4, 4) == '.ogv')
				$l2[] = $l;
		}
		return $l2;
	}

?>