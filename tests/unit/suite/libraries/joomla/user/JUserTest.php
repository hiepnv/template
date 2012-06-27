<?php
/**
 * JUserTest.php -- unit testing file for JUser
 *
 * @package	Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once JPATH_BASE.'/tests/unit/JoomlaDatabaseTestCase.php';
/**
 * Test class for JUser.
 * Generated by PHPUnit on 2009-10-26 at 22:44:07.
 *
 * @package	Joomla.UnitTest
 * @subpackage User
 *
 */
class JUserTest extends JoomlaDatabaseTestCase
{
	/**
	 * @var JUser
	 */
	protected $object;
	/**
	 * Receives the callback from JError and logs the required error information for the test.
	 *
	 * @param	JException	The JException object from JError
	 *
	 * @return	bool	To not continue with JError processing
	 */
	static function errorCallback( $error )
	{
		JUserTest::$actualError['code'] = $error->get('code');
		JUserTest::$actualError['msg'] = $error->get('message');
		JUserTest::$actualError['info'] = $error->get('info');
		return false;
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		include_once JPATH_BASE . '/libraries/joomla/database/table.php';
		include_once JPATH_BASE . '/libraries/joomla/user/user.php';

		parent::setUp();
		parent::setUpBeforeClass();

		$this->saveFactoryState();
		$this->saveErrorHandlers();
		$this->setErrorCallback('JUserTest');
		JUserTest::$actualError = array();

		JUser::getTable('user', 'JTable');
		$this->object = new JUser(42);
		$params = new JRegistry;
		$this->object->setParameters($params);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		$this->setErrorhandlers($this->savedErrorState);
		$this->restoreFactoryState();
	}

	/**
	 * Test cases for getInstance
	 *
	 * @return array
	 */
	function casesGetInstance()
	{
		return array(
			'42' => array(
				42,
				null,
				array()
			),
			'admin' => array(
				'admin',
				null,
				array(),
			),
			'nobody' => array(
				'nobody',
				false,
				array(
					'code' => 'SOME_ERROR_CODE',
					'msg' => 'JLIB_USER_ERROR_ID_NOT_EXISTS',
					'info' => ''
				),
			),
		);
	}
	/**
	 * TestingGetInstance().
	 *
	 * @param	mixed	User ID or name
	 * @param	mixed	User object or false if unknown
	 * @param	array	Expected error info
	 *
	 * @return void
	 * @dataProvider casesGetInstance
	 */
	public function testGetInstance( $userid, $expected, $error )
	{
		$expResult = (is_null($expected))? $this->object : $expected;
		$user = JUser::getInstance($userid);
		$this->assertThat(
			$user,
			$this->equalTo($expResult)
		);
		$this->assertThat(
			JUserTest::$actualError,
			$this->equalTo($error)
		);
	}

	/**
	 * Testing individual parameter control.
	 *
	 * @return void
	 */
	public function testParameters()
	{
		$this->assertThat(
			$this->object->getParam('holy', 'fred'),
			$this->equalTo('fred')
		);

		$this->object->defParam('holy', 'batman');
		$this->assertThat(
			$this->object->getParam('holy', 'fred'),
			$this->equalTo('batman')
		);

		$this->object->setParam('holy', 'batman');
		$this->assertThat(
			$this->object->getParam('holy', 'fred'),
			$this->equalTo('batman')
		);
	}

	/**
	 * Test cases for authorizations
	 *
	 * @return array
	 */
	function casesAuthorise()
	{
		return array(
			'Publisher Create' => array(
				43,
				'core.create',
				'com_content',
				true,
			),
			'null asset Super Admin' => array(
				42,
				'core.create',
				null,
				true,
			),
			'fictional action Super Admin' => array(
				42,
				'nuke',
				'root.1',
				true,
			),
			'core.admin Other user' => array(
				43,
				'core.admin',
				'root.1',
				false,
			),
			'core.admin Super Admin' => array(
				42,
				'core.admin',
				'root.1',
				true,
			),
			'core.admin emergency root_user' => array(
				99,
				'core.admin',
				'root.1',
				true,
			),
		);
	}
	/**
	 * Testing authorize().
	 *
	 * @param	int		User id of user to test
	 * @param	string	Action to get aithorized for this user
	 * @param	string	Asset to get authorization for
	 * @param	bool	Expected return from the authorization check
	 *
	 * @return void
	 */
	public function testAuthorize( )
	{
		// Set up user 99 to be root_user from configuration
		$testConfig = $this->getMock('JConfig', array('get'));
		$testConfig->expects(
			$this->any())
			->method('get')
			->will($this->returnValue('test_root_user'));
		JFactory::$config = $testConfig;
		$user = new JUser(99);
		$user->username = 'test_root_user';
		$this->assertThat(
			$user->authorize('core.admin', 'root.1'),
			$this->equalTo(true),
			"Emergency root user should have core.admin for root.1"
		);

	}

	/**
	 * Testing authorise().
	 *
	 * @param	int		User id of user to test
	 * @param	string	Action to get aithorized for this user
	 * @param	string	Asset to get authorization for
	 * @param	bool	Expected return from the authorization check
	 *
	 * @return void
	 * @dataProvider casesAuthorise
	 */
	public function testAuthorise( $userId, $action, $asset, $expected )
	{
		// Set up user 99 to be root_user from configuration
		$testConfig = $this->getMock('JConfig', array('get'));
		$testConfig->expects(
			$this->any())
			->method('get')
			->will($this->returnValue(99));
		JFactory::$config = $testConfig;

		// Run through test cases
		$user = new JUser($userId);
		$this->assertThat(
			$user->authorise($action, $asset),
			$this->equalTo($expected),
			'Line: '. __LINE__ . ' Failed for user $user'
		);

	}

	/**
	 * Test cases for authorisedViewLevels
	 *
	 * @return array
	 */
	function casesAuthorisedViewLevels()
	{
		return array(
			'Normal' => array(
				0,
				array( 1, 3 ),
			),
			'User1' => array(
				1,
				array( 1 ),
			),
			'User100' => array(
				100,
				array( 1 ),
			),
		);
	}

	/**
	 * Testing authorisedViewLevels().
	 *
	 * @param	Integer	User ID
	 * @param	array	Authorized levels of use
	 *
	 * @return void
	 * @dataProvider	casesAuthorisedViewLevels
	 */
	public function testAuthorisedViewLevels( $user, $expected )
	{
		if ($user )
		{
			$user = new JUser($user);
		}
		else
		{
			$user = $this->object;
		}

		$this->assertThat(
			$user->getAuthorisedViewLevels(),
			$this->equalTo($expected),
			"Failed for user {$user->id}"
		);
	}

	public function testAuthorisedLevels()
	{
		$user = new JUser(0);
		$this->assertThat(
			$user->authorisedLevels(),
			$this->equalTo(array(1)),
			'Line: ' . __LINE__ . ' User 0 should have level 1 only'
			);
	}

	/**
	 * Testing setLastVisit().
	 *
	 * @return void
	 */
	public function testSetLastVisit()
	{
		$timestamp = '2006-12-23 12:12:14';

		$this->object->setLastVisit($timestamp);
		$testUser = new JUser(42);
		$this->assertThat(
			$testUser->lastvisitDate,
			$this->equalTo($timestamp)
		);
	}

	/**
	 * Testing getParameters
	 *
	 * @return void
	 * @todo Implement testGetParameters().
	 */
	public function testGetParam()
	{
		$user = $this->object;
		$user->setParam('testkey', 'testvalue');
		$this->assertEquals('testvalue', $user->getParam('testkey', 'default'),
			'Line: ' . __LINE__ . ' Param value should be correct');
	}

	/**
	 * Testing getParameters
	 *
	 * @return void
	 * @todo Implement testSetParameters().
	 */
	public function testGetParameters()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Testing setParameters
	 *
	 * @return void
	 * @todo Implement testSetParameters().
	 */
	public function testSetParameters()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test cases for gettable
	 *
	 * @return array
	 */
	function casesGetTable()
	{
		return array(
			'Wierd' => array(
				'fred',
				'JTable',
				false,
				'#__users',
			),
			'simple' => array(
				null,
				null,
				'JTableUser',
				'#__users',
			),
			'unknown' => array(
				null,
				'PTable',
				'JTableUser',
				'#__users',
			),
			'reset' => array(
				'user',
				'JTable',
				'JTableUser',
				'#__users',
			),
		);
	}

	/**
	 * Testing getTable().
	 *
	 * @param	string	The type of table
	 * @param	string	The prefix for the table
	 * @param	string	The expected class of the table
	 * @param	string	The expected name of the table
	 *
	 * @return void
	 *
	 * @dataProvider casesGetTable
	 */
	public function testGetTable( $type, $prefix, $expClass, $expName )
	{
		$table = $this->object->getTable($type, $prefix);

		$this->assertThat(
			$table,
			$expClass?$this->isInstanceOf($expClass):$this->isFalse(),
			'Table is not instance of JTableUser'
		);
		if ($expClass)
		{
			$this->assertThat(
				$table->getTableName(),
				$this->equalTo($expName),
				'Failed table name check'
			);
		}
	}

	/**
	 *	Testing bind()
	 *
	 * @return void
	 * @todo Implement testBind().
	 */
	public function testBind()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 *	Testing save() for the case where check() returns false
	 *
	 * @return void
	 */
	public function testSaveCheckIsFalse()
	{
		// This is the error message that check is going to return.
		$myErrorMessage = 'This is the error that bind returns';

		// we need two mock objects - one to mock the other methods of JUser, and one to serve as a JTable mock
		// the false in the $tableMock means that our constructor doesn't get called
		$testObject = $this->getMock('JUser', array('getTable', 'getProperties', 'setError'));
		$tableMock = $this->getMock('JTableUser', array('bind', 'check', 'getError'), array(), '', false);

		// we expect getTable to be called once.  We are going to return our mock table object.
		$testObject->expects($this->once())
					->method('getTable')
					->will($this->returnValue($tableMock));

		// we expect getProperties to be called once.  We are going to return some random user data
		// this data should get passed to the bind method.
		$testObject->expects($this->once())
					->method('getProperties')
					->will($this->returnValue(array('id' => 5, 'username' => 'jimbo')));

		// We expect setError to be called with the error message that check returned.
		$testObject->expects($this->once())
					->method('setError')
					->with($this->equalTo($myErrorMessage));

		// We expect bind to be called with the data that was returned from getProperties
		$tableMock->expects($this->once())
					->method('bind')
					->with($this->equalTo(array('id' => 5, 'username' => 'jimbo')));

		// We expect check to be called.  We will return false.
		$tableMock->expects($this->once())
					->method('check')
					->will($this->returnValue(false));

		// If check behaves properly, it will have set the error message in the table object.  So we expect getError to be called on
		// the table object and it should return the error message.
		$tableMock->expects($this->once())
					->method('getError')
					->will($this->returnValue($myErrorMessage));

		// Now when we call our actual save() method, it will return false
		$this->assertThat(
			$testObject->save(),
			$this->equalTo(false),
			'JUser::save() did not return false when JTable::check returned failed'
		);
	}

	/**
	 *	Testing save() for the case where updateOnly is true and it is a new user
	 *
	 * @return void
	 */
	public function testSaveNoCreateNewUser()
	{
		// here we inject a mock user object into a mock session object so that when JFactory::getUser gets called
		// we already have an object in place and we don't get complaints about not being able to send cookies
		$sessionMock = $this->getMock('JSession', array('get'), array(), '', false);
		$userMock = $this->getMock('JUser', array(), array(), '', false);

		$sessionMock->expects($this->any())
					->method('get')
					->with($this->equalTo('user'))
					->will($this->returnValue($userMock));

		JFactory::$session = $sessionMock;

		// we need two mock objects - one to mock the other methods of JUser, and one to serve as a JTable mock
		// the false in the $tableMock means that our constructor doesn't get called
		// We don't care too much about these methods, because all we're primarily concerned about is that
		// it doesn't try to create a new user
		$testObject = $this->getMock('JUser', array('getTable', 'getProperties'));
		$tableMock = $this->getMock('JTableUser', array('bind', 'check', 'store'), array(), '', false);

		// we expect getTable to be called once.  We are going to return our mock table object.
		$testObject->expects($this->any())
					->method('getTable')
					->will($this->returnValue($tableMock));

		$testObject->id = null;

		$tableMock->expects($this->never())
				->method('store');

		$tableMock->expects($this->any())
				->method('check')
				->will($this->returnValue(true));

		// Now when we call our actual save() method, it will return false
		$this->assertThat(
			$testObject->save(true),
			$this->equalTo(true),
			'JUser::save() did not get stopped when trying to save a new user when it was not supposed to'
		);

		JFactory::$session->destroy();

	}

	/**
	 * Testing creation and deletion of users
	 *
	 * @return void
	 */
	public function testCreateDeleteUser()
	{
		include_once JPATH_BASE . '/libraries/joomla/event/dispatcher.php';
		include_once JPATH_BASE . '/libraries/joomla/plugin/helper.php';
		include_once JPATH_BASE . '/libraries/joomla/application/application.php';

		//JFactory::getApplication('site');
		$mockSession = $this->getMock('JSession', array('_start', 'get'));
		$mockSession->expects($this->any())
			->method('get')
			->will($this->returnValue($this->object)
		);
		JFactory::$session = $mockSession;

		$testUser = new JUser();
		$testUser->name = "Floyd Smoot";
		$testUser->username = "Floyd";

		$this->assertThat(
			$testUser->id,
			$this->equalTo(0),
			"Newly created id should be zero"
		);

		$this->assertThat(
			$testUser->save(),
			$this->isFalse(),
			'Cannot save without valid email'
		);

		$this->assertThat(
			$testUser->getErrors(),
			$this->equalTo(
				array('JLIB_DATABASE_ERROR_VALID_MAIL')
			),
			'Should have caused valid email error'
		);

		$testUser->email = "harry@sally.com";
		//TODO: Fix this assertion
		$this->assertThat(
			$testUser->save(true),
			// Should be false
			$this->isTrue(),
			'Line: ' . __LINE__ . ' Should not create new user when update only flag is set'
		);

		//TODO: Fix this assertion
		$this->assertThat(
			$testUser->save(),
			// Should be true
			$this->isFalse(),
			'Line: ' . __LINE__ . ' Should save the user successfully'
		);

		$this->assertThat(
			$testUser->id,
			$this->greaterThan(0),
			'Line: ' . __LINE__ . " Newly saved id should not be zero"
		);

		$testUser->email = "sally@harry.com";
		//TODO: Fix this assertion
		$this->assertThat(
			$testUser->save(),
			// Should be true
			$this->isFalse(),
			'Line: ' . __LINE__ . ' Should update existing user.'
		);

		$testUser1 = JUser::getInstance('Floyd');
		$this->assertThat(
			$testUser1->id,
			$this->equalTo($testUser1->id),
			'Line: ' . __LINE__ . " Id's should be the same"
		);

		$this->assertThat(
			$testUser->delete(),
			$this->isTrue(),
			'Line: ' . __LINE__ . ' Delete should succeed'
		);

		$testUser2 = JUser::getInstance('Floyd');
		$this->assertThat(
			$testUser2,
			$this->isFalse(),
			'Line: ' . __LINE__ . " Id should not be found"
		);

	}

	/**
	 * Test cases for load
	 *
	 * @return array
	 */
	function casesLoad()
	{
		return array(
			'non-existant' => array(
				1120,
				false,
			),
			'existing' => array(
				42,
				true,
			),
		);
	}

	/**
	 * Testing load().
	 *
	 * @param	integer	User ID to load
	 * @param	bool	Expected result of load operation
	 *
	 * @return void
	 *
	 * @dataProvider casesLoad
	 */
	public function testLoad( $id, $expected )
	{
		$testUser = new JUser($id);

		$this->assertThat(
			$testUser->load($id),
			$this->equalTo($expected)
		);
	}
}

