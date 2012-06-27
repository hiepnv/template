<?php
/**
 * @package     Joomla.Platform
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * class for sortable table list
 *
 * @package     Joomla.Platform
 * @subpackage  HTML
 * @since       11.1
 */
abstract class JHtmlDndlist
{
	protected static $loaded = array();
	
	/**
	 * Method to load the Sortable script and make table sortable 
	 *	 * 
	 *
	 * @param   string  $tableId  DOM id of the table
	 * @param   string  $formId   DOM id of the form
	 * @param   string  $saveOrderingUrl save ordering url, ajax-load after an item dropped 
	 * @return  void
	 * 
	 */
	public static function sortable ($tableId, $formId, $saveOrderingUrl)
	{	
		// Only load once
		if (isset(self::$loaded[__METHOD__]['dndlist']))
		{
			return;
		}

		JHtml::_('script', 'system/dndlist.js', false, true);

		// Attach sortable to document
		JFactory::getDocument()->addScriptDeclaration(
			"jQuery(document).ready(function ($){
				var sortableList = new $.JSortableList('#" . $tableId . " tbody','" . $formId . "', '" . $saveOrderingUrl . "');
			});"
		);

		// Set static array
		self::$loaded[__METHOD__]['dndlist'] = true;
		return;
	}
}