<?php

	//MetaElement deprecated
	abstract class RootElement
	{
		public $name;

		public function getName()
		{
			return $this->name;
		}

		public function setName($value)
		{
			$this->name = $value;
		}
	}

	abstract class Element extends RootElement{

	}

	abstract class FuntionalElement extends RootElement {
		public $state;
		public $start_time;
		public $end_time;
		
		private $effect;
		private $precodition;

		function __construct()
		{
			$this->setPrecondition(new State('name', false));
			$this->setEffect(new State('name', false));
		}


		public function setEffect($value)
		{
			$this->effect = $value;
		}

		public function getEffect()
		{
			return $this->effect;
		}

		public function setPrecondition($value)
		{
			$this->precodition = $value;
		}

		public function getPrecondition()
		{
			return $this->precodition;
		}
	}

	abstract class Action extends FuntionalElement {

	}

	abstract class Task extends FuntionalElement {
		//es posible que vaya arriba
		public $goal;

		function __construct()
		{
			$this->setGoal(new Goal);
		}
		abstract public function run();

		public function setGoal($value)
		{
			$this->goal = $value;
		}

		public function getGoal()
		{
			return $this->goal;
		}		
	}

	class Goal extends Element{
		public $current_state;
		public $target_state;

		function __construct()
		{
			$this->setCurrrentState(new State("current_state", false));
			$this->setTargetState(new State("target_state", true));
		}

		public function setCurrrentState($value)
		{
			$this->current_state = $value;
		}

		public function getCurrentState()
		{
			return $this->current_state;
		}

		public function setTargetState($value)
		{
			$this->target_state = $value;
		}

		public function getTargetState()
		{
			return $this->target_state;
		}
	}

	class State extends Element
	{
		private $value;

		function __construct($name, $value)
		{
			$this->setName($name);
			$this->setValue($value);
		}

		public function setValue($value)
		{
			$this->value = $value;
		}

		public function getValue()
		{
			return $this->value;
		}
	}

	abstract class CognitiveFunction extends RootElement{

		abstract public function processInformation($value);
	}


?>