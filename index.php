<?php
	session_start();
	//unset($_SESSION);
	require_once 'objectlevel/views/view_board.php';
	require_once 'objectlevel/controllers/tictactoeController.php';
	require_once 'karina/objectlevel/objectlevel.php';
	require_once 'karina/memory/memory.php';
	//require_once 'objectlevel/models/models.php';
	//call controller
	$data_controllers = new TictactoeControllers;
	if( $_POST )
	{
		$keys 		= array_keys($_POST);
		$data_controllers->sensor($keys[0]);
		$data_controllers->perception();
		$data_controllers->run();
	}
	if( isset($_GET['reset']) )
	{
		$data_controllers->reset();
	}
	$data_controllers->showBoard();

?>