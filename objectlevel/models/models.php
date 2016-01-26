<?php 

require_once __DIR__ . '/../../karina/metacore/metacore.php';
require_once __DIR__ . '/../../karina/objectlevel/objectlevel.php';


/**
* Model of the word
*/
class ModelOfTheWorld
{
	private $board;
	private $is_created = false;

	function __construct()
	{
		$this->setStateIsCreated($_SESSION['is_created']);

		$this->board = new Board;
		$this->board->create();

		if( !$this->getStateIsCreated() )
		{
			$this->setStateIsCreated(true);
			$this->updateModelOfTheWorld($board);
		}
		else
		{
			$this->setBoard( $_SESSION['model_of_the_world'] );
		}
	}

	public function updateModelOfTheWorld($value)
	{
		$_SESSION['model_of_the_world'] = $value;
		$_SESSION['is_created'] = $this->getStateIsCreated();
	}


	public function getModelOfTheWorld()
	{
		return $this->board;
		//return $_SESSION['model_of_the_world'];
	}


	public function getStateIsCreated()
	{
		if( !empty($_SESSION['is_created']) )
			return $_SESSION['is_created'];
		else
			return $this->is_created;
	}

	public function setStateIsCreated($value)
	{
		$this->is_created = $value;
	}


	public function setBoard($value)
	{
		$this->board = $value;
	}

	public function getBoard()
	{
		$this->board;
	}
}


class Board extends Element
	{
		/*
			Atributo que almacena el tablero de juego en memoria
			Corresponde a una matriz
		*/
		public $cells= [];

		public function __construct()
		{
			
		}
		
		/*
			Crear arreglo bidimensional en PHP
		*/
		public function create()
		{
			for ($x=0; $x < 3; $x++) { 
				$cells[$x] = [];
			}
			for ($i=0; $i <=2; $i++) 
			{ 
				for ($j=0; $j <=2; $j++) 
				{ 
					$this->setData($i,$j,'');
				}
			}
		}		

		/*public function has($position)
		{
			if( empty($this->cells[$position] ) )
				return false;
			return true;			
		}*/

		/*
			Inicio de la sección de encapsulado
		*/
		public function getCells()
		{  
			return $this->cells;
		}

		public function getData($x,$y){
			return $this->cells[$x][$y]; 
		}

		public function setData($x,$y,$value)
		{ 
			$this->cells[$x][$y]=$value;
		}

	}

/**
* 
*/
class GetPlayerMove extends Perception
{
	
	function __construct($value)
	{
		$this->processInformation($value);
	}

}
?>