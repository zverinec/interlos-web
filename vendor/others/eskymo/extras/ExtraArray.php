<?php
/**
 * This class provides some extra functions to manipulate with array.
 *
 * @author Jan Papousek
 */
class ExtraArray
{

	public static function get(array $array, $key) {
		if (empty($key)) {
			throw new NullPointerException("key");
		}
		return isset($array[$key]) ? $array[$key] : NULL;
	}
	/**
	 * It returns the first key name, or NULL if the array is empty.
	 *
	 * @param array $input
	 * @return mixed
	 */
	public static function firstKey(array $array) {
		foreach ($array AS $key => $value) {
			return $key;
		}
		return NULL;
	}

	/**
	 * It returns the first value, or NULL if the array is empty
	 *
	 * @param array $input
	 * @return mixed
	 */
	public static function firstValue(array $array) {
		foreach ($array AS $key => $value) {
			return $value;
		}
		return NULL;
	}

	/**
	 * It returns a key of the value. If there are more than one value the same,
	 * first value is chosen.
	 *
	 * @param array $array The array where the value is located
	 * @param mixed $value The value which key is wanted
	 * @return mixed The key
	 * @throws DataNotFoundException if the value is not in the array
	 */
	public static function keyOf(array $array, $value) {
		foreach ($array AS $key => $item) {
			if ($item == $value) {
				return $key;
			}
		}
		throw new DataNotFoundException("The value '$value' does not exist.");
	}

	/**
	 * It returns keys which contains the value
	 *
	 * @param array $array The array where the value is located
	 * @param mixed $value The value which keys are wanted
	 * @return array
	 */
	public static function keysOf(array $array, $value) {
		$result = array();
		foreach ($array AS $key => $item) {
			if ($item == $value) {
				$result[] = $key;
			}
		}
		return $result;
	}

	/**
	 * It returns the last value, or NULL if the array is empty
	 *
	 * @param array $input
	 * @return mixed
	 */
	public function lastValue(array $array) {
		if (empty($array)) {
			return NULL;
		}
		else {
			return end($array);
		}
	}

}

