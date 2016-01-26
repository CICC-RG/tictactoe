<?php
//require_once 'vendors/Gatito.php';
session_start();
	require_once 'objectlevel/views/view_board.php';
	require_once 'objectlevel/controllers/tictactoeController.php';
	require_once 'karina/objectlevel/objectlevel.php';
	//require_once 'objectlevel/models/models.php';

	//call controller

	$data_controllers = new TictactoeControllers;
	if( $_POST )
	{
		$keys 		= array_keys($_POST);
		$position 	= explode('_', $keys[0]);
		$data_controllers->perception($position);
		$data_controllers->run();
	}
	
	
	$data_controllers->showBoard();


	//$draw_board = new ViewBoard($data_board->getCells());

?>