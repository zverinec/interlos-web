<?php
// TODO annotations
//		- Throw
/**
 * The basic unit test
 *
 * @author Jan Papousek
 */
abstract class EskymoTestCase extends EskymoObject implements IEskymoTest
{

	/**
	 * Test result
	 *
	 * @var IEskymoTestResult
	 */
	private $result;

	/**
	 * It fails a test
	 *
	 * @throws EskymoTestFailure
	 */
	protected function fail($message = NULL) {
		if (empty($message)) {
			$message = "Test failed.";
		}
		throw new EskymoTestFailure($message);
	}

	/**
	 * It returns a test result of the test case
	 *
	 * @return IEskymoTestResult
	 */
	private function getResult() {
		if (empty($this->result)) {
			$this->result = new EskymoTestResult($this);
		}
		return $this->result;
	}

	/**
	 * It runs tests in test case.
	 *
	 * @return IEskymoTestResult
	 */
	public function run() {
		$methods = $this->getMethods();
		foreach($methods AS $method) {
			if (String::startsWith($method, "test")) {
				$this->setUp();
				$this->testMethod($method);
				$this->tearDown();
			}
		}
		return $this->getResult();
	}

	/**
	 * Sets up the fixture, for example, open a network connection.
	 */
	protected function setUp() {}

	/**
	 * Tears down the fixture, for example, close a network connection.
	 */
	protected function tearDown() {}

	/**
	 * It tests a method
	 *
	 * @param string $method
	 * @throws NullPointerException if the $method is empty
	 */
	private function testMethod($method) {
		if (empty($method)) {
			throw new NullPointerException("method");
		}		
		// Start method testing
		$this->getResult()->startMethod($method);
		// Check annotations
		$reflection = $this->reflection->getMethod($method);
		// If it is skipped, save it.
		if ($reflection->hasAnnotation("Skip")) {
			$this->getResult()->endMethod(EskymoTestResult::SKIPPED);
		}
		// If the method tests an expcetion throwing, test it.
		elseif ($reflection->hasAnnotation("TestThrow")) {
			if (count($reflection->getAnnotation("TestThrow")) == 0) {
				throw new NullPointerException("Method '$method' has 'TestThrow' annotation, but the exception class name is undefined.");
			}
			$exceptionClass = ExtraArray::firstValue((array)$reflection->getAnnotation("TestThrow"));
			try {
				call_user_func(array($this, $method));
				$this->fail("Expected '$exceptionClass', but the method '$method' does not throw it.");
			}
			catch(EskymoTestFailure $e) {
				$this->getResult()->addFailure($e);
			}
			catch(Exception $e) {
				if (!($e instanceof $exceptionClass)) {
					$this->getResult()->addFailure( new EskymoTestFailure("Expected '$exceptionClass', but the method '$method' throws '".get_class($e)."'."));
				}
				else {
					$this->getResult()->endMethod();
				}
			}
		}
		// If the method is not skipped execute it.
		else {
			try {
				call_user_func(array($this, $method));
				$this->getResult()->endMethod();
			}
			catch(EskymoTestFailure $e) {
				$this->getResult()->addFailure($e);
			}
			catch(Exception $e) {
				$this->getResult()->addError($e);
			}
		}
	}

	/* ASSERTIONS */
	protected function assertEquals($expected, $actual, $message = NULL) {
		if ($expected != $actual) {
			if (empty($message)) {
				$message = "Expected '$expected', given '$actual'. Test failed.";
			}
			$this->fail($message);
		}
	}

	protected function assertTrue($actual, $message = NULL) {
		$this->assertEquals(TRUE, $actual, $message);
	}

	protected function assertFalse($actual, $message = NULL) {
		$this->assertEquals(FALSE, $actual, $message);
	}

	protected function assertNull($actual, $message = NULL) {
		$this->assertEquals(NULL, $actual, $message);
	}
}
