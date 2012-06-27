<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE.'/libraries/joomla/environment/request.php';
require_once JPATH_BASE.'/libraries/joomla/document/renderer.php';
require_once JPATH_BASE.'/libraries/joomla/document/feed/feed.php';
require_once JPATH_BASE.'/libraries/joomla/document/feed/renderer/rss.php';

/**
 * Test class for JDocumentRendererRSS.
 * Generated by PHPUnit on 2009-10-09 at 13:47:52.
 */
class JDocumentRendererRSSTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var	JDocumentRendererRSS
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
		$_SERVER['REQUEST_METHOD'] = 'get';
		JRequest::setVar('type', 'rss');
		$this->object = new JDocumentFeed;
		$app = JFactory::getApplication('site');
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['SCRIPT_NAME'] = '';
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
	 * testRender method
	 */
	public function testRender() {
		$item = new JFeedItem(array(
			'title'			=> 'Joomla!',
			'link'			=> 'http://www.joomla.org',
			'description'	=> 'Joomla main site',
			'author'		=> 'Joomla',
			'authorEmail'	=> 'joomla@joomla.org',
			'category'		=> 'CMS',
			'comments'		=> 'No comment',
			'guid'			=> 'joomla',
			'date'			=> 'Mon, 20 Jan 03 18:05:41 +0400',
			'source'		=> 'http://www.joomla.org'
		));
		$this->object->addItem($item);
		$this->assertThat(
			// use original 'id' and 'name' here (from XML definition of the form field)
			preg_replace('#\t\t<lastBuildDate>[^<]*</lastBuildDate>\n#','',$this->object->render()),
			$this->equalTo('<?xml version="1.0" encoding="utf-8"?>
<!-- generator="Joomla! 1.7 - Open Source Content Management" -->
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title></title>
		<description></description>
		<link>http://localhost</link>
		<generator>Joomla! 1.7 - Open Source Content Management</generator>
		<atom:link rel="self" type="application/rss+xml" href="http://localhost/index.php?format=feed&amp;type=rss"/>
		<language>en-gb</language>
		<item>
			<title>Joomla!</title>
			<link>http://www.joomla.org</link>
			<guid isPermaLink="false">joomla</guid>
			<description><![CDATA[Joomla main site]]></description>
			<author>joomla@joomla.org (Joomla)</author>
			<category>CMS</category>
			<comments>No comment</comments>
			<pubDate>Mon, 20 Jan 2003 14:05:41 +0000</pubDate>
		</item>
	</channel>
</rss>
'),
			'Line:'.__LINE__.' The feed does not generate properly.'
		);
	}

	/**
	 * @todo Implement test_relToAbs().
	 */
	public function test_relToAbs() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
?>
