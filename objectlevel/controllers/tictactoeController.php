<?php
	
	//require_once __DIR__ . '/../../karina/objectlevel/objectlevel.php';
	require_once __DIR__ . '/../models/models.php';
	require_once __DIR__ . '/../views/view_board.php';


	/**
	* TicTacToe
	*/
	class TictactoeControllers
	{
		private $player_movement;
		private $current_perception;
		private $current_model_of_the_world;
		
		function __construct()
		{
			$this->current_model_of_the_world = new ModelOfTheWorld();
			//agregar mision
			$this->current_model_of_the_world->addMission('win_game');
			//seleccionar tokens
			//first argument = machine
			//secund argument = player
			$this->current_model_of_the_world->addTokens('O','X');
			$this->player_movement = new PlayerMovement;
		}

		public function sensor($keys)
		{
			$this->player_movement->setMovement($keys);
		}

		public function perception()
		{
			$this->current_perception = $this->player_movement->getMovement();
		}

		public function run()
		{
			$board = $this->current_model_of_the_world->getBoard();
			$perception = $this->current_perception->getPerception()->getInformation();
			if( empty( $board->getData($perception[0],  $perception[1]) ) )
			{
				$this->current_model_of_the_world->getBoard()->setData( $perception[0], $perception[1], $this->current_model_of_the_world->currentToken() );
				$this->current_model_of_the_world->updateModelOfTheWorld();
				//determinar si gano el jugador
				$verify_winner = new VerifyWinner($this->current_model_of_the_world->getBoard(), $this->current_model_of_the_world->currentToken());
				$this->current_model_of_the_world->changeTurn();

				//jugar maquina
				if(!$verify_winner->run())
				{
					$machine_plays 	= new MachinePlays($this->current_model_of_the_world);
					$position 		= $machine_plays->run();
					$this->current_model_of_the_world->getBoard()->setData($position[0], $position[1],$this->current_model_of_the_world->currentToken());
					$this->current_model_of_the_world->updateModelOfTheWorld();
				}
				else
				{
					echo "<b>JUGADOR GANÓ</b>";
				}

				//determinar si la maquina gano
				$verify_winner = new VerifyWinner($this->current_model_of_the_world->getBoard(), $this->current_model_of_the_world->currentToken());
				$this->current_model_of_the_world->changeTurn();
				if ($verify_winner->run()) 
				{
					echo "<b>MAQUINA GANÓ</b>";
				}
			}
		}

		public function reset()
		{
			$this->current_model_of_the_world->getBoard()->create();
			$this->current_model_of_the_world->updateModelOfTheWorld();
			header("Location: index.php");
		}

		public function showBoard()
		{
			ViewBoard::showBoard($this->current_model_of_the_world->getBoard()->getCells(), []);
		}
	}
?>