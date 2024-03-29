<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument system message renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererMessage extends JDocumentRenderer
{
	/**
	 * Renders the error stack and returns the results as a string
	 *
	 * @param   string  $name     Not used.
	 * @param   array   $params   Associative array of values
	 * @param   string  $content  Not used.
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 */
	public function render($name, $params = array (), $content = null)
	{
		// Initialise variables.
		$buffer = null;
		$lists = null;

		// Get the message queue
		$messages = JFactory::getApplication()->getMessageQueue();

		// Build the sorted message list
		if (is_array($messages) && !empty($messages))
		{
			foreach ($messages as $msg)
			{
				if (isset($msg['type']) && isset($msg['message']))
				{
					$lists[$msg['type']][] = $msg['message'];
				}
			}
		}

		// Build the return string
		$buffer .= "\n<div id=\"system-message-container\">";

		// If messages exist render them
		if (is_array($lists))
		{
			$buffer .= "\n<div id=\"system-message\" class=\"alert alert-" . $msg['type'] . "\">";
			$buffer .= "<a class=\"close\" data-dismiss=\"alert\">×</a>";
			foreach ($lists as $type => $msgs)
			{
				if (count($msgs))
				{
					$buffer .= "\n<h4 class=\"alert-heading\">" . JText::_($type) . "</h4>";
					$buffer .= "\n<div>";
					foreach ($msgs as $msg)
					{
						$buffer .= "\n\t\t<p>" . $msg . "</p>";
					}
					$buffer .= "\n</div>";
				}
			}
			$buffer .= "\n</div>";
		}

		$buffer .= "\n</div>";
		return $buffer;
	}
}
