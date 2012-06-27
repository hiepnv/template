<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_sliders.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
require_once JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'pane.php';
class JSNISSliders extends JPaneSliders
{
	function __construct($params = array())
	{
		parent::__construct($params);
	}

	public function startPanel($text, $id)
	{
		return '<div class="panel">'
			.'<h3 class="pane-toggler title" id="'.$id.'"><a href="javascript:return void(0);"><span>'.$text.'</span></a></h3>'
			.'<div class="pane-slider content">';
	}	
}