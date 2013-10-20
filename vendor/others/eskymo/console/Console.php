<?php
class Console
{

	private final function  __construct() {}

	public static function loadArguments($argv) {
		unset($argv[0]);
		$result = array();
		foreach($argv AS $argument) {
			$splitted = split("=",$argument);
			if (sizeof($splitted) != 2) {
				throw new InvalidArgumentException("The argument $argument is not valid");
			}
			$key	= trim($splitted[0]);
			$value	= trim($splitted[1]);
			if (isset($result[$key])) {
				if (is_array($result[$key])) {
					if (!in_array($value, $result[$key])) {
						$result[$key][] = $value;
					}
				}
				else {
					if ($result[$key] != $value) {
						$result[$key] = array(
							0 => $result[$key],
							1 => $value
						);
					}
				}
			}
			else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

}

