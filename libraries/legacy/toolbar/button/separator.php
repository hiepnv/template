<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Renders a button separator
 *
 * @package     Joomla.Platform
 * @subpackage  Toolbar
 * @since       11.1
 */
class JToolbarButtonSeparator extends JButton
{
	/**
	 * Button type
	 *
	 * @var   string
	 */
	protected $_name = 'Separator';

	/**
	 * Get the HTML for a separator in the toolbar
	 *
	 * @param   array  &$definition  Class name and custom width
	 *
	 * @return  string  The HTML for the separator
	 *
	 * @see     JButton::render()
	 * @since   12.1
	 */
	public function render(&$definition)
	{
		// Initialise variables.
		$class = null;
		$style = null;

		// Separator class name
		$class = (empty($definition[1])) ? 'btn-group' : 'btn-group ' . $definition[1];

		// Custom width
		$style = (empty($definition[2])) ? null : ' style="width:' . intval($definition[2]) . 'px;"';

		return '<div class="' . $class . '"' . $style . ">\n</div>\n";
	}

	/**
	 * Empty implementation (not required for separator)
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function fetchButton()
	{
	}
}
