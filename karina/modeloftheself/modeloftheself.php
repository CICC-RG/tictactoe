<?php
	/**
	* ModelOfTheSelf
	*/
	class ModelOfTheSelf
	{
		private $profiles;
		private $events;

		
		function __construct()
		{
			
		}

		public function getProfiles()
		{
			return $this->profiles;
		}

		public function setProfiles($value)
		{
			$this->profiles = $value;
		}

		public function getProfile($i)
		{
			return $this->profiles[$i];
		}

		public function addProfile($value, $i)
		{
			$this->profiles[$i] = $value;
		}


		public function getEvents()
		{
			return $this->events;
		}

		public function setEvents($value)
		{
			$this->events = $value;
		}

		public function getEvent($i)
		{
			return $this->events[$i];
		}

		public function addEvent($value, $i)
		{
			$this->events[$i] = $value;
		}
	}
?>