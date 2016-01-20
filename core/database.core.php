<?php
	
	/**
	 * Project: Higher For Higher
	 * File: database.core.php
	 *
	**/
	
	class Database {
		// Stores the connection object
		private $connection;
		
		// Counts the queries
		private $count_successfully = 0;
		private $count_failed = 0;
		
		// average query time
		private $query_time_sum = 0.0;
		
		// last query
		private $last_query = "";
		
		// Constructor
		// Initiates the connection to the database
		function __construct() {
			// Check if config file exists. If not, redirect to setup routine.
			if (!file_exists('./config/database.config.php'))
				header('Location: install.php');
				
			// Establish database connection
			include('./config/database.config.php');
			
			// Connect
			@$this->connection = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
			if ($this->connection === false)
				header('Location: install.php');
			
			// Select database
			$result = mysql_select_db(MYSQL_DATABASE, $this->connection);
			if ($result === false)
				header('Location: install.php');
			
			// debug output
			global $debug;
			$debug->add('db::connected');
		}
		
		// Destructor
		// Closes the connection at the end of the script
		function __destruct() {
			mysql_close($this->connection);
		}
		
		// main query function
		// accomplishes all queries
		function query($query) {
			global $notify;
			
			$query_lower = strtolower($query);
			if (strpos($query_lower, 'union') > 0) {
				die('UNION statements are now allowed in database queries.');
				return;
			}
			
			// query runtime start
			$start = microtime(true);
			
			// Tries to query
			if ($result = mysql_query($query, $this->connection)) {
				// Successfully
				$this->count_successfully++;
				$this->last_query = $query;
				
				// debug output
				global $debug;
				$debug->add('db::query', $query . '&nbsp;&nbsp;&nbsp;&nbsp;('.number_format((microtime(true)-$start), 4).' sec)');
				$this->query_time_sum += (microtime(true)-$start);
				
				return $result;
			}
			else {
				// something goes wrong, raise an error
				$this->count_failed++;
				
				die ('
					<h1>Error: ' . 'mysql:query ' . mysql_errno($this->connection) . '</h1>
					<p>' . mysql_error($this->connection) . '<br />' . $query . '</p>'
				);
				
				// debug output
				global $debug;
				$debug->add('db::query (failed)', $query . '&nbsp;&nbsp;&nbsp;&nbsp;('.number_format((microtime(true)-$start), 4).' sec)');
				$this->query_time_sum += (microtime(true)-$start);
			}
		}
		
		// returns a list as the result of a query
		function selectList($table, $field = "*", $where = "1", $order = '', $limit = '') {
			$list = array();
			$table = $this->addTablePrefix($table);
			
			// if an order is wished, insert ORDER keyword before
			if ($order != '')
				$order = " ORDER BY " . $order ;
			
			// if an limit has been entered, insert LIMIT keyword before
			if ($limit != '')
				$limit = " LIMIT " . $limit;
			
			// puzzle the query string
			$sql = "SELECT " . $field . " FROM `" . $table . "` WHERE " . $where . $order . $limit . ";";
			$result = $this->query($sql);
			
			// append all rows to an array
			while ($row = mysql_fetch_assoc($result)) {
				$list[] = $row;
			}
			
			return $list;
		}
		
		// Returns one field from one row of one table
		function selectOne($table, $field, $where = '1', $order = '') {
			$table = $this->addTablePrefix($table);
			
			// if an order is wished, insert ORDER keyword before
			if ($order != '')
				$order = " ORDER BY " . $order ;
			
			$sql = "SELECT `" . $field . "` FROM `" . $table . "` WHERE " . $where . $order . " LIMIT 1;";
			$result = $this->query($sql);
			$row = mysql_fetch_assoc($result);

			return ($row[$field]);
		}
		
		// Returns one row of one table
		function selectOneRow($table, $field = '*', $where = "1", $order = "") {
			$table = $this->addTablePrefix($table);
			
			// if an order is wished, insert ORDER keyword before
			if ($order != '')
				$order = " ORDER BY " . $order ;
			
			$sql = "SELECT " . ($field) . " FROM `" . ($table) . "` WHERE " . ($where) . $order . " LIMIT 1;";
			$result = $this->query($sql);
			$row = mysql_fetch_assoc($result);
			
			if ($row != false)
			foreach ($row as $i => $r)
				$row[$i] = ($r);
			
			return $row;
		}
		
		// Inserts data into a table
		function insert($table, $fields, $values) {
			$table = $this->addTablePrefix($table);
			
			$f = implode('`, `', $fields);
			
			foreach ($values as $v)
			{
				if (substr($v,0, 1) == "'" || substr($v, strlen($v)-1, 1) == "'")
					$vl[] = "'" . secureMySQL(substr($v, 1, strlen($v)-2)) . "'";
				else
					$vl[] = secureMySQL($v);
				
			}
			
			$v = implode(', ', $vl);
			
			
			$sql = "INSERT INTO `" . secureMySQL($table) . "`
					(`" . ($f) . "`)
					VALUES
					(" . ($v) . ");";
			
			
			$result = $this->query($sql);
			
			return mysql_insert_id();
		}
		
		// Deletes something out of one table
		function delete($table, $where = "1") {
			$table = $this->addTablePrefix($table);
			
			$sql = "DELETE FROM `" . $table . "` WHERE " . $where . ";";
			$result = $this->query($sql);
			
		}
		
		// Updates something in a table
		function update($table, $fields, $where = "1") {
			$table = $this->addTablePrefix($table);
			
			$sql = "UPDATE `" . $table . "` SET
					" . $fields . "
					WHERE " . $where . ";";
			$this->query($sql);
			
		}
		
		// Returns the number of rows returned by a query
		function num_rows($table, $where) {
			$table = $this->addTablePrefix($table);
			
			$sql = "SELECT * FROM `" . $table . "` WHERE " . $where . ";";
			$result = $this->query($sql);
			$num = mysql_num_rows($result);
			
			return $num;
		}
		
		// returns several query information
		function query_count() {
			return 'successfully: ' . $this->count_successfully . ' failed: ' . $this->count_failed;
		}
		
		function averageQueryTime() {
			return number_format($this->query_time_sum / $this->count_successfully, 4);
		}
		
		function sumQueryTime() {
			return number_format($this->query_time_sum, 4);
		}
		
		function addTablePrefix($table) {
			if (substr($table, 0, strlen(MYSQL_TABLE_PREFIX)) == MYSQL_TABLE_PREFIX)
				return $table;
			else
				return MYSQL_TABLE_PREFIX . $table;
		}
		
		function tableExists($table) {
			$sql = "
				SELECT table_name
				FROM information_schema.tables
				WHERE table_name = '".$table."'";
			$result = $this->query($sql);
			if (mysql_num_rows($result) == 0)
				return false;
			else
				return true;
		}
		
		function queryToList($sql) {
			// execute the query
			$result = $this->query($sql);
			
			// append all rows to an array
			$list = array();
			while ($row = mysql_fetch_assoc($result)) {	
				$list[] = $row;	
			}
			
			return $list;
		}
	}
?>