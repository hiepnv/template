<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE.'/libraries/joomla/html/parameter.php';

class JParameterInspector extends JParameter
{
	public function getElementPath()
	{
		return $this->_elementPath;
	}
}

/**
 * Test class for JParameter.
 * Generated by PHPUnit on 2009-10-27 at 15:38:18.
 */
class JParameterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test the JParameter::addElementPath method.
	 */
	public function testAddElementPath()
	{
		$p = new JParameterInspector('');
		$p->addElementPath(dirname(__FILE__));

		$expected = array(
			// addElementPath appends the slash for some reason.
			dirname(__FILE__) . '/',
			JPATH_LIBRARIES . '/joomla/html/parameter/element'
		);

		$this->assertThat(
			$p->getElementPath(),
			$this->equalTo($expected)
		);
	}

	/**
	 * Test the JParameter::bind method.
	 */
	public function testBind()
	{
		$p = new JParameter('');

		// Check binding an array.
		$p->bind(array(
			'foo1' => 'bar1'
		));
		$this->assertThat(
			$p->get('foo1'),
			$this->equalTo('bar1')
		);

		// Check binding an object.
		$object = new stdClass;
		$object->foo1 = 'bar2';
		$p->bind($object);
		$this->assertThat(
			$p->get('foo1'),
			$this->equalTo('bar2')
		);

		// Check binding a JSON string.
		$p->bind('{"foo1":"bar4"}');
		$this->assertThat(
			$p->get('foo1'),
			$this->equalTo('bar4')
		);

		// Check binding an INI string.
		$p->bind('foo1=bar5');
		$this->assertThat(
			$p->get('foo1'),
			$this->equalTo('bar5')
		);
	}

	/**
	 * Test the JParameter::def method
	 */
	public function testDef()
	{
		$p = new JParameter('');

		$p->set('foo1', 'bar1');

		$this->assertThat(
			$p->def('foo1', 'bar2'),
			$this->equalTo('bar1')
		);

		$this->assertThat(
			$p->def('foo2', 'bar2'),
			$this->equalTo('bar2')
		);
	}

	/**
	 * Test the JParameter::get method.
	 */
	public function testGet()
	{
		$p = new JParameter('{"foo":"bar"}');

		$this->assertThat(
			$p->get('foo'),
			$this->equalTo('bar')
		);

		$this->assertThat(
			$p->get('foo2'),
			$this->equalTo(null)
		);

		$this->assertThat(
			$p->get('foo2', 'bar2'),
			$this->equalTo('bar2')
		);
	}

	/**
	 * Test the JParameter::getGroups method.
	 */
	public function testGetGroups()
	{
		$p = new JParameter('{"foo":"bar"}', dirname(__FILE__).'/jparameter.xml');

		$this->assertThat(
			$p->getGroups(),
			$this->equalTo(
				array(
					'basic' => 1,
					'advanced' => 2,
				)
			)
		);
	}

	/**
	 * Test the JParameter::getNumParams() method.
	 */
	public function testGetNumParams()
	{
		$p = new JParameter('{"foo":"bar"}', dirname(__FILE__).'/jparameter.xml');

		$this->assertThat(
			$p->getNumParams('unknown'),
			$this->isFalse()
		);

		$this->assertThat(
			$p->getNumParams('basic'),
			$this->equalTo(1)
		);

		$this->assertThat(
			$p->getNumParams('advanced'),
			$this->equalTo(2)
		);
	}

	/**
	 * Test the JParameter::getParam method.
	 */
	public function testGetParam()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the JParameter::getParams method.
	 */
	public function testGetParams()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the JParameter::loadElement method.
	 */
	public function testLoadElement()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the JParameter::loadSetupFile method.
	 */
	public function testLoadSetupFile()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the JParameter::render method.
	 */
	public function testRender()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the JParameter::renderToArray method.
	 */
	public function testRenderToArray()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}

	/**
	 * Test the JParameter::set method.
	 */
	public function testSet()
	{
		$p = new JParameter('');

		$this->assertThat(
			$p->set('foo', 'bar'),
			$this->equalTo('bar')
		);

		$this->assertThat(
			$p->get('foo'),
			$this->equalTo('bar')
		);
	}

	/**
	 * Test the JParameter::setXML method.
	 */
	public function testSetXML()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		'This test has not been implemented yet.'
		);
	}
}