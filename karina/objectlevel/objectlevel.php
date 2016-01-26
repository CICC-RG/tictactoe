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

?>