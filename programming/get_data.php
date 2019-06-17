<?php
	if(isset($_GET['target'])){
		switch ($_GET['target']) {
			case 'eight_queens':
				system("./bin/eight_queens.o",$r);
				break;
			
			default:
				# code...
				break;
		}
	}
?>