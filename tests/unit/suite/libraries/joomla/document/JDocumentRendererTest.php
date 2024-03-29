<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE.'/libraries/joomla/document/renderer.php';
require_once JPATH_BASE.'/libraries/joomla/document/document.php';

/**
 * Test class for JDocumentRenderer.
 * Generated by PHPUnit on 2009-10-09 at 12:14:39.
 */
class JDocumentRendererTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var	JDocumentRenderer
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
		//$this->object = new JDocumentRenderer;
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
	 * Empty test because the base class does nothing
	 */
	public function testRender() {
		$doc = new JDocument;
		$this->object = new JDocumentRenderer($doc);
		$this->object->render('test');
	}

	/**
	 * @todo Implement testGetContentType().
	 */
	public function testGetContentType() {
		$doc = new JDocument;
		$this->object = new JDocumentRenderer($doc);
		$this->assertThat(
			$this->object->getContentType(),
			$this->equalTo('text/html')
		);
	}
}

