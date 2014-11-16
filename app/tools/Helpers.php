<?php
/**
 * The static class which provides filters to presenters.
 *
 * @author Jan Papousek
 */
final class Helpers {

    /** @var Texy */
    private static $texy;

    final private function  __construct() {

    }

    /**
     * It returns the callback for helper with given name
     * @param string $helper The name of helper.
     * @return callback The callback to the helper.
     * @throws InvalidArgumentException if the $helper is empty.
     * @throws DataNotFoundException if the helper does not exist.
     */
    public static function getHelper($helper) {
	if (empty($helper)) {
	    throw new InvalidArgumentException("helper argument is empty");
	}
	switch ($helper) {
	    case "date": return array(get_class(), 'dateFormatHelper');
		break;
	    case "time": return array(get_class(), 'timeFormatHelper');
		break;
	    case "translate": return array(get_class(), 'translateHelper');
		break;
	    case "timeOnly": return array(get_class(), "timeOnlyHelper");
		break;
	    case 'texy': return array(get_class(), "texyHelper");
	    default:
		throw new DataNotFoundException("helper: $helper");
	}
    }

    /**
     * It returns date in format 'day.month.year'
     *
     * @param $date string Time in format 'YYYY-MM-DD HH:mm:ms'
     * @return string Formated date.
     */
    public static function dateFormatHelper($date) {
	return preg_replace(
		"/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/",
		"\\3. \\2. \\1",
		$date
	);
    }

    /**
     * It returns time in format 'day.month.year, hour:second'
     *
     * @param $time string Time in format 'YYYY-MM-DD HH:mm:ms'
     * @return string Formated time.
     */
    public static function timeFormatHelper($time) {
	return preg_replace(
		"/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/",
		"\\3. \\2. \\1, \\4:\\5",
		$time
	);
    }

    public static function timeOnlyHelper($time) {

	return preg_replace(
		"/(\d{4})-0?([1-9]{1,2}0?)-0?([1-9]{1,2}0?) 0?([0-9]{1,2}0?):(\d{2}):(\d{2})/",
		"\\4:\\5:\\6",
		$time
	);
    }

    public static function texyHelper($text) {
	return self::getTexy()->process($text);
    }


    // ---- PRIVATE METHODS

    /** @return Texy */
    private static function getTexy() {
	if (!isset(self::$texy)) {
	    self::$texy = new Texy();
	    self::$texy->encoding = 'utf-8';
	}
	return self::$texy;
    }
}
