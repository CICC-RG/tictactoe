<?php

	class ViewBoard
	{
		public function __construct()
		{
		}

		public function showBoard($modelOfTheWorld)
		{

			$output  = self::header();
			$output .= self::body($modelOfTheWorld);
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

		public function body($data)
		{
			$output  = "<form action='' method='POST'><table><tbody>";

			for ($i=0; $i < count($data) ; $i++) { 
				$output .= '<tr>';
				for ($j=0; $j < count($data[$i]); $j++) { 
					$output .= "<td><input type='submit' value='{$data[$i][$j]}' name='{$i}_{$j}' ";
					if($data[$i][$j] != '') 
						$output .= "disabled";
					$output .= "></td>";
				}
				$output .= '</tr>';
			}
			$output .= "<tr><td colspan='".count($data)."'><a href='?reset=1'>REINICIAR</a></td></tr>";
			$output .= "</tbody></table></form>";
			return $output;
		}

		private function footer()
		{
			return "</body></html>";
		}
	}
?>