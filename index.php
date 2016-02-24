<?php
	session_start();
	//session_unset();
	require_once 'objectlevel/views/view_board.php';
	require_once 'objectlevel/controllers/reasoner.php';
	require_once 'karina/objectlevel/objectlevel.php';
	require_once 'karina/memory/memory.php';


	$working_memory  = new WorkingMemory;
	$working_memory->setMentalState( new State('is_system_started', true) );
	$working_memory->setMentalState( new State('is_perceived', false) );
	$working_memory->setMentalState( new State('is_board_modified', false) );
	$working_memory->setMentalState( new State('is_player_winner_verified', false) );
	$working_memory->setMentalState( new State('is_player_turn_changed', false) );
	$working_memory->setMentalState( new State('is_machine_played', false) );
	$working_memory->setMentalState( new State('is_machine_winner_verified', false) );
	$working_memory->setMentalState( new State('is_machine_turn_changed', false) );
	$working_memory->setMentalState( new State('is_world_shown', false) );

	
	//require_once 'objectlevel/models/models.php';
	//call controller
	$reasoner = new Reasoner;
	if( isset($_GET['reset']) )
	{
		$reasoner->reset();
	}
	echo "perception<br>";
	if($reasoner->perception())
	{
		echo "planning<br>";
		$reasoner->planning();
		echo "execute plan<br>";
		$reasoner->run();
	}

	echo "show world<br>";
	$reasoner->showBoard();

?>