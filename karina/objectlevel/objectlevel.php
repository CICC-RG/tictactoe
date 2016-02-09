<?php
	require_once  __DIR__ . '/../metacore/metacore.php';

	class Input  extends RootElement{
		private $information;
		private $type;

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

		public function setType($value)
		{
			$this->type = $value;
		}

		public function getType()
		{
			return $this->type;
		}
	}

	/**
	* Pattern
	*/
	class Pattern extends RootElement
	{
		private $patter;

		function __construct()
		{
			
		}

		public function addPatter($value)
		{
			$this->patter = $value;
		}

	}


	/**
	* 
	*/
	class BasicCognitiveProcessingUnit extends RootElement
	{
		private $input;
		private $patter;

		function __construct()
		{
			
		}

		public function addInput($value, $type_sensor)
		{
			$new_input = new Input;
			$new_input->setInformation($value);
			$new_input->setType($type_sensor);
			$this->setInput($new_input);
		}

		public function addPatter($value)
		{
			$p = new Pattern;
			$p->setPatter($value);
			$this->setPatter($p);
		}


		public function setPatter($value)
		{
			$this->patter = $value;
		}

		public function getPatter()
		{
			return $this->patter;
		}

		public function setInput($value)
		{
			$this->input = $value;
		}

		public function getInput()
		{
			return $this->input;
		}
	}


	/**
	* Recognition
	*/
	class Recognition extends CognitiveFunction
	{
		
		function __construct()
		{
			
		}

		public function processInformation($value)
		{
			$information 		= $value['bcpu']->getInput()->getInformation();
			$algorithmStrategy 	= new $$value['algorithmStrategy']($information);
			$reconition 		= $algorithmStrategy->run();

			//esto es hay que cambiarlo por IA!!!
			$perceptual_memory = new PerceptualMemory;
			$perceptual_memory->storeInformation(['value', 'recognized'], [ $information[0] . '_' . $information[1] , $reconition ]);
			$value->addPatter($reconition);
			return $value;
		}

		
	}


	/*abstract*/
	class Perception extends CognitiveFunction {
		private $perception; //type Input

		function __construct()
		{
			//$this->processInformation($value);
		}

		public function processInformation($value)
		{
			var_dump($value);
			$bcpi  = new BasicCognitiveProcessingUnit;
			$bcpi->addInput($value['information'], $value['type_sensor']);
			$this->setPerception($bcpi);
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