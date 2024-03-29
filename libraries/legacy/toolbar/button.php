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
 * Button base class
 *
 * The JButton is the base class for all JButton types
 *
 * @package     Joomla.Platform
 * @subpackage  Toolbar
 * @since       12.1
 */
abstract class JToolbarButton
{
	/**
	 * element name
	 *
	 * This has to be set in the final renderer classes.
	 *
	 * @var    string
	 */
	protected $_name = null;

	/**
	 * reference to the object that instantiated the element
	 *
	 * @var    JButton
	 */
	protected $_parent = null;

	/**
	 * Constructor
	 *
	 * @param   object  $parent  The parent
	 */
	public function __construct($parent = null)
	{
		$this->_parent = $parent;
	}

	/**
	 * Get the element name
	 *
	 * @return  string   type of the parameter
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Get the HTML to render the button
	 *
	 * @param   array  &$definition  Parameters to be passed
	 *
	 * @return  string
	 */
	public function render(&$definition)
	{
		/*
		 * Initialise some variables
		 */
		$html = null;
		$id = call_user_func_array(array(&$this, 'fetchId'), $definition);
		$action = call_user_func_array(array(&$this, 'fetchButton'), $definition);

		// Build id attribute
		if ($id)
		{
			$id = "id=\"$id\"";
		}

		// Build the HTML Button
		$html .= "<div class=\"btn-group\" $id>\n";
		$html .= $action;
		$html .= "</div>\n";

		return $html;
	}

	/**
	 * Method to get the CSS class name for an icon identifier
	 *
	 * Can be redefined in the final class
	 *
	 * @param   string  $identifier  Icon identification string
	 *
	 * @return  string  CSS class name
	 *
	 * @since   11.1
	 */
	public function fetchIconClass($identifier)
	{
		return "icon-$identifier";
	}

	/**
	 * Get the button
	 *
	 * Defined in the final button class
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	abstract public function fetchButton();
}

/**
 * Deprecated class placeholder. You should use JToolbarButton instead.
 *
 * @package     Joomla.Platform
 * @subpackage  Toolbar
 * @since       11.1
 * @deprecated  12.3
 */
abstract class JButton extends JToolbarButton
{
	/**
	 * Constructor
	 *
	 * @param   object  $parent  The parent
	 */
	public function __construct($parent = null)
	{
		parent::__construct($parent);
	}
}
