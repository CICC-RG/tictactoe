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
	private $machine_token = "O";
	private $player_token = "X";
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
			$this->getBoard()->setCells( $_SESSION['cells'] );
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
		//$_SESSION['model_of_the_world'] = serialize($this);
		$_SESSION['cells'] = $this->getBoard()->getCells();
		$_SESSION['is_created'] = $this->getStateIsCreated();
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
			for ($x=0; $x <= 2; $x++) { 
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
		parent::__construct();
		$this->ssm = new SensorMemory;
	}

	public function setMovement($value)
	{
		$this->ssm->storeInformation(['information', 'type'], [ $value, $this->getType() ]);
	}

	public function getMovement()
	{
		return $this->ssm->retrieveInformation($this->getType());
	}
}


/**
* Muestra el board
*/
class ShowWorld extends Action
{
	private $working_memory;
	private $styles;
	function __construct($styles = [])
	{
		$this->setStyles($styles);
	}

	public function run()
	{
		$this->setWorkingMemory( new WorkingMemory );
		ViewBoard::showBoard($this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells(), $this->getStyles());
	}

	public function getStyles()
	{
		return $this->styles;
	}

	public function setStyles($value)
	{
		$this->styles = $value;
	}

	public function getWorkingMemory()
	{
		return $this->working_memory;
	}

	public function setWorkingMemory($value)
	{
		$this->working_memory = $value;
	}
}

/**
* se modifica el Board con un movimiento
*/
class ModifyBoard extends ReasoningTask
{
	private $working_memory;
	function __construct()
	{

	}

	public function run()
	{
		$this->setWorkingMemory(new WorkingMemory);

		$cells 		= $this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells();
		$position 	= $this->getWorkingMemory()->getBCPU()->getInput()->getInformation();
		$position   = explode("_", $position);
		$this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->setData( $position[0], $position[1], $this->getWorkingMemory()->getModelOfTheWorld()->currentToken() );
		$this->getWorkingMemory()->getModelOfTheWorld()->updateModelOfTheWorld();
		$this->getWorkingMemory()->setModelOfTheWorld($this->getWorkingMemory()->getModelOfTheWorld());
	}

	public function getWorkingMemory()
	{
		return $this->working_memory;
	}

	public function setWorkingMemory($value)
	{
		$this->working_memory = $value;
	}

}

/**
* se cambia el turno al siguiente jugador
*/
class ChangeTurn extends ReasoningTask
{
	private $working_memory;
	
	function __construct()
	{
		
	}

	public function run()
	{
		$this->setWorkingMemory(new WorkingMemory);
		$this->getWorkingMemory()->getModelOfTheWorld()->changeTurn();
		$this->getWorkingMemory()->syncModelOfTheWorld( $this->getWorkingMemory()->getModelOfTheWorld() );
	}

	public function getWorkingMemory()
	{
		return $this->working_memory;
	}

	public function setWorkingMemory($value)
	{
		$this->working_memory = $value;
	}
}


/**
* Extend Reasoning Task
*/
class VerifyWinner extends ReasoningTask
{
	private $working_memory;

	
	function __construct()
	{
	}

	public function setWorkingMemory($value)
	{
		$this->working_memory = $value;
	}

	public function getWorkingMemory()
	{
		return $this->working_memory;
	}

	public function run()
	{
		$this->setWorkingMemory(new WorkingMemory);
   		$columns 	= $this->transposition($this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells());
   		$temp 		= $this->diagonal($this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells());
   		
   		
   		$diagonal 	= $temp[0];
   		$cross 		= $temp[1];


   		for ($i=0; $i < 3; $i++) { 
   			//ganador en cualquier fila
   			$row  			= $this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells()[$i];
   			$count_token 	= $this->tell( $row, $this->getWorkingMemory()->getModelOfTheWorld()->currentToken());

   			if( $count_token == 3 )
   				return true;
   		}

   		for ($i=0; $i < 3; $i++) { 
   			//ganador en la columna
   			$column  		= $columns[$i];
   			$count_token 	= $this->tell( $column, $this->getWorkingMemory()->getModelOfTheWorld()->currentToken());

   			if( $count_token == 3 )
   				return true;
   		}

   		$count_d = $this->tell($diagonal, $this->getWorkingMemory()->getModelOfTheWorld()->currentToken());
   		$count_t = $this->tell($cross, $this->getWorkingMemory()->getModelOfTheWorld()->currentToken());

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
   		for ($i=0; $i < 3; $i++) { 
   			if( $cells[$i] == $token )
   				$temp++;
   		}
   		return $temp;
   	}

