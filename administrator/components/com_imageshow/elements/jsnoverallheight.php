<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsnoverallheight.php 6505 2011-05-31 11:01:31Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldJSNOverAllHeight extends JFormField
{
	public $type = 'JSNOverAllHeight';
	protected function getInput()
	{
		$html       = '';
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : 'class="jsn-text"';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$postfix	= (isset($this->element['postfix'])) ? '<span class="jsn-postfix">'.$this->element['postfix'].'</span>' : '';
		$value      = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		$html = '<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
				' value="'.($value != ''? (int) $value:'').'" onfocus="getInputValue(this);" onchange="checkNumberValue(this);"' .
				$class.$size.$disabled.$readonly.$maxLength.'/> '.$postfix;
		return $html;
	}
}