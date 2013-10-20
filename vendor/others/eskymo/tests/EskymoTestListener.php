<?php
/**
 * The test listener
 *
 * @author Jan Papousek
 */
abstract class EskymoTestListener extends EskymoObject
{

	public function addError(IEskymoTest $test, Exception $e) {}

	public function addFailure(IEskymoTest $test, EskymoTestFailure $e) {}

	public function endMethod(IEskymoTest $test, $code) {}

	public function startMethod(IEskymoTest $test) {}
}

