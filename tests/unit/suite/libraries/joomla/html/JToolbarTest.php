<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE.'/libraries/joomla/html/toolbar.php';

/**
 * Test class for JToolbar.
 * Generated by PHPUnit on 2009-10-27 at 15:38:36.
 */
class JToolbarTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JToolbar
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new JToolbar;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * @todo Decide how to Implement.
	 */
	public function testDummy() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	public function testAddButtonPathString()
	{
		$initialValue = $this->readAttribute($this->object, '_buttonPath');
		$this->object->addButtonPath('MyTestPath');
		$newValue = $this->readAttribute($this->object, '_buttonPath');
		$this->assertThat(
			$newValue[0],
			$this->equalTo('MyTestPath'.DIRECTORY_SEPARATOR)
		);

		$initialCount = count($initialValue);

		for($i = 0; $i < $initialCount; $i++) {
			$this->assertThat(
				$initialValue[$i],
				$this->equalTo($newValue[$i+1])
			);
		}
	}

	public function testAddButtonPathArray()
	{
		$initialValue = $this->readAttribute($this->object, '_buttonPath');
		$this->object->addButtonPath(array('MyTestPath1', 'MyTestPath2'));
		$newValue = $this->readAttribute($this->object, '_buttonPath');
		$this->assertThat(
			$newValue[0],
			$this->equalTo('MyTestPath2'.DIRECTORY_SEPARATOR)
		);

		$this->assertThat(
			$newValue[1],
			$this->equalTo('MyTestPath1'.DIRECTORY_SEPARATOR)
		);

		$initialCount = count($initialValue);

		for($i = 0; $i < $initialCount; $i++) {
			$this->assertThat(
				$initialValue[$i],
				$this->equalTo($newValue[$i+2])
			);
		}
	}


}
