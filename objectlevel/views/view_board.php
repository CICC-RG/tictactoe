<?php

	class ViewBoard
	{
		public function __construct()
		{
		}

		public function showBoard($modelOfTheWorld, $events)
		{
			$output  = self::header();
			$output .= self::body($modelOfTheWorld, $events);
			$output .= self::footer();
			echo $output;
		}

		private function header()
		{
			$output = 	"<html><head><title>Tic Tac Toe</title>";			
			$output .=  "<link rel='stylesheet' href='objectlevel/views/css/style.css'>";
			$output .= 	"</head><body>";
			return $output;
		}

		public function body($data, $events)
		{
			$output  = "<form action='' method='POST'><table class='tictactoe'><tbody>";

			for ($i=0; $i < 3 ; $i++) { 
				$output .= '<tr>';
				for ($j=0; $j < 3; $j++) { 
					$output .= "<td><input type='submit' value='{$data[$i][$j]}' name='{$i}_{$j}' ";
					/*if($data[$i][$j] != '') 
						$output .= "disabled";*/
					$output .= "></td>";
				}
				$output .= '</tr>';
			}
			$output .= "<tr><td colspan='".count($data)."'><a href='?reset=1'>REINICIAR</a></td></tr>";
			$output .= "</tbody></table></form>";

			$output .= "<div class='container'><table class='events'><thead><tr><th>Eventos</th></tr></thead><tbody>";
			for ($j=0; $j < count($events); $j++) { 
				$output .= "<tr><td>{$events[$j]->getName()}</td></tr>";
			}
			$output .= "</tbody></table></div>";
			return $output;
		}

		private function footer()
		{
			return "</body></html>";
		}
	}
?>