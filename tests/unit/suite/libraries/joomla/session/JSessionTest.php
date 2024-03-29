<?php
require_once 'PHPUnit/Framework.php';


/**
 * Test class for JSession.
 * Generated by PHPUnit on 2009-10-26 at 22:57:34.
 */
class JSessionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JSession
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		include_once JPATH_BASE . '/libraries/joomla/session/session.php';
		include_once JPATH_BASE . '/libraries/joomla/application/application.php';

		$this->object = JSession::getInstance('none', array('expire' => 20, 'force_ssl' => true, 'name' => 'name', 'id' => 'id', 'security' => 'security'));
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test cases for getInstance
	 * string	handler of type JSessionStorage: none or database
	 * array	arguments for $options in form of associative array
	 * string	message if test case fails
	 *
	 * @return array
	 */

	function casesGetInstance()
	{
		return array(
			'first_instance' => array(
				'none',
				array('expire' => 99),
				'Line: '.__LINE__.': '.'Should not be a different instance '
			),
			'second_instance' => array(
				'database',
				array('expire' => 15),
				'Line: '.__LINE__.': '.'Should not be a different instance '
			)
		);
	}
	/**
	 * @todo Implement testGetInstance().
	 * @dataProvider casesGetInstance
	 */
	public function testGetInstance($store, $options)
	{
		$oldSession = $this->object;
		$newSession = JSession::getInstance($store, $options);
		$this->assertThat(
			$oldSession,
			$this->identicalTo($newSession)
		);
	}

	public function testGetState()
	{
		$this->assertEquals('active', $this->object->getState(), 'Session should be active');
	}


	public function testGetExpire()
	{
		$this->assertEquals(20, $this->object->getExpire(), 'Session expire should be 20');
	}


	public function testGetToken()
	{
		$session = $this->object;
		$session->set('session.token', 'abc');
		$this->assertEquals('abc', $session->getToken(), 'Token should be abc');

		$session->set('session.token', null);
		$token = $session->getToken();
		$this->assertEquals(32, strlen($token), 'Line: '.__LINE__.' Token should be length 32');

		$token2 = $session->getToken(true);
		$this->assertNotEquals($token, $token2, 'Line: '.__LINE__.' New token should be different');
	}

	public function testHasToken()
	{
		$session = $this->object;
		$token = $session->getToken();
		$this->assertTrue($session->hasToken($token), 'Line: '.__LINE__.' Correct token should be true');

		$this->assertFalse($session->hasToken('abc', false), 'Line: '.__LINE__.' Should return false with wrong token');
		$this->assertEquals('active', $session->getState(), 'Line: '.__LINE__.' State should not be set to expired');

		$this->assertFalse($session->hasToken('abc'), 'Line: '.__LINE__.' Should return false with wrong token');
		$this->assertEquals('expired', $session->getState(), 'Line: '.__LINE__.' State should be set to expired by default');

	}

	public function testGetFormToken() {
		$user = JFactory::getUser();
		$expected = JApplication::getHash($user->get('id', 0) . JFactory::getSession()->getToken(false));
		$this->assertEquals($expected, $this->object->getFormToken(), 'Form token should be calculated as above');
	}

	public function testGetName()
	{
		$session = $this->object;
		$this->assertEquals(session_name(), $session->getName(), 'Line: '.__LINE__.' Session name should be set');

		// Destroy and try again
		$session->destroy();
		$this->assertEquals(null, $session->getName(), 'Line: '.__LINE__.' Session name should be null');
		$session->restart();
	}

	public function testGetId()
	{
		$session = $this->object;
		$this->assertEquals(session_id(), $session->getId(), 'Line: '.__LINE__.' Session id should be set');

		// Destroy and try again
		$session->destroy();
		$this->assertEquals(null, $session->getId(), 'Line: '.__LINE__.' Session id should be null');
		$session->restart();

	}

	/**
	 * @todo Implement testGetStores().
	 */
	public function testGetStores()
	{
		$expected = array('database', 'none');
		$return = JSession::getStores();
		$this->assertEquals(
			$expected[0],
			$return[0],
			'Line: '.__LINE__.' database and none are available'
		);
		$this->assertEquals(
			$expected[1],
			$return[1],
			'Line: '.__LINE__.' database and none are available'
		);
	}

	public function testIsNew()
	{
		$session = $this->object;
		$session->restart();
		$this->assertTrue($session->isNew(), 'Line: '.__LINE__.' restarted session should be new');
		$session->set('session.counter', 2);
		$this->assertFalse($session->isNew(), 'Line: '.__LINE__.' session should not be new');

	}

	public function testGet()
	{
		$session = $this->object;
		$expected = $_SESSION['__default']['session.counter'];
		$this->assertEquals($expected, $session->get('session.counter'), 'Line: '.__LINE__.' values should match for active session');
		$session->destroy();
		$this->assertEquals(null, $session->get('session.counter'), 'Line: '.__LINE__.' Always return null for destroyed session');
		$session->restart();
	}

	public function testSet()
	{
		$session = $this->object;
		$session->clear('my.property', 'my_namespace');
		$this->assertNull($session->set('my.property', 'my_value', 'my_namespace'), 'Line: '.__LINE__.' Old value should be null');
		$this->assertEquals('my_value', $session->get('my.property', null, 'my_namespace'), 'Line: '.__LINE__.' New value should be set');
		$session->destroy();
		$this->assertNull($session->set('my.property', 'my_new_value', 'my_namespace'), 'Line: '.__LINE__.' Destroyed session set should return null');
		$this->assertFalse(isset($_SESSION['__my_namespace']['my.property']), 'Line: '.__LINE__.' Destroyed session set should not write to $_SESSION');
		$session->restart();
	}

	public function testHas()
	{
		$session = $this->object;
		$session->set('my.property', 'my_value', 'my_namespace');
		$this->assertTrue($session->has('my.property', 'my_namespace'), 'Line: '.__LINE__.' Property should exist');
		$session->destroy();
		$this->assertEquals(null, $session->has('my.property', 'my_namespace'), 'Line: '.__LINE__.' Property should not exist for destroyed session');
		$session->restart();
	}

	public function testClear()
	{
		$session = $this->object;
		$session->destroy();
		$this->assertNull($session->clear('my.property', 'my_namespace'), 'Line: '.__LINE__.' Always return null for non-active session');
		$session->restart();
		// Set a property
		$session->set('my.property', 'my_testclear_value', 'my_namespace');
		// Make sure it is set correctly
		$this->assertEquals('my_testclear_value', $session->get('my.property', null, 'my_namespace'));
		// Clear and test result
		$this->assertEquals('my_testclear_value', $session->clear('my.property', 'my_namespace'), 'Line: '.__LINE__.' Old value should be returned');
		$this->assertNull($session->get('my.property', null, 'my_namespace'), 'Line: '.__LINE__.' Property should now be null after clear');
	}

	public function testDestroy()
	{
		$session = $this->object;
		// Set up cookie for test
		$_COOKIE[session_name()]= 'test cookie';

		$this->assertEquals('active', $session->getState(), 'Line: '.__LINE__.' Starting state is active');
		$this->assertTrue(count($_SESSION) > 0, 'Line: '.__LINE__.' $_SESSION has content');
		$this->assertTrue($session->destroy());
		$this->assertEquals('destroyed', $session->getState(), 'Line: '.__LINE__.' State is now destroyed');
		$this->assertTrue(count($_SESSION) == 0, 'Line: '.__LINE__.' $_SESSION has no content');
		$session->restart();
	}

	public function testRestart()
	{
		// Test starting state
		$session = $this->object;
		$this->assertEquals('active', $session->getState(), 'Line: '.__LINE__.' Starting state should be active');
		$oldId = $session->getId();
		// Restart and test
		$this->assertTrue($session->restart(), 'Line: '.__LINE__.' Restart should succeed');
		$this->assertTrue($oldId != $session->getId(), 'Line: '.__LINE__.' Restart should change id');
		$this->assertEquals(1, $session->get('session.counter'), 'Line: '.__LINE__.' Counter should be reset');

	}

	public function testFork()
	{
		$session = $this->object;
		$session->set('my.property', 'my_testfork_value', 'my_namespace');
		$oldId = $session->getId();
		$this->assertTrue($session->fork(), 'Line: '.__LINE__.' fork() should succeed');
		$this->assertNotEquals($oldId, $session->getId(), 'Line: '.__LINE__.' id should have changed');
		$this->assertNotEquals('my_testfork_value', $session->get('my.property', null, 'my_namespace'), 'Line: '.__LINE__.' Property should be preserved');

		// Test with destroyed session
		$session->destroy();
		$this->assertFalse($session->fork(), 'Line: '.__LINE__.' fork() should fail for destroyed session');
		$session->restart();
	}

	public function testClose()
	{
		$session = $this->object;
		$this->assertNull($session->close());
	}

}
?>
