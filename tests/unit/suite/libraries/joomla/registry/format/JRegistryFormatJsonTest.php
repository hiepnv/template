<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE.'/libraries/joomla/registry/format/json.php';

/**
 * Test class for JRegistryFormatJSON.
 * Generated by PHPUnit on 2009-10-27 at 15:13:37.
 */
class JRegistryFormatJSONTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JRegistryFormatJSON
	 */
	protected $object;

	/**
	 * Test the JRegistryFormatJSON::objectToString method.
	 */
	public function testObjectToString()
	{
		$class = new JRegistryFormatJSON;
		$options = null;
		$object = new stdClass;
		$object->foo = 'bar';

		// Test basic object to string.
		$string = $class->objectToString($object, $options);
		$this->assertThat(
			$string,
			$this->equalTo('{"foo":"bar"}')
		);
	}

	/**
	 * Test the JRegistryFormatJSON::stringToObject method.
	 */
	public function testStringToObject()
	{
		$class = new JRegistryFormatJSON;

		$string1 = '{"title":"Joomla Framework","author":"Me","params":{"show_title":1,"show_abstract":0,"show_author":1,"categories":[1,2]}}';
		$string2 = "[section]\nfoo=bar";

		$object1 = new stdClass;
		$object1->title = 'Joomla Framework';
		$object1->author = 'Me';
		$object1->params = new stdClass;
		$object1->params->show_title = 1;
		$object1->params->show_abstract = 0;
		$object1->params->show_author = 1;
		$object1->params->categories = array(1,2);

		$object2 = new stdClass;
		$object2->section = new stdClass;
		$object2->section->foo = 'bar';

		$object3 = new stdClass;
		$object3->foo = 'bar';

		// Test basic JSON string to object.
		$object = $class->stringToObject($string1, false);
		$this->assertThat(
			$object,
			$this->equalTo($object1),
			'Line:'.__LINE__.' The complex JSON string should convert into the appropriate object.'
		);

		// Test INI format string without sections.
		$object = $class->stringToObject($string2, false);
		$this->assertThat(
			$object,
			$this->equalTo($object3),
			'Line:'.__LINE__.' The INI string should convert into an object without sections.'
		);

		// Test INI format string with sections.
		$object = $class->stringToObject($string2, true);
		$this->assertThat(
			$object,
			$this->equalTo($object2),
			'Line:'.__LINE__.' The INI string should covert into an object with sections.'
		);

		$this->markTestIncomplete(
			'Need to test for bad input.'
		);
	}
}
