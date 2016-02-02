<?php
	require_once __DIR__ . '/../metacore/metacore.php';
	require_once __DIR__ . '/../objectlevel/objectlevel.php';

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


		public function select()
		{
			$sql = "SELECT * FROM {$this->getTable()} ORDER BY 'id' ASC LIMIT 1";
			$result = mysql_query($sql);
			var_dump($sql);
			$result = mysql_fetch_array($result);
			$new_perception = new Perception;
			$position 	= explode('_', $result['information']);
			$new_perception->processInformation($position);
			return $new_perception;
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

	/**
	* SensorMemory
	*/
	class SensorMemory extends Memory
	{
		
		function __construct()
		{
			$this->setTable('ssm');
		}
	}
?>