<?php
	/*
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * File:     function.timeelapsed.php
	 * Type:     function
	 * Name:     timeelapsed
	 * Purpose:  returns a string containing the elapsed time since $time for lpm iv
	 * -------------------------------------------------------------
	 */
	function smarty_function_timeelapsed($params, $template)
	{
		if (isset($params['time'])) {
			return timeElapsed($params['time']);
		}
	}
?>