   	private function transposition($cells)
   	{
   		$temp = [];

   		for ($i=0; $i < 3; $i++) { 
   			for ($j=0; $j < 3; $j++) { 
   				$temp[$j][$i] = $cells[$i][$j];
   			}
   		}
   		return $temp;
   	}

   	private function diagonal($cells)
   	{
   		$tempd = [];
        $tempt = [];
   		for ($i=0; $i < 3; $i++) { 
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
	private $working_memory;
	private $tasks;
	
	function __construct()
	{

	}

	public function run()
	{
		$this->setWorkingMemory(new WorkingMemory);

		//$this->addStrategy( new RandomAlgorithmStrategy($this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells()) );
		//$this->addStrategy( new MinMaxAlgorithmStrategy($this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells()) );
   		$strategy = new RandomAlgorithmStrategy($this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->getCells());
   		//$this->buildProfile();
   		$position = $strategy->run();
   		$this->getWorkingMemory()->getModelOfTheWorld()->getBoard()->setData($position[0], $position[1],$this->working_memory->getModelOfTheWorld()->currentToken());
		$this->getWorkingMemory()->getModelOfTheWorld()->updateModelOfTheWorld();
		$this->getWorkingMemory()->setModelOfTheWorld($this->getWorkingMemory()->getModelOfTheWorld());}

	public function setWorkingMemory($value)
	{
		$this->working_memory = $value;
	}

	public function getWorkingMemory()
	{
		return $this->working_memory;
	}

}


/**
* CategorizationAlgorithmStrategy
*/
class CategorizationAlgorithmStrategy extends ComputationalStrategy
{
	private $value;
	private $categories;
	private $model_of_the_world;
	function __construct($categories, $value, $model_of_the_world)
	{
		$this->setCategories($categories);
		$this->setValue($value);
		$this->setModelOfTheWorld($model_of_the_world);
	}

	public function run()
	{
		$categories = [];
		$information = explode('_', $this->getValue());
		$board = $this->getModelOfTheWorld()->getBoard()->getCells();
		//se recorren las categorias
		foreach ($this->getCategories() as $category) {
			if( eval( "return '" . strtoupper($board[$information[0]][$information[1]]) . "' " . $category['symbol'] . " '" . strtoupper($category['value']) . "';" ) )
			{
				if( !in_array( $category['name'] , $categories) )
				{
					$categories[] = $category['name'];
				}
			}
		}
		return $categories;
	}


	public function getModelOfTheWorld()
	{
		return $this->model_of_the_world;
	}	

	public function setModelOfTheWorld($value)
	{
		$this->model_of_the_world = $value;
	}

	public function setCategories($value)
	{
		$this->categories = $value;
	}
	public function getCategories()
	{
		return $this->categories;
	}

	public function setValue($value='')
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}
}

/**
* ReconizeAlgorithmStrategy
*/
class ReconizeAlgorithmStrategy extends ComputationalStrategy
{
	private $value;
	function __construct($value)
	{
		$this->setValue($value);
	}

	public function run()
	{
		//un String con dos numeros entre 0 y 2 separados por un "_"
		if( ereg('[0-2]_[0-2]', $this->getValue())  )
			return true;
		else
			return false;
	}

	public function setValue($value='')
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
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
	   		$r = rand(0, 2);
	   		$count_col = count( $cells[$r] );
	   		$c = rand(0, 2);
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