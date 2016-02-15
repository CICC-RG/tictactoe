<?php
	
	//require_once __DIR__ . '/../../karina/memory/memory.php';
	require_once __DIR__ . '/../models/models.php';
	require_once __DIR__ . '/../views/view_board.php';
	/**
	* TicTacToe
	*/
	class Reasoner
	{
		private $working_memory;

		private $player_movement;
		private $current_perception; // current perception is BCPU
		//private $current_model_of_the_world;
		
		private $recognition;
		private $categorization;
		function __construct()
		{
			$this->working_memory = new WorkingMemory;
			$this->working_memory->setModelOfTheWorld(new ModelOfTheWorld);
			$this->working_memory->getModelOfTheWorld()->addMission('win_game');
			$this->working_memory->getModelOfTheWorld()->addTokens('O', 'X');


			//$this->current_model_of_the_world = new ModelOfTheWorld();
			//agregar mision
			//$this->current_model_of_the_world->addMission('win_game');
			//seleccionar tokens
			//first argument = machine
			//secund argument = player
			//$this->current_model_of_the_world->addTokens('O','X');
			$this->player_movement 	= new PlayerMovement;

			$this->recognition 		= new Recognition;
			$this->categorization 	= new Categorization;
		}

		public function sensing()
		{
			if( $_POST )
			{
				$keys 		= array_keys($_POST);
				$this->player_movement->setMovement($keys[0]);
				return true;
			}
			else
				return false;
		}

		public function perception()
		{
			if( $this->sensing() )
			{
				$this->current_perception = $this->player_movement->getMovement();
				$this->current_perception = $this->recognition->processInformation([ 'bcpu' => $this->current_perception, 'algorithmStrategy' => 'ReconizeAlgorithmStrategy' ]);
				$this->current_perception = $this->categorization->processInformation( ['bcpu' =>  $this->current_perception, 'algorithmStrategy' => 'CategorizationAlgorithmStrategy', 'modelOfTheWorld' => $this->working_memory->getModelOfTheWorld()] );
				$this->working_memory->setBCPU( $this->current_perception );
				return true;
			}
			else
				return false;
		}

		public function planning()
		{
			
			//var_dump( $this->working_memory->getModelOfTheWorld()->currentToken());
			//echo "<br>";
			//var_dump( $this->working_memory->getBCPU() );
			$t1 = new ModifyBoard;//$position, $token, $model_of_the_world
			//$plans['empty'][] = new 
			//var_dump($this->current_perception->getPerception()->getCategories()[0]->getCategory());
		}

		//cognitive loop
		public function run()
		{
			$board = $this->working_memory->getModelOfTheWorld()->getBoard();
			$perception = $this->current_perception->getPerception()->getInput()->getInformation();
			$perception = explode('_', $perception);
			if( empty( $board->getData($perception[0],  $perception[1]) ) )
			{
				$this->working_memory->getModelOfTheWorld()->getBoard()->setData( $perception[0], $perception[1], $this->working_memory->getModelOfTheWorld()->currentToken() );
				$this->working_memory->getModelOfTheWorld()->updateModelOfTheWorld();
				//determinar si gano el jugador
				$verify_winner = new VerifyWinner($this->working_memory->getModelOfTheWorld()->getBoard(), $this->working_memory->getModelOfTheWorld()->currentToken());
				$this->working_memory->getModelOfTheWorld()->changeTurn();

				//jugar maquina
				if(!$verify_winner->run())
				{
					$machine_plays 	= new MachinePlays($this->working_memory->getModelOfTheWorld());
					$position 		= $machine_plays->run();
					$this->working_memory->getModelOfTheWorld()->getBoard()->setData($position[0], $position[1],$this->working_memory->getModelOfTheWorld()->currentToken());
					$this->working_memory->getModelOfTheWorld()->updateModelOfTheWorld();
				}
				else
				{
					echo "<b>JUGADOR GANÓ</b>";
				}

				//determinar si la maquina gano
				$verify_winner = new VerifyWinner($this->working_memory->getModelOfTheWorld()->getBoard(), $this->working_memory->getModelOfTheWorld()->currentToken());
				$this->working_memory->getModelOfTheWorld()->changeTurn();
				if ($verify_winner->run()) 
				{
					echo "<b>MAQUINA GANÓ</b>";
				}
			}
		}

		public function reset()
		{
			$this->working_memory->getModelOfTheWorld()->getBoard()->create();
			$this->working_memory->getModelOfTheWorld()->updateModelOfTheWorld();
			header("Location: index.php");
		}

		public function showBoard()
		{
			ViewBoard::showBoard($this->working_memory->getModelOfTheWorld()->getBoard()->getCells(), []);
		}
	}
?>