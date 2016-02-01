<?php
	require_once __DIR__ . '/../metacore/metacore.php';

	/**
	* 
	*/
	class Memory extends EstructureElement
	{
		private $db = "karina";
		private $table;

		function __construct($host="localhost", $user="root", $pass="")
		{
			mysql_connect($host, $user, $pass);
			mysql_select_db($this->db);
		}

		public function create($fields, $values)
		{
			$sql = "INSERT INTO {$this->getTable()} (";

			
			$c = 1;
			for ($i=0; $i < count($fields); $i++) { 
				
				$sql .= "{$fields[$i]}";
				if( $c != count($fields) )
					$sql .= ", ";
				$c++;
			}

			$sql .= ") VALUES (";
			
			$c = 1;
			for ($i=0; $i < count($values); $i++) { 
				
				$sql .= "'{$values[$i]}'";
				if( $c != count($values) )
					$sql .= ", ";
				$c++;
			}

			$sql .= ")";
			
			
			if(!mysql_query($sql))
				return mysql_error();
			else
				return true;

		}


		public function setTable($value)
		{
			$this->table = $value;
		}

		public function getTable()
		{
			return $this->table;
		}
	}
?>