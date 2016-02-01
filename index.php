<?php
	session_start();
	require_once 'objectlevel/views/view_board.php';
	require_once 'objectlevel/controllers/tictactoeController.php';
	require_once 'karina/objectlevel/objectlevel.php';
	require_once 'karina/memory/memory.php';
	//require_once 'objectlevel/models/models.php';

	$m = new Memory;
	
	$m->setTable('ssm');
	//var_dump($m->create(['information', 'type'], ['hola', 'mouse']));

	
	//call controller
	$data_controllers = new TictactoeControllers;
	if( $_POST )
	{
		$keys 		= array_keys($_POST);
		$data_controllers->sensorMemory($keys);
		$position 	= explode('_', $keys[0]);

		$data_controllers->perception($position);
		$data_controllers->run();
	}
	if( isset($_GET['reset']) )
	{
		$data_controllers->reset();
	}
	$data_controllers->showBoard();

?>