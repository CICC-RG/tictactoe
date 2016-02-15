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
		private $pattern;

		function __construct()
		{
			
		}

		public function setPattern($value)
		{
			$this->pattern = $value;
		}

		public function getPattern()
		{
			return $this->pattern;
		}

	}

	/**
	* Categorie
	*/
	class Category extends RootElement
	{
		private $category;

		public function getCategory()
		{
			return $this->category;
		}

		public function setCategory($value)
		{
			$this->category = $value;
		}
	}


	/**
	* 
	*/
	class BasicCognitiveProcessingUnit extends RootElement
	{
		private $input;
		private $pattern;
		private $categories;

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

		public function addPattern($value)
		{
			$p = new Pattern;
			$p->setPattern($value);
			$this->setPattern($p);
		}

		public function addCategories($categories)
		{
			$temp = [];
			foreach ($categories as $category) {
				$c = new Category;
				$c->setCategory($category);
				$temp[] = $c;
			}
			$this->setCategories($temp);
		}

		public function setCategories($value)
		{
			$this->categories = $value;
		}

		public function getCategories()
		{
			return $this->categories;
		}


		public function setPattern($value)
		{
			$this->patter = $value;
		}

		public function getPattern()
		{
			return $this->pattern;
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
	* Categorization
	*/
	class Categorization extends CognitiveFunction
	{
		
		private $db = "karina";
		private $table = "categories";

		function __construct($host="localhost", $user="root", $pass="")
		{
			mysql_connect($host, $user, $pass);
			mysql_select_db($this->db);
		}

		public function processInformation($value)
		{
			$information 		= $value['bcpu']->getPerception()->getInput()->getInformation();
			$categories 		= $this->getCategories();
			$algorithmStrategy 	= new $value['algorithmStrategy']($categories, $information, $value['modelOfTheWorld']);
			$categorization 	= $algorithmStrategy->run();
			
			$value['bcpu']->getPerception()->addCategories($categorization);
			return $value['bcpu'];
		}

		public function getCategories()
		{
			$sql 	= "SELECT * FROM {$this->getTable()}";
			$result = mysql_query($sql);
			$temp = [];

			while( $row = mysql_fetch_array($result) )
			{
				$temp[] = $row;
			}

			return $temp;
		}

		public function getTable()
		{
			return $this->table;
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
			$information 		= $value['bcpu']->getPerception()->getInput()->getInformation();
			$algorithmStrategy 	= new $value['algorithmStrategy']($information);
			$reconition 		= $algorithmStrategy->run();

			//esto es hay que cambiarlo por IA!!!
			$perceptual_memory = new PerceptualMemory;
			$perceptual_memory->storeInformation(['value', 'recognized'], [ $information , $reconition ]);
			$value['bcpu']->getPerception()->addPattern($reconition);
			return $value['bcpu'];
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