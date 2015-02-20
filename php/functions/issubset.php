<?php
	function issubset($array1, $array2) {
		for($i = 0; $i < count($array1); $i++) {
			if(!in_array($array1[$i], $array2)){
				return false;
			}	
		}
		return true;
	}

?>