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
		//$this->setBoard(new Board) ;

		$this->board = new Board;
		$this->board->create();
		if( !$this->getStateIsCreated() )
		{
			$this->setStateIsCreated(true);
			$this->updateModelOfTheWorld();
			
		}
		else
		{
			$this->getModelOfTheWorld()->setCells( $_SESSION['model_of_the_world'] );
		}
	}

	public function updateModelOfTheWorld()
	{
		$_SESSION['model_of_the_world'] = $this->getModelOfTheWorld()->getCells();
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

		public function setCells($value)
		{
			$this->cells = $value;
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

/**
* Extend Reasoning Task
*/
class VerifyWinner extends ReasoningTask
{
	private $ModelOfTheWorld;
	private $token;
	
	function __construct($ModelOfTheWorld, $token)
	{
		$this->ModelOfTheWorld = $ModelOfTheWorld;
		$this->token = $token;
	}

	public function run()
	{
		
   		$columns 	= $this->transposition($this->ModelOfTheWorld->getCells());
   		$temp 		= $this->diagonal($this->ModelOfTheWorld->getCells());
   		
   		
   		$diagonal 	= $temp[0];
   		$cross 		= $temp[1];


   		for ($i=0; $i < count($this->ModelOfTheWorld->getCells()); $i++) { 
   			$row  			= $this->ModelOfTheWorld->getCells()[$i];
   			$count_token 	= $this->tell( $row, $this->token);

   			if( $count_token == 3 )
   				return true;
   		}

   		for ($i=0; $i < count($columns); $i++) { 
   			$column  		= $columns[$i];
   			$count_token 	= $this->tell( $column, $this->token);

   			if( $count_token == 3 )
   				return true;
   		}

   		$count_d = $this->tell($diagonal, $this->token);
   		$count_t = $this->tell($cross, $this->token);

   		if( $count_d == 3 )
   			return true;

   		if( $count_t == 3 )
   			return true;

   		return false;
   	}

   	private function tell($cells, $token)
   	{
   		$temp = 0;
   		for ($i=0; $i < count($cells); $i++) { 
   			if( $cells[$i] == $token )
   				$temp++;
   		}
   		return $temp;
   	}

   	private function transposition($cells)
   	{
   		$temp = [];

   		for ($i=0; $i < count($cells); $i++) { 
   			for ($j=0; $j < count($cells[$i]); $j++) { 
   				$temp[$j][$i] = $cells[$i][$j];
   			}
   		}
   		return $temp;
   	}

   	private function diagonal($cells)
   	{
   		$tempd = [];
        $tempt = [];
   		for ($i=0; $i < count($cells); $i++) { 
   			$tempd[]=$cells[$i][$i];
 	        $tempt[]= $cells[$i][2-$i];
   			
   		}
   		return [$tempd,$tempt];
   	}





}
?>