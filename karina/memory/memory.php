<?php
	require_once __DIR__ . '/../metacore/metacore.php';
	require_once __DIR__ . '/../objectlevel/objectlevel.php';

	/**
	* 
	*/
	abstract class Memory extends EstructureElement
	{
		abstract public function storeInformation($fields, $values);	
		abstract public function retrieveInformation($cue);
		abstract public function forgetInformation($cue);
	}

	/**
	* SensorMemory
	*/
	class SensorMemory extends Memory
	{
		private $db = "karina";
		private $table;

		function __construct($host="localhost", $user="root", $pass="")
		{
			mysql_connect($host, $user, $pass);
			mysql_select_db($this->db);
			$this->setTable('ssm');
		}

		public function storeInformation($fields, $values)
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

		public function retrieveInformation($cue)
		{
			$sql = "SELECT * FROM {$this->getTable()} WHERE type = '{$cue}' ORDER BY id ASC LIMIT 1";
			$result = mysql_query($sql);
			$result = mysql_fetch_array($result);
			
			$this->forgetInformation($result);

			$new_perception = new Perception;
			//$position 	= explode('_', $result['information']);
			$new_perception->processInformation( [ 'information' => $result['information'], 'type_sensor' => $result['type']]);
			return $new_perception;
		}

		public function forgetInformation($cue)
		{
			$sql = "DELETE FROM {$this->getTable()} WHERE id = {$cue['id']}";
			mysql_query($sql);
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
	* 
	*/
	class PerceptualMemory extends Memory
	{
		private $db = "karina";
		private $table;

		function __construct($host="localhost", $user="root", $pass="")
		{
			mysql_connect($host, $user, $pass);
			mysql_select_db($this->db);
			$this->setTable('patter');
		}

		public function storeInformation($fields, $values)
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

		public function retrieveInformation($cue)
		{
			$sql = "SELECT * FROM {$this->getTable()} WHERE type = '{$cue}' ORDER BY id ASC LIMIT 1";
			$result = mysql_query($sql);
			$result = mysql_fetch_array($result);
			
			$this->forgetInformation($result);

			$new_perception = new Perception;
			//$position 	= explode('_', $result['information']);
			$new_perception->processInformation( [ 'information' =>  $result['information'],  'type_sensor' => $cue] );
			return $new_perception;
		}

		public function forgetInformation($cue)
		{
			$sql = "DELETE FROM {$this->getTable()} WHERE id = {$cue['id']}";
			mysql_query($sql);
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



	
	class WorkingMemory extends Memory
	{
		private $bcpu;
		private $model_of_the_world;
		private $profiles = [];
		
		function __construct()
		{
			if( !empty($_SESSION['bcpu']) )
			{
				$this->setBCPU( unserialize($_SESSION['bcpu']) );
			}

			if( !empty($_SESSION['model_of_the_world']) )
			{
				$this->setBCPU( unserialize($_SESSION['model_of_the_world']) );
			}

			if( !empty($_SESSION['profiles']) )
			{
				foreach ($_SESSION['profiles'] as $profile) {
					$this->setProfiles( unserialize($profile) );
				}
			}
		}


		public function storeInformation($fields, $values)
		{

		}
		public function retrieveInformation($cue){

		}
		public function forgetInformation($cue){

		}

		private function sync()
		{
			$this->setBCPU( unserialize($_SESSION['bcpu']) );
			$this->setModelOfTheWorld( unserialize($_SESSION['model_of_the_world']) );
			if( !empty($_SESSION['profiles']) )
			{
				foreach ($_SESSION['profiles'] as $profile) {
					$this->setProfiles( unserialize($profile) );
				}
				
			}
		}

		public function setProfiles($value)
		{
			$_SESSION['profiles'][] = serialize($value);
			$this->profiles[] = $value;
		}

		public function getProfiles($id)
		{
			$this->sync();
			return $this->profiles[$id];
		}

		public function getModelOfTheWorld()
		{
			$this->sync();
			return $this->model_of_the_world;
		}

		public function setModelOfTheWorld($value)
		{
			$_SESSION['model_of_the_world'] = serialize($value);
			$this->model_of_the_world = $value;
		}

		public function setBCPU($value)
		{
			$_SESSION['bcpu'] = serialize($value);
			$this->bcpu = $value;
		}

		public function getBCPU()
		{
			$this->sync();
			return $this->bcpu;
		}
	}
?>