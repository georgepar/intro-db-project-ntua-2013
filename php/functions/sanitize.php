<?php
// Escape quotes and set character encoding to utf8
function escape($inputString) {
	htmlentities($string, ENT_QUOTES, 'UTF-8');
}

?>