<?php
/**
 * @author Jan Papousek
 */
interface IEskymoTestResult
{

	/**
	 * It adds a test error into the test result.
	 *
	 * @param Exception $error The created error
	 * @throws NullPointerException if the $error is empty
	 */
	function addError(Exception $error);

	/**
	 * It adds a test failure into the test result
	 *
	 * @param EskymoTestFailure $failure The descriptor of the failure
	 * @throws NullPointerException if the $failure is empty
	 */
	function addFailure(EskymoTestFailure $failure);

	/**
	 * It adds a listener
	 *
	 * @param EskymoTestListener $listener
	 * @throws NullPointerException if the $listener is empty
	 */
	function addListener(EskymoTestListener $listener);

	/**
	 * It add a success into the test result
	 *
	 * @param EskymoTestSucces $success
	 * @throws NullPointerException if the $success is empty
	 */
	function addSuccess(EskymoTestSuccess $success);

	/**
	 * It returns a list of tested methods
	 *
	 * @return array
	 */
	function getTested();

	/**
	 * It removes a listener
	 *
	 * @param EskymoTestListener $listener
	 * @throws NullPointerException if the $listener is empty
	 */
	function removeListener(EskymoTestListener $listener);

	/**
	 * It checks if the result is successful
	 *
	 * @return bool
	 */
	function isSuccessful();

}
