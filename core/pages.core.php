<?php

	/**
	 * Project: Higher For Hire
	 * File: pages.core.php
	 *
	**/

	class Pages
	{
		private $current_page;
		var $range = 7;
		private $max_pages;
		
		private $max_values;
		private $values_per_page;
		
		function lastPage()
		{
			return $this->max_pages;
		}
		
		function thisPage()
		{
			return $this->current_page;
		}
		
		function setPages($current, $max)
		{
			$this->current_page = $current;
			$this->max_pages = $max;
		}
		
		function setValues($current, $values_per_page, $max)
		{
			global $debug;
			
			$this->max_pages = floor(($max - 1) / $values_per_page) + 1;
			
			$debug->add('<b>pages::currentValue</b>', $current);
			$debug->add('<b>pages::valuesPerPage</b>', $values_per_page);
			$debug->add('<b>pages::maxValue</b>', $max);
			
			if ($current == 0) $current = 1;
			$this->current_page = $current;
			$this->values_per_page = $values_per_page;
			
		}
		
		function currentValue()
		{
			$v = ($this->current_page - 1) * $this->values_per_page;
			return $v;
		}
		
		function get($mod, $params)
		{
			global $lang;
			
			$sites = $this->max_pages;
			$range = $this->range;
			$site = $this->current_page;
			
			$sitelist = array();
			
			if ($sites <= $range) {
		 		for ($i = 1; $i <= $sites; $i++) {
		  		$sitelist[] = $i;  //nächste zahl anhängen
		 		}
			} else {
		 		if ($site - ($range - 1) / 2 <= 0) {
		  			$offset = $site;
		 		} elseif ($site + ($range - 1) / 2 > $sites) {
		  			$offset = $range - ($sites - $site);
		 		} else {
		  			$offset = ($range + 1) / 2;
		 		}
		 		for ($i = 1; $i <= $range; $i++) {
		  			$sitelist[] = $i + $site - $offset;
		 		}
			}
			
			if (count($sitelist) > 0)
				foreach ($sitelist as $i => $val)
				{
					if ($val != $site)
					{
						$p = $params;
						$p['page'] = $val;
						$sitelist[$i] = '<a href="' . makeURL($mod, $p) . '">' . $val . '</a>';
					}
					else
					{
						$p = $params;
						$p['page'] = $val;
						$sitelist[$i] = '<strong>' . $val . '</strong>';
					}
					
				}
			
			if (count($sitelist) > 0)
				$sl = implode(" ", $sitelist);
			else
				$sl = "";
			
			
			if ($this->max_pages > $this->current_page)
			{
				$p1 = array('page' => $this->current_page + 1) + $p;
				$p2 = array('page' => $this->lastPage()) + $p;
				$sl = $sl . " | " . makeHTMLURL($lang->get('next'), makeURL($mod, $p1)) . " | " . makeHTMLURL($lang->get('last'), makeURL($mod, $p2));
			}
			else
				$sl = $sl . " | " . $lang->get('next') . " | " . $lang->get('last');
			
			if ($this->current_page > 1)
			{
				$p1 = array('page' => $this->current_page - 1) + $p;
				$p2 = array('page' => 1) + $p;
				$sl = makeHTMLURL($lang->get('first'), makeURL($mod, $p2)) . " | " . makeHTMLURL($lang->get('prev'), makeURL($mod, $p1)) . " | " . $sl;
			}
			else
				$sl = $lang->get('first') . " | " . $lang->get('prev') . " | " . $sl;
				
			return $sl;
			
		}
		
	}

?>