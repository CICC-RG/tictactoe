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
	private $mission;
	private $machine_token;
	private $player_token;
	private $is_me_turn = false;

	function __construct()
	{

		//$this->setBoard(new Board) ;

		$this->board = new Board;
		$this->board->create();
		$this->setMission( new Goal() );

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

	public function changeTurn()
	{
		$this->setStateIsMeTurn( !$this->getStateIsMeTurn() );
	}

	public function currentToken()
	{
		if( $this->getStateIsMeTurn() )
			return $this->getMachineToken();
		else
			return $this->getPlayerToken();
	}

	public function getStateIsMeTurn()
	{
		return $this->is_me_turn;
	}

	public function setStateIsMeTurn($value)
	{
		$this->is_me_turn = $value;
	}

	public function getMachineToken()
	{
		return $this->machine_token;
	}

	public function setMachineToken($value)
	{
		$this->machine_token = $value;
	}

	public function getPlayerToken()
	{
		return $this->player_token;
	}

	public function setPlayerToken($value)
	{
		$this->player_token = $value;
	}


	public function addTokens($machine_token, $player_token)
	{
		$this->setMachineToken($machine_token);
		$this->setPlayerToken($player_token);
	}
	public function addMission($value)
	{
		$this->getMission()->getCurrentState()->setName($value);
		$this->getMission()->getCurrentState()->setValue(false);
		$this->getMission()->getTargetState()->setName($value);
		$this->getMission()->getTargetState()->setValue(true);
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
		return $this->board;
	}

	public function getMission()
	{
		return $this->mission;
	}

	public function setMission($value)
	{
		$this->mission = $value;
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
class PlayerMovement extends MouseSensor
{
		
	private $ssm;
	function __construct()
	{
		$this->ssm = new SensorMemory;
	}

	public function setMovement($value)
	{
		$this->ssm->create(['information', 'type'], [ $value, $this->getType() ]);
	}

	public function getMovement()
	{
		return $this->ssm->select();
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
   			//ganador en cualquier fila
   			$row  			= $this->ModelOfTheWorld->getCells()[$i];
   			$count_token 	= $this->tell( $row, $this->token);

   			if( $count_token == 3 )
   				return true;
   		}

   		for ($i=0; $i < count($columns); $i++) { 
   			//ganador en la columna
   			$column  		= $columns[$i];
   			$count_token 	= $this->tell( $column, $this->token);

   			if( $count_token == 3 )
   				return true;
   		}

   		$count_d = $this->tell($diagonal, $this->token);
   		$count_t = $this->tell($cross, $this->token);

   		//ganador en la digonal
   		if( $count_d == 3 )
   			return true;

   		//ganador trasversal
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

/**
* Machine plays
*/
class MachinePlays extends ReasoningTask
{
	private $board;
	private $tasks;
	
	function __construct($board)
	{

		$this->setBoard($board);
		$this->addStrategy( new RandomAlgorithmStrategy($this->getBoard()->getBoard()->getCells()) );
		$this->addStrategy( new MinMaxAlgorithmStrategy($this->getBoard()->getBoard()->getCells()) );
		//$this->setToken($token);
	}

	public function run()
	{
		$cells = $this->getBoard()->getBoard()->getCells();
   		$strategy = new RandomAlgorithmStrategy($cells);
   		$this->buildProfile();
   		return $strategy->run();
	}

	public function setBoard($value)
	{
		$this->board = $value;
	}

	public function getBoard()
	{
		return $this->board;
	}

	public function setToken($value)
	{
		$this->token = $value;
	}

	public function getToken()
	{
		return $this->token;
	}
}

/**
* RandomStrategy
*/
class RandomAlgorithmStrategy extends ComputationalStrategy
{
	private $cells;

	function __construct($cells)
	{
		$this->setCells($cells);
	}

	public function run()
	{
		$cells = $this->getCells();
		$count_rows = count($cells);	
   		do
   		{
	   		$r = rand(0, $count_rows-1);
	   		$count_col = count( $cells[$r] );
	   		$c = rand(0, $count_col-1);
   		} while( !empty( $cells[$r][$c]  ) );
   		return [$r,$c];
	}

	public function getCells()
	{
		return $this->cells;
	}

	public function setCells($value)
	{
		$this->cells = $value;
	}
}

/**
* MinMax
*/
class MinMaxAlgorithmStrategy extends ComputationalStrategy
{
	private $cells;

	function __construct($cells)
	{
		$this->setCells($cells);
	}

	public function run()
	{
		return [1,1];
	}

	public function getCells()
	{
		return $this->cells;
	}

	public function setCells($value)
	{
		$this->cells = $value;
	}
}
?>