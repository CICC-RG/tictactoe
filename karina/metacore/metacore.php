<?php

	//MetaElement deprecated
	abstract class RootElement
	{
		public $name;
	}

	abstract class Element extends RootElement{

	}

	abstract class CognitiveFunction extends RootElement{

		abstract public function processInformation($value);
	}



?>