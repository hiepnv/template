<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_modules.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
class JSNISModules extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
}
?>