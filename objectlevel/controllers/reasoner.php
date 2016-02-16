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

		private $plans;

		//private $current_perception; // current perception is BCPU
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
				$this->working_memory->setBCPU($this->player_movement->getMovement());
				$this->working_memory->setBCPU( $this->recognition->processInformation([ 'bcpu' => $this->working_memory->getBCPU(), 'algorithmStrategy' => 'ReconizeAlgorithmStrategy' ]) );
				$this->working_memory->setBCPU($this->categorization->processInformation( ['bcpu' =>  $this->working_memory->getBCPU(), 'algorithmStrategy' => 'CategorizationAlgorithmStrategy', 'modelOfTheWorld' => $this->working_memory->getModelOfTheWorld()] ));
				//$this->current_perception = $this->player_movement->getMovement();
				//$this->current_perception = $this->recognition->processInformation([ 'bcpu' => $this->current_perception, 'algorithmStrategy' => 'ReconizeAlgorithmStrategy' ]);
				//$this->current_perception = $this->categorization->processInformation( ['bcpu' =>  $this->current_perception, 'algorithmStrategy' => 'CategorizationAlgorithmStrategy', 'modelOfTheWorld' => $this->working_memory->getModelOfTheWorld()] );
				//$this->working_memory->setBCPU( $this->current_perception );
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
			$modify_board = new ModifyBoard;
			$verify_winner = new VerifyWinner;
			$change_turn = new ChangeTurn;
			$machine_plays = new MachinePlays;
			$show_world = new ShowWorld;


			$this->plans['empty'] = new Plan;
			$this->plans['empty']->setAction( $modify_board );
			$this->plans['empty']->setAction( $verify_winner );
			$this->plans['empty']->setAction( $change_turn );
			$this->plans['empty']->setAction( $machine_plays );
			$this->plans['empty']->setAction( $verify_winner );
			$this->plans['empty']->setAction( $change_turn );
			//$plans['empty']->setAction( $show_world );

			//var_dump($this->current_perception->getPerception()->getCategories()[0]->getCategory());
		}

		//cognitive loop
		public function run()
		{
			
			foreach ($this->working_memory->getBCPU()->getCategories() as $category) {
				$this->plans[$category->getCategory()]->executePlan();
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
			$working_memory = new WorkingMemory;
			ViewBoard::showBoard($working_memory->getModelOfTheWorld()->getBoard()->getCells(), []);
		}
	}
?>