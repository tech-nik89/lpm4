<?php

	/**
	 * Project: Higher For Hire
	 * File: breadcrumbs.core.php
	 *
	**/

	class Breadcrumbs
	{
		private $title = array();
		private $url = array();
		
		
		function clear()
		{
			$this->title = null;
			$this->title = null;
		}
		
		function removeElement($index)
		{
			$this->title[$index] = '';
		}
		
		function count()
		{
			return count($this->title);
		}
		
		function addElement($title, $url)
		{
			$this->title[] = $title;
			$this->url[] = $url; 
		}
		
		function get($bold = true, $class = '')
		{
			$output = Array();
			foreach ($this->title as $i => $title)
			{
				if (trim($title) != '')
				{
				
					if ($this->url[$i] != '')
					{

						if ($bold)
							$output[] = '<strong><a class="' . $class . '" href="' . $this->url[$i] . '">' . $title . '</a></strong>';
						else
							$output[] = '<a class="' . $class . '" href="' . $this->url[$i] . '">' . $title . '</a>';
							
					} else {
						
						if ($bold)
							$output[] = '<strong>' . $title . '</strong>';
						else
							$output[] = $title;
						
					}
				}
			}
			
			return @implode(' &raquo; ', $output);
			
		} 
		
		function getSmall() {
			$output = Array();
			foreach ($this->title as $i => $title)
			{
				if (trim($title) != '')
				{
					$output[] = $title;
				}
			}
			
			return @implode(' &raquo; ', $output);
		}
		
	}

?>