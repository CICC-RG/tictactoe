<?php
	session_start();
	//session_unset();
	require_once 'objectlevel/views/view_board.php';
	require_once 'objectlevel/controllers/reasoner.php';
	require_once 'karina/objectlevel/objectlevel.php';
	require_once 'karina/memory/memory.php';


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