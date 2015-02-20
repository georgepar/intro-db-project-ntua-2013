<?php
	class MyConfig {
		public static function get($path = null) {
			if($path) {
				$conf = $GLOBALS['config'];
				$path = explode('/', $path);
				
				foreach ($path as $attr) {
					if(isset($conf[$attr])) {
						$conf = $conf[$attr];
					}
				}
				return $conf;
			}
		}
	}
?>