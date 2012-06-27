<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_contentclip.php 8474 2011-09-26 03:28:41Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );

class JSNISContentclip
{
	function JSNISContentclip() {}

	public static function getInstance()
	{
		static $instanceContentClipsUtils;
		if ($instanceContentClipsUtils == null)
		{
			$instanceContentClipsUtils = new JSNISContentclip();
		}
		return $instanceContentClipsUtils;
	}

	function calColStyle ($cols)
	{
		$parts = array();
		switch ($cols)
		{
			case 0:
				return null;
			break;
			case 1:
				$parts[0]['class'] 	= "";
				$parts[0]['width'] 	= "100%";

			break;
			case 2:
				$parts[0]['class'] 	= "-left";
				$parts[0]['width'] 	= "49.9%";
				$parts[1]['class'] 	= "-right";
				$parts[1]['width'] 	= "49.9%";

			break;
			default:
				$width1 = round(99.6/($cols-0.1), 2);
				$width2 = round((99.6 - $width1*($cols-2))/2, 2);

				for ($i = 1; $i < $cols - 1; $i++)
				{
					$parts[$i]['class'] 	 = "-center";
					$parts[$i]['width'] 	 = $width1."%";
					$parts[$i]['subwidth'] = "90%";
				}
				$parts[0]['class'] 			= "-left";
				$parts[0]['width'] 			= $width2."%";
				$parts[$cols - 1]['class'] 	= "-right";
				$parts[$cols - 1]['width'] 	= $width2."%";
			break;
		}
		return $parts;
	}
}