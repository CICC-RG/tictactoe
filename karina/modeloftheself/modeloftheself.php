<?php
	/**
	* ModelOfTheSelf
	*/
	class ModelOfTheSelf
	{
		private $profiles;

		
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
	}
?>