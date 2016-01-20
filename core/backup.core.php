<?php

	/**
	* LPM 4 - Higher For Hire Backup Class
	*
	*
	*/
	
	class Backup {
	
		function listTables() {
			global $db;
			
			$result = $db->query("SHOW TABLES");
			while ($row = mysql_fetch_array($result)) {
				$tables[] = $row[0];
			}
			return $tables;
		}
		
		function backupTables($tables) {
			$output = '<?xml version="1.0" standalone="yes" ?>'."\n";
			$output .= '<backup date="'.date("d.m.Y").'" time="'.date("H:i").'">'."\n";
			
			foreach (@$tables as $table) {
				$output .= $this->backupTable($table);
			}
			
			$output .= "</backup>\n";
			
			return $output;
		}
		
		function backupTable($table) {
			global $db;
			
			$output = "";
			$output .= $this->spaceTabs(1).'<table name="'.$table.'">'."\n";
			$result = $db->selectList($table, "*");
			foreach ($result as $rowIndex => $rowValue) {
				$output .= $this->spaceTabs(2).'<row>'."\n";
				foreach ($rowValue as $key => $value) {
					$output .= $this->spaceTabs(3).'<'.$key.'>'.$value.'</'.$key.">\n";
				}
				$output .= $this->spaceTabs(2)."</row>\n";
			}
			$output .= $this->spaceTabs(1)."</table>\n";
			return $output;
		}
		
		function restore($xml) {
			global $db;
			
			$array = $this->parseXMLtoArray($xml);
			foreach ($array['tables'] as $tbl_name => $table) {
				// Clear table
				$sql = "TRUNCATE `".$tbl_name."`;";
				$db->query($sql);
				
				// Restore data
				foreach($table as $row) {
					$fields = array();
					$values = array();
					foreach($row as $key => $value) {
						$fields[] = "`".$key."`";
						$values[] = "'".$value."'";
					}
					$sql = "INSERT INTO `".$tbl_name."`
							(".implode(", ", $fields).")
							VALUES
							(".implode(", ", $values).");";
					$db->query($sql);
				}
			}
			return true;
		}
		
		private function spaceTabs($tabs) {
			$return = "";
			for ($i = 0; $i < $tabs; $i++) $return .= "\t";
			return $return;
		}
		
		function parseXMLtoArray($xml) {
			$xml = trim($xml);
			$xmlarray = array();
			$tables = array();
			$backup = array();
			
			// Delete xml head
			$xml=substr($xml, strpos($xml, '?>')+2);
			
			// Read backup tags
			$startofbackuptag = strpos($xml, '<backup');
			$endoffirstbackuptag = strpos($xml, '>');
			$backupattributes = $this->getAttributes(substr($xml, $startofbackuptag+1, $endoffirstbackuptag));
			$endofbackuptag = strpos($xml, '</backup>');
			
			// Write date in final array
			$backup = $backupattributes;
			
			// Strip of backup tags
			$xml=substr($xml, $endoffirstbackuptag+1, $endofbackuptag-$endoffirstbackuptag-1);
			
			// Cut out ever table
			$startoftabletag = strpos($xml, '<table');
			$endoffirsttabletag = strpos($xml, '>');
			
			while($endoffirsttabletag > 0) {
				// Get Tagname and attributes
				$tagname = substr($xml, $startoftabletag+1, $endoffirsttabletag);
				$firstBlankInTag = strpos($xml, ' ', $startoftabletag);
				
				$name = substr($xml, $startoftabletag+1, $firstBlankInTag-$startoftabletag-1); 
				$attributes = $this->getAttributes(substr($xml, $startoftabletag+1, $endoffirsttabletag));		
				
				// Get Endtag position
				$endoftabletag = strpos($xml, '</table>');
				
				// Cut out the first part of the string
				$dummy_table = substr($xml, $endoffirsttabletag+1, $endoftabletag-$endoffirsttabletag-1);
				
				// Read out the rows
				$startofrowtag = strpos($dummy_table, '<row>');
				$endofrowtag = strpos($dummy_table, '</row>');
				
				while($endofrowtag > 0) {			
					// Cut out the first part of the table rows
					$dummy_row = trim(substr($dummy_table, $startofrowtag+strlen('<row>')+1, $endofrowtag-strlen('</row>')-strlen('<row>')-1));
					
					$table[$attributes['name']][] = $this->getValuesOfRow($dummy_row);;
					
					// Cut off the first row of the table string
					$dummy_table = substr($dummy_table, $endofrowtag+strlen('</row>'));
					
					$startofrowtag = strpos($dummy_table, '<row>');
					$endofrowtag = strpos($dummy_table, '</row>');
				}
				
				// Cut off the first part of the string
				$xml=substr($xml, $endoftabletag+strlen('</table>'));
				
				$startoftabletag = strpos($xml, '<table');
				$endoffirsttabletag = strpos($xml, '>');
			}
			$backup['tables'] = $table;

			return $backup;
		}

		function getValuesOfRow($row) {
			$rowarray = array();
			$firststarttag = strpos($row, '<');
			$firstendtag = strpos($row, '>');
			
			while($firstendtag > 0) {
				// Get Key
				$name = substr($row, $firststarttag+1, $firstendtag-1);
				
				// Get endtag
				$endtag = strpos($row, '</'.$name.'>');
				
				// Get Value
				$value = substr($row, $firstendtag+1, $endtag+1-strlen('</'.$name.'>'));

				// Save Keys and Values
				$rowarray[$name] = $value;
				
				// Cut off the frist column
				$row = trim(substr($row, $endtag+strlen('</'.$name.'>')));
				$firststarttag = strpos($row, '<');
				$firstendtag = strpos($row, '>');
			}
			return $rowarray;
		}

		function getAttributes($tag) {
			$tags = array();
			$start = strpos($tag, ' ');
			while($start > 1) {
				$equals  = strpos($tag, '=', $start);	
				$firstequotation = strpos($tag, '"', $start);
				$secondequotation  = strpos($tag, '"', $firstequotation+1);
				$tags[substr($tag, $start+1, $equals-$start-1)] = substr($tag, $firstequotation+1, $secondequotation-$firstequotation-1);
				$start = strpos($tag, ' ', $secondequotation);
			}
			return $tags;
		}
	}

?>