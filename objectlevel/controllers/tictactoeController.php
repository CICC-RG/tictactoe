<?php
	
	//require_once __DIR__ . '/../../karina/objectlevel/objectlevel.php';
	require_once __DIR__ . '/../models/models.php';
	require_once __DIR__ . '/../views/view_board.php';


	/**
	* TicTacToe
	*/
	class TictactoeControllers
	{
		private $current_perception;
		private $current_model_of_the_world;
		
		function __construct()
		{
			$this->current_model_of_the_world = new ModelOfTheWorld();
		}

		public function perception($position)
		{
			$this->current_perception = new GetPlayerMove($position);
		}


		public function run()
		{

			$board = $this->current_model_of_the_world->getModelOfTheWorld();


			$information = $this->current_perception->getPerception()->getInformation();


			if( empty( $board->getData($information[0], $information[1] ) ))
			{
				$this->current_model_of_the_world->getModelOfTheWorld()->setData( $information[0], $information[1], 'X' );
				
				$this->current_model_of_the_world->updateModelOfTheWorld($board);

				//determinar si gano el jugador

				//jugar maquina

				//daterminar si la maquina gano

			}

		}


		public function showBoard()
		{
			
			ViewBoard::showBoard($this->current_model_of_the_world->getModelOfTheWorld());
		}


		
	}
?>