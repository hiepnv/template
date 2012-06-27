<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_displaymessage.php 11375 2012-02-24 10:19:14Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class JSNISDisplayMessage{

	function JSNISDisplayMessage() {}

	public static function getInstance()
	{
		static $instanceMsg;
		if ($instanceMsg == null){
			$instanceMsg = new JSNISDisplayMessage();
		}
		return $instanceMsg;
	}

	function _sortMessage($keyWord)
	{
		$arrayResult = array();
		$db = JFactory::getDBO();
		if($keyWord != '' && !is_null($keyWord))
		{
			$query 		= 'SELECT *
						   FROM #__imageshow_messages
						   WHERE msg_screen = \''.$keyWord.'\'
						   AND published = 1
						   ORDER BY ordering ASC';
			$db->setQuery($query);
			$result = $db->loadAssocList();
			if(count($result))
			{
				foreach ($result as $value)
				{
					$arrayResult [] = array($value['ordering'], $value['msg_id']);
				}
			}
		}
		return $arrayResult;
	}

	function displayMessage($keyWord)
	{
		$lang 	= JFactory::getLanguage();
		$string = null;
		if($keyWord != '' && !is_null($keyWord))
		{
			$document 	= JFactory::getDocument();
			$jsSlide 	= "window.addEvent('domready', function(){JSNISImageShow.SlideMessage();JSNISImageShow.setDisplayMessage();});";
			$document->addScriptDeclaration($jsSlide);
			$result = $this->_sortMessage($keyWord);
			if(count($result)){
				$index = 0;
				$string .= '<div class="jsn-wrapper-message">';
				foreach ($result as $value)
				{
					$strPrimary 	= 'MESSAGE_'.$keyWord.'_'.$value[0].'_PRIMARY';
					$strSecondary 	= 'MESSAGE_'.$keyWord.'_'.$value[0].'_SECONDARY';
					$string .= '<div class="jsn-more-msg-info-wrapper">';
					$string .= '<div class="jsn-div-msg-toogle clearafter" id="jsn-system-message">';
					$string .= ' <span class="jsn-span-delete-messages"><a class="jsn-link-delete-messages" title="'.htmlspecialchars(JText::_('Close')).'" href="#" onclick="JSNISImageShow.SetStatusMessage(\''.JUtility::getToken().'\', \''.$value[1].'\');"></a></span>';
					$string .= '<p class="jsn-span-title-messages">'.JText::_($strPrimary);
					if($lang->hasKey($strSecondary) == true)
					{
						$string .= ' <strong><a href="#" class="jsn-link-readmore-messages" title="'.htmlspecialchars(JText::_('Read more')).'">[+]</a></strong>';
					}
					$string .= '</p>';
					$string .= '<div class="jsn-more-msg-info">';
					if($lang->hasKey($strSecondary) == true)
					{
						$string .= JText::_($strSecondary);
					}
					$string .= '</div>';
					$string .= '</div>';
					$string .= '</div>';
					$index ++;
				}
				$string .= '</div><div class="clr"></div>';
			}
		}
		return $string;
	}

	function getMessages($screen = '')
	{
		$db 	= JFactory::getDBO();
		$where 	= array();

		if($screen != ''){
			$where[] = 'msg_screen = \''.$screen.'\'';
		}

		$where 	= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		$query 	= 'SELECT * FROM #__imageshow_messages'.$where.' ORDER BY msg_screen, ordering ASC';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function setMessagesStatus($cid, $screen = '')
	{
		$db 		= JFactory::getDBO();
		$where 		= '';
		$whereAll 	= '';

		if($screen != ''){
			$where 		= ' AND msg_screen = \''.$screen.'\'';
			$whereAll 	= ' WHERE msg_screen = \''.$screen.'\'';
		}

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids 	= implode( ',', $cid );
			$query 	= 'UPDATE #__imageshow_messages'
				. ' SET published = 1'
				. ' WHERE msg_id IN ( '.$cids.' )'.$where;
			$db->setQuery( $query );
			$db->query();

			$query = 'UPDATE #__imageshow_messages'
				. ' SET published = 0'
				. ' WHERE msg_id NOT IN ( '.$cids.' )'.$where;
			$db->setQuery( $query );
			$db->query();
		}
		else
		{
			$query = 'UPDATE #__imageshow_messages'
					. ' SET published = 0'.$whereAll;
			$db->setQuery( $query );
			$db->query();
		}
		return true;
	}

	function setSeparateMessage($msgID)
	{
		$db 	= JFactory::getDBO();
		$query 	= 'UPDATE #__imageshow_messages'
				. ' SET published = 0'
				. ' WHERE msg_id = '.$msgID;
		$db->setQuery( $query );
		$db->query();
		return true;
	}

	function refreshMessage()
	{
		$db 					=& JFactory::getDBO();
		$lang 					=& JFactory::getLanguage();
		$currentlang   			= $lang->getTag();
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$langSupport 			= $infoXmlDetail['langs'];
		$registry				= new JRegistry();
		$newStrings				= array();
		$path 					= null;
		$realLang				= null;
		$queries				= array();
		$pathEn					= JLanguage::getLanguagePath( JPATH_BASE, 'en-GB');

		if(array_key_exists($currentlang, $langSupport))
		{
			$path 		= JLanguage::getLanguagePath( JPATH_BASE, $currentlang);
			$realLang	= $currentlang;
		}
		else
		{
			if(!JFolder::exists($pathEn))
			{
				$filepath 		= JPATH_ROOT.DS.'administrator'.DS.'language';
				$foldersLang 	= $this->getFolder($filepath);

				foreach ($foldersLang as $value)
				{
					if(in_array($value, $langSupport) == true)
					{
						$path 		= JLanguage::getLanguagePath( JPATH_BASE, $value);
						$realLang	= $value;
						break;
					}
				}
			}
		}

		if(JFolder::exists($pathEn))
		{
			$filename	= $pathEn.DS.'en-GB'.'.com_imageshow.ini';
		}else{
			$filename	= $path.DS.$realLang.'.com_imageshow.ini';
		}

		$objJNSUtils	= JSNISFactory::getObj('classes.jsn_is_utils');
		$content		= $objJNSUtils->readFileToString( $filename );

		if($content)
		{
			$registry->loadINI($content);
			$newStrings	= $registry->toArray();

			if(count($newStrings))
			{
				if(count($infoXmlDetail['menu']))
				{
					$queries [] = 'TRUNCATE TABLE #__imageshow_messages';

					foreach ($infoXmlDetail['menu'] as $value)
					{
						$index = 1;
						while (isset($newStrings['MESSAGE_'.$value.'_'.$index.'_PRIMARY'])){
							$queries [] = 'INSERT INTO #__imageshow_messages (msg_screen, published, ordering) VALUES (\''.$value.'\', 1, '.$index.')';
							$index ++;
						}
					}
				}
			}

			if(count($queries))
			{
				foreach ($queries as $query)
				{
					$query = trim($query);
					if ($query != '')
					{
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		}
		return true;
	}

	function listScreenDisplayMsg()
	{
		$objectReadxmlDetail 	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
		$infoXmlDetail 			= $objectReadxmlDetail->parserXMLDetails();
		$arrayScreen 			= array();
		$arrayScreen [] = array('value'=>'', 'text'=>'- '.JText::_('Select screen').' -');

		foreach ($infoXmlDetail['menu'] as $value)
		{
			$arrayScreen [] = array('value'=>$value, 'text'=>JText::_($value));
		}
		return $arrayScreen;
	}
}
?>