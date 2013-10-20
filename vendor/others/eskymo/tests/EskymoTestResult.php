<?php
/**
 * Test result
 *
 * @author papi
 */
class EskymoTestResult implements IEskymoTestResult
{

	/**
	 * @var int
	 */
	const PASSED = 1;
	
	/**
	 * @var int
	 */
	const SKIPPED = 0;

	/**
	 * Listeners
	 *
	 * @var array
	 */
	private $listeners = array();

	/**
	 * Started method
	 *
	 * @var string
	 */
	private $started;

	/**
	 * Is successful?
	 *
	 * @var bool
	 */
	private $successful = TRUE;

	/**
	 * The test which the result is owned by.
	 *
	 * @var IEskymoTest
	 */
	private $test;

	/**
	 * Messages of tests
	 *
	 * @var array
	 */
	private $tested = array();

	/**
	 * It creates a new test result
	 *
	 * @param IEskymoTest
	 * @throws NullPointerException if the $test is empty.
	 */
	public function  __construct($test) {
		if (empty($test)) {
			throw new NullPointerException();
		}
		$this->test = $test;
	}

	/**
	 * It adds a test error into the test result.
	 *
	 * @param Exception $error The created error
	 * @throws NullPointerException if the $error is empty
	 */
	function addError(Exception $error) {
		if (empty($error)) {
			throw new NullPointerException("error");
		}
		$this->successful = FALSE;
		$this->tested[$this->started] = $error;
		unset($this->started);
		foreach ($this->listeners AS $listener) {
			$listener->addError($test, $error);
		}
	}

	/**
	 * It adds a test failure into the test result
	 *
	 * @param EskymoTestFailure $failure The descriptor of the failure
	 * @throws NullPointerException if the $failure is empty
	 */
	function addFailure(EskymoTestFailure $failure) {
		if (empty($failure)) {
			throw new NullPointerException("failure");
		}
		$this->successful = FALSE;
		$this->tested[$this->started] = $failure;
		unset($this->started);
		foreach ($this->listeners AS $listener) {
			$listener->addFailure($test, $failure);
		}
	}

	/**
	 * It adds a listener
	 *
	 * @param EskymoTestListener $listener
	 * @throws NullPointerException if the $listener is empty
	 */
	function addListener(EskymoTestListener $listener) {
		if (empty($listener)) {
			throw new NullPointerException("listener");
		}
		$this->removeListener($listener);
		$this->listeners[] = $listener;
	}

	/**
	 * It add a success into the test result
	 *
	 * @param EskymoTestSucces $success
	 * @throws NullPointerException if the $success is empty
	 */
	function addSuccess(EskymoTestSuccess $success) {
		if (empty($success)) {
			throw new NullPointerException("success");
		}
		$this->tested[$this->started] = $success;
		unset($this->started);
		foreach ($this->listeners AS $listener) {
			$listener->addSuccess($test, $success);
		}
	}

	/**
	 * It finishes method testing
	 *
	 * @throws InvalidStateException if there is no tested method.
	 */
	public function endMethod($code = self::PASSED) {
		if (empty($this->started)) {
			throw new InvalidStateException("There is no tested method.");
		}
		$this->tested[$this->started] = $code;
		unset($this->started);
		foreach ($this->listeners AS $listener) {
			$listener->endMethod($test, $code);
		}
	}

	/**
	 * It returns a list of tested methods
	 *
	 * @return array Method name => result (or code)
	 * @throws InvalidStateException if some method is beeing tested
	 */
	function getTested() {
		if (!empty($this->started)) {
			Debug::dump(array_keys($this->tested));
			throw new InvalidStateException("The method '" . $this->started . "' is being tested.");
		}
		return $this->tested;
	}

	/**
	 * It removes a listener
	 *
	 * @param EskymoTestListener $listener
	 * @throws NullPointerException if the $listener is empty
	 */
	function removeListener(EskymoTestListener $listener) {
		if (empty($listener)) {
			throw new NullPointerException("listener");
		}
		for ($i=0; $i < count($this->listeners); $i++) {
			if ($listener->equals($this->listeners[$i])) {
				unset($this->listeners[$i]);
			}
		}
	}

	/**
	 * It starts a testing method
	 *
	 * @param string $method Method name
	 * @throws NullPointeException if the $method is empty
	 * @throws InvalidArgumentException if the tested class does not have this method
	 */
	function startMethod($method) {
		if (empty($method)) {
			throw new NullPointerException("method");
		}
		if (!in_array($method, $this->test->getMethods())) {
			throw new InvalidArgumentException("There is no method '$method' in test '".get_class($this->test)."'");
		}
		$this->started = $method;
		foreach ($this->listeners AS $listener) {
			$listener->startMethod($test);
		}
	}

	/**
	 * It checks if the result is successful
	 *
	 * @return bool
	 */
	function isSuccessful() {
		return $this->successful;
	}

}
