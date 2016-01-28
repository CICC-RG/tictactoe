<?php
	require_once __DIR__ . '/../core/core.php';

	/**
	* SelectStrategy
	*/
	class StrategySelection extends MetareasoningTask
	{
		private $strategies;

		function __construct($strategies)
		{
			$this->setStrategies($strategies);
		}

		public function run()
		{
			$select_best_strategy = new SelectBestStrategy($this->getStrategies());
			$strategy = $select_best_strategy->run();
			$this->setOutput($this->getStrategy($strategy));
			return $this->getOutput();
		}

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
	}


	/**
	* SelectBestStrategy
	*/
	class SelectBestStrategy extends ComputationalStrategy
	{
		
		private $strategies;
		function __construct($strategies)
		{
			$this->strategies = $strategies;
		}

		public function run()
		{
			$i = rand(0, count($this->strategies)-1);
			$this->setOutput($i);
			return $this->getOutput();
		}
	}

?>