<?php
/**
 * This class provides some extra methods associated with string.
 *
 * @author Jan Papousek
 */
class ExtraString
{

	/**
	 * It checks if the string represents a datetime.
	 *
	 * @param string $datetime
	 * @return bool
	 */
	public static function isDateTime($datetime) {
		if (empty($datetime)) {
			throw new NullPointerException("datetime");
		}
		return ereg("^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$",$datetime);
	}

	/**
	 * The PHP function lcFirst is available in the PHP version >= 5.3.0,
	 * this method does the same but in the lower PHP versions.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function lowerFirst($string) {
		static $from	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		static $to		= "abcdefghijklmnopqrstuvwxyz";
		$first			= substr($string, 0, 1);
		$rest			= substr($string, 1, strlen($string) - 1);
		return strtr($first, $from, $to) . $rest;
	}

	/**
	 * It generates random string with specified length.
	 *
	 * @param int $length
	 * @return string
	 * @throws NullPointerException if the $lenght is empty.
	 * @throws InvalidArgumentException if the length is not positive number.
	 */
	public static function random($length) {
		if (empty ($length))  {
			throw new NullPointerException("length");
		}
		if ($length <= 0) {
			throw new InvalidArgumentException("length");
		}
		$chars = array_merge(range("a","z"), range("A","Z"), range(0,9));
		$result = "";
		for($i=0; $i<$length; $i++) {
			$result .= $chars[rand(0,count($chars)-1)];
		}
		return $result;
	}

}