<?php
	function generateSessionKey() {
		srand(time());
		$lenght = 0;
		$sessionKey = "";
		while ($lenght < 30) {
			$char = substr("0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRESTUVWXYZ", rand(0, strlen("0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRESTUVWXYZ") - 1), 1);
			if (!strstr($sessionKey, $char)) {
				$sessionKey .= $char;
				$lenght++;
			}
		}
		return $sessionKey;
	}
	return generateSessionKey();
?>
