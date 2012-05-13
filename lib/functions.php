<?php

function stringContains($string, $keyword) {

	$pos = strpos(strtolower($string), strtolower($keyword));
	if($pos === false) {
		return false;
	} else {
		return true;
	}

}
