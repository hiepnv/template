<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE.'/libraries/joomla/base/object.php';
/**
 * Test class for JObject.
 * Generated by PHPUnit on 2009-09-24 at 17:15:16.
 */
class JObjectTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var	JObject
	 * @access protected
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
		$this->o = new JObject;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown() {
	}


	/**
	 * @todo Implement test__toString().
	 */
	public function test__construct() {
		$this->object = new JObject(array('property1' => 'value1', 'property2' => 5));
		$this->assertThat(
			$this->object->get('property1'),
			$this->equalTo('value1')
		);
	}

	/**
	 * @todo Implement test__toString().
	 */
	public function test__toString() {
		$this->assertEquals("JObject", $this->o->__toString());
	}

	/**
	 * @todo Implement testDef().
	 */
	public function testDef() {
		$this->o->def("check");
		$this->assertEquals(null, $this->o->def("check"));
		$this->o->def("check", "paint");
		$this->o->def("check", "forced");
		$this->assertEquals("paint", $this->o->def("check"));
		$this->assertNotEquals("forced", $this->o->def("check"));
	}

	/**
	 * @todo Implement testGet().
	 */
	public function testGet() {
		$this->assertEquals("onaplane", $this->o->get("foo", "onaplane"));
		$this->assertNotEquals(null, $this->o->get("foo", "onaplane"));
	}

	/**
	 * @todo Implement testGetProperties().
	 */
	public function testGetProperties() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement testGetError().
	 */
	public function testGetError() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement testGetErrors().
	 */
	public function testGetErrors() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement testSet().
	 */
	public function testSet() {
		$this->assertEquals(null, $this->o->set("foo", "imintheair"));
		$this->assertEquals("imintheair", $this->o->set("foo", "nojibberjabber"));
		$this->assertEquals("nojibberjabber", $this->o->foo);
	}

	/**
	 * @todo Implement testSetProperties().
	 */
	public function testSetProperties() {
		$a = array("foo" => "ghost", "knife" => "stewie");
		$this->assertEquals(true, $this->o->setProperties($a));
		$this->assertEquals("ghost", $this->o->foo);
		$this->assertEquals("stewie", $this->o->knife);
	}

	/**
	 * @todo Implement testSetError().
	 */
	public function testSetError() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement testToString().
	 */
	public function testToString() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
?>
