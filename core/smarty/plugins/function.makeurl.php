<?php
	/*
	 * Smarty plugin
	 * -------------------------------------------------------------
	 * File:     function.makeurl.php
	 * Type:     function
	 * Name:     makeurl
	 * Purpose:  can be used to generate a dynamic url for lpm iv
	 * -------------------------------------------------------------
	 */
	function smarty_function_makeurl($params, $template)
	{
		if (isset($params['mod'])) {
			$mod = $params['mod'];
			unset($params['mod']);
			return makeURL($mod, $params);
		}
	}
?>