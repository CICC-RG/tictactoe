<?php
	require_once  __DIR__ . '/../metacore/metacore.php';
	class Input  extends Element{
		private $information;

		function __construct()
		{
			
		}

		public function setInformation($value)
		{
			$this->information = $value;
		}

		public function getInformation()
		{
			return $this->information;
		}
	}


	abstract class Perception extends CognitiveFunction {
		private $perception; //type Input

		function __construct($value)
		{
			//$this->processInformation($value);
		}

		public function processInformation($value)
		{
			$new_input  = new Input;
			$new_input->setInformation($value);
			$this->setPerception($new_input);
		}

		public function getPerception()
		{
			return $this->perception;
		}

		public function setPerception($value)
		{
			$this->perception = $value;
		}
	}


	abstract class CognitiveTask extends Task {

	}

	abstract class ReasoningTask extends CognitiveTask {
		private $strategies;

		public function setStrategies($value)
		{
			$this->strategies = $value;
		}

		public function getStrategies()
		{
			return $this->strategies;
		}

		public function addStrategy($value)
		{
			return $this->strategies[] = $value;
		}

		public function getStrategy($i)
		{
			return $this->strategies[$i];
		}
		public function buildProfail(){

		}
    }
		
	class ReasoningEvent extends Event
	{
		private $profile;
		function __construct()
		{
			
		}
		public function setProfile($value){
		  $this->profile=$value;	
		}
		public function getProfile(){
			return $this->profile;
		}


	}
	/**
	* pendiente de procesar clase MemoryEvent
	*/
	class MemoryEvent extends Event
	{
		
		function __construct()
		{
			
		}
	}


	
	/**
	* 
	*/
	class MouseSensor extends Sensor
	{
		
		function __construct()
		{
			$this->setType('mouse');
		}
	}



	

?>