<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE . '/libraries/joomla/user/helper.php';

/**
 * Test class for JUserHelper.
 * Generated by PHPUnit on 2009-10-26 at 22:44:33.
 */
class JUserHelperTest extends JoomlaDatabaseTestCase
{
	/**
	 * @var JUserHelper
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
		JUserHelperTest::$actualError['code'] = $error->get('code');
		JUserHelperTest::$actualError['msg'] = $error->get('message');
		JUserHelperTest::$actualError['info'] = $error->get('info');
		return false;
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		jimport('joomla.user.user');
		parent::setUp();

		$this->saveFactoryState();
		$this->saveErrorHandlers();
		$this->setErrorCallback('JUserHelperTest');
		JUserHelperTest::$actualError = array();

		$this->object = new JUserHelper;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->setErrorhandlers($this->savedErrorState);
	}

	/**
	 * @todo Implement testAddUserToGroup().
	 */
	public function testAddUserToGroup()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

 /**
    * Test cases for userGroups
    *
    * Each test case provides
    * - integer  userid  a user id
    * - array    group   user group, given as hash
    *                    group_id => group_name,
    *                    empty if undefined
    * - array    error   error info, given as hash
    *                    with indices 'code', 'msg', and
    *                    'info', empty, if no error occured
    * @see ... (link to where the group and error structures are
    *      defined)
    * @return array
    */
   function casesGetUserGroups()
   {
       return array(
           'validSuperUser' => array(
               42,
               array( 'Super Users' => 8 ),
               array(),
           ),
           'unknownUser' => array(
               1000,
               array(),
               array(
                   'code' => 'SOME_ERROR_CODE',
                   'msg'  => 'JLIB_USER_ERROR_UNABLE_TO_LOAD_USER',
                   'info' => ''
               ),
           ),
       );
   }

	/**
	 * TestingGetUserGroups().
	 *
	 * @param	integer	User ID
	 * @param	mixed	User object or empty array if unknown
	 * @param	array	Expected error info
	 *
	 * @return void
	 * @dataProvider casesGetUserGroups
	 */
	public function testGetUserGroups( $userid, $expected, $error )
	{
		$this->assertThat(
			JUserHelper::getUserGroups($userid),
			$this->equalTo($expected)
		);
		$this->assertThat(
			JUserHelperTest::$actualError,
			$this->equalTo($error)
		);
	}

	/**
	 * @todo Implement testRemoveUserFromGroup().
	 */
	public function testRemoveUserFromGroup()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testSetUserGroups().
	 */
	public function testSetUserGroups()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGetProfile().
	 */
	public function testGetProfile()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testActivateUser().
	 */
	public function testActivateUser()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test cases for userId
	 *
	 * @return array
	 */
	function casesGetUserId()
	{
		return array(
			'admin' => array(
				'admin',
				42,
				array(),
			),
			'unknown' => array(
				'unknown',
				null,
				array(),
			),
		);
	}

	/**
	 * TestingGetUserGroups().
	 *
	 * @param	string	User name
	 * @param	int 	Expected user id
	 * @param	array	Expected error info
	 *
	 * @return void
	 * @dataProvider casesGetUserId
	 */
	public function testGetUserId( $username, $expected, $error )
	{
		$expResult = $expected;
		$this->assertThat(
			JUserHelper::getUserId($username),
			$this->equalTo($expResult)
		);
		$this->assertThat(
			JUserHelperTest::$actualError,
			$this->equalTo($error)
		);
	}

	/**
	 * @todo Implement testGetCryptedPassword().
	 */
	public function testGetCryptedPassword()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGetSalt().
	 */
	public function testGetSalt()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * @todo Implement testGenRandomPassword().
	 */
	public function testGenRandomPassword()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}
}
?>
