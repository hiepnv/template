<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: subinstall.php 11682 2012-03-13 04:18:26Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'defines.imageshow.php');
class JSNSubInstaller extends JObject
{
    private $_source 		= null;
    private $_db 			= null;
    private $_app 			= null;
    private $_subinstall 	= null;
	private $_error 		= array();
	private $_lang 			= array();

    public function __construct()
    {
        $parent 			= JInstaller::getInstance();
        $manifest 			= $parent->getManifest();
        $this->_db 			= $parent->getDbo();
        $this->_source 		= $parent->getPath('source');
        $this->_subinstall 	= $manifest->subinstall;
        $this->_app 		= JFactory::getApplication();
        $this->_lang 		= (array) json_decode(JSN_LIST_LANGUAGE_SUPPORTED);
    }

	public function setError($value)
	{
		$this->_error[] = $value;
	}

	public function getError()
	{
		return $this->_error;
	}

    private function _msg($msg, $type = 'message')
    {
        if (!empty($msg))
		{
            $this->_app->enqueueMessage($msg, $type);
        }
    }

    private function _getExtID($element)
	{
		if (!is_object($element))
		{
            return false;
        }
        $query = '';
        switch ($element->type)
		{
			case 'module':
				$name = $element->name;
				if (strncmp($name, 'mod_', 4) != 0)
				{
					$name = 'mod_' . $name;
				}
				$query = 'SELECT extension_id FROM #__extensions WHERE type = \'module\' AND element = \''.$name.'\' AND client_id = '.$element->client; //.' GROUP BY folder, element';
				break;
			case 'plugin':
				$query = 'SELECT extension_id FROM #__extensions WHERE type = \'plugin\' AND element = \''.$element->name.'\' AND client_id = '.$element->client.' AND folder = \''.$element->folder.'\''; //GROUP BY folder, element';
				break;
			case 'component':
				$name = $element->name;
				if (strncmp($name, 'com_', 4) != 0)
				{
					$name = 'com_' . $name;
				}
				 $query = 'SELECT extension_id FROM #__extensions WHERE type = \'component\' AND element = \''.$element->name.'\' AND client_id = '.$element->client;
				break;
		}

        if ($query != '')
		{
            $this->_db->setQuery($query);
            if (!$this->_db->query())
			{
                return false;
            }
            return $this->_db->loadResult();
        }
        return false;
    }

    private function _enable($id, $name = '', $type = 'plugin')
	{
        $query = '';
        switch ($type)
		{
			case 'plugin':
				$query = 'UPDATE #__extensions SET enabled = 1 WHERE extension_id = '.$id;
            break;
         	case 'module':
            	$query = 'UPDATE #__modules SET published = 1, ordering = 99 WHERE module = \''.$name.'\'';
				$this->_setPageForModule($name, 0);
            break;
			default:
			break;
        }

        if ($query != '')
		{
            $this->_db->setQuery($query);
            if (!$this->_db->query())
			{
                return false;
            }
        }
        return true;
    }

    private function _setposition($id, $name, $position, $type = 'module')
    {
        $query = '';
        switch ($type)
        {
        	case 'module':
            	$query = 'UPDATE #__modules SET position = \''.$position.'\' WHERE module = \''.$name.'\'';
            break;
 			default:
			break;
        }

        if ($query != '')
        {
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                return false;
            }
        }
        return true;
    }

    private function _setprotect($id, $type = 'plugin', $lock)
	{
        $query = '';
        switch ($type)
		{
			case 'module':
			case 'plugin':
			case 'component':
				$query = 'UPDATE #__extensions SET protected = '.$lock.' WHERE extension_id = '.$id;
			break;
			default:
			break;
        }
        if ($query != '')
		{
			$this->_db->setQuery($query);
            if (!$this->_db->query())
			{
                return false;
            }
        }
        return true;
    }

    private function _convertToBool($arg = null)
	{
        if (!empty($arg))
		{
            return ((strcasecmp($arg, 'true') == 0) || ($arg == '1'));
        }
        return false;
    }

    function _parseAttributes($element, $status = 1)
	{
        $obj 		= new stdClass();
        $obj->skip   = false;
        if ($element->name() != 'extension')
		{
            $obj->skip = true;
            return $obj;
        }
        $obj->type = (string) $element->attributes()->type;

        $obj->name = (string) $element->attributes()->name;
        $obj->data = (string) $element->data();
		$subdir = (string) $element->attributes()->subdir;
        if (empty($obj->data))
		{
            $obj->data = $obj->name;
        }
        $obj->position  = $element->attributes()->position;
        $obj->folder 	= (string)$element->attributes()->folder;
        $obj->publish 	= $this->_convertToBool((string) $element->attributes()->publish) ? 1 : 0;
		$obj->core 		= $this->_convertToBool((string) $element->attributes()->lock) ? 1 : 0;
        $client 		= $element->attributes()->client;

        if (!$obj->type)
		{
            return false;
        }

        switch ($obj->type)
		{
			case 'plugin':

				if (!$obj->folder)
				{
					return false;
				}
				$client = 'site';
			break;
			case 'component':
				$client = 'site';
			break;
			case 'module':
			break;
			default:
				return false;
        }

        if (!$obj->name)
		{
            return false;
        }

        if (!$client)
		{
            return false;
        }
		if ($status)
		{
			if (!empty($subdir))
			{
				$obj->source = $this->_source.DS.$subdir;
				if (!is_dir($obj->source))
				{
					return false;
				}
			}
		}

        $obj->client = 0;
        switch ($client)
		{
			case 'site':
				break;
			case 'admin':
				$obj->client = 1;
				break;
			default:
				return null;
        }

        return $obj;
    }

    public function install()
	{
    	$resultCheckPlgContent 	= false;
    	$resultCheckPlgSystem 	= false;
    	$resultCheckModule 		= false;
    	$resultCheckLangBO 		= false;
    	$resultCheckLangFO	    = false;
		$objJConfig 			= new JConfig();
		$JConfigArrays 			= JArrayHelper::fromObject($objJConfig);
    	$FTPEnable     			= (int) $JConfigArrays['ftp_enable'];
		if (!$FTPEnable)
		{
			$checklangB0 = $this->_checkFolderLangBOPermission();
			if ($checklangB0 == true)
			{
				$resultCheckLangBO = true;
			}
			else
			{
				$this->setError('lgcheck');
			}

			$checklangF0 = $this->_checkFolderLangFOPermission();
			if ($checklangF0 == true)
			{
				$resultCheckLangFO = true;
			}
			else
			{
				$this->setError('lgcheckfo');
			}

			$checkPlgContent 	= $this->_checkWritablePluginContent();
			if ($checkPlgContent == true)
			{
				$resultCheckPlgContent = true;
			}
			else
			{
				$this->setError('plgcontent');
			}

			$checkPlgSystem 	= $this->_checkWritablePluginSystem();
			if ($checkPlgSystem == true)
			{
				$resultCheckPlgSystem = true;
			}
			else
			{
				$this->setError('plgsystem');
			}

			$checkModule 		= $this->_checkWritableModule();
			if($checkModule == true)
			{
				$resultCheckModule = true;
			}
			else
			{
				$this->setError('module');
			}

			if ($resultCheckPlgContent == false || $resultCheckPlgSystem == false || $resultCheckModule == false || $resultCheckLangBO == false || $resultCheckLangFO == false)
			{
				return true;
			}
		}
        if (is_a($this->_subinstall, 'JXMLElement'))
		{
            $nodes = $this->_subinstall->children();
            if (count($nodes) == 0)
			{
                return false;
            }

            foreach ($nodes as $node)
			{

				$ext = $this->_parseAttributes($node);

			   if (!is_object($ext))
				{
                    return false;
                }
                if ($ext->skip)
				{
                    continue;
                }

                $objInstaller 	= new JInstaller();
				$result 		= $objInstaller->install($ext->source);

                $smsg 			= $objInstaller->get('message');
                $msg 			= $objInstaller->get('extension.message');
                if (!empty($msg))
				{
                    echo $msg;
                }
                if ($result)
				{
					if ($ext->publish)
					{
                        $id = $this->_getExtID($ext);
						if ($id)
						{
							if (!$this->_enable($id, $ext->name, $ext->type))
							{
								return false;
							}
                        }
                    }
                    if ($ext->core)
					{
                        $id = $this->_getExtID($ext);
                        if ($id)
						{
                            $this->_setprotect($id, $ext->type, 1);
                        }
                    }
					if (!is_null($ext->position) && $ext->position != '' && $ext->type == "module")
					{
						$id = $this->_getExtID($ext);
                        if ($id != null)
                        {
                            $this->_setposition($id, $ext->name, $ext->position, $ext->type);
                        }
                    }
                }
				else
				{
                    return false;
                }

            }
        }
        return true;
    }


    function uninstall()
	{
        if (is_a($this->_subinstall, 'JXMLElement'))
		{
            $nodes = $this->_subinstall->children();
            if (count($nodes) == 0)
			{
                return false;
            }

            foreach ($nodes as $node)
			{
                $ext = $this->_parseAttributes($node, 0);

                if (!is_object($ext))
				{
                    return false;
                }
				$id = $this->_getExtID($ext);
                if ($id)
				{
					 if ($ext->core)
					{
                        $this->_setprotect($id, $ext->type, 0);
                    }
					$objInstaller 	= new JInstaller();
                    $result 		= $objInstaller->uninstall($ext->type, $id);
                    $msg 			= $objInstaller->get('message');
                    $this->_msg($msg, $result ? 'message' : 'warning');
                    $msg = $objInstaller->get('extension.message');
                    if (!empty($msg))
					{
                        echo $msg;
                    }
                    if ($result)
					{
                        $this->_msg('Successfully removed '.$ext->type.' "'.$ext->data.'".');
                    }
                }
            }

			$this->_deleteFolderThumbImage();
        }
        return true;
    }

	private function _checkWritablePluginContent()
	{
		$filepath 	= JPATH_ROOT.DS.'plugins'.DS.'content';
		if (is_writable($filepath))
		{
			return true;
		}
		return false;
	}

	private function _checkWritablePluginSystem()
	{
		$filepath 	= JPATH_ROOT.DS.'plugins'.DS.'system';
		if (is_writable($filepath))
		{
			return true;
		}
		return false;
	}

	private function _checkWritableModule()
	{
		$filepath 	= JPATH_ROOT.DS.'modules';
		if (is_writable($filepath))
		{
			return true;
		}
		return false;
	}

	private function _checkFolderLangPermission($base, $nameError)
	{
		jimport('joomla.filesystem.folder');
		$session 	= JFactory::getSession();
		$folders 	= JFolder::folders($base, '.', false, true);
		$arrayError	= array();
		foreach ($folders as $folder)
		{
			if (array_key_exists(basename($folder), $this->_lang))
			{
				if (is_writable($folder))
				{
					$arrayError [basename($folder)] = 'yes';
				}
				else
				{
					$arrayError [basename($folder)] = 'no';
				}
			}
		}
		$session->set($nameError, $arrayError);
		return true;
	}

	private function _checkFolderLangBOPermission()
	{
		$filepath 	= JPATH_ROOT.DS.'administrator'.DS.'language';
		$this->_checkFolderLangPermission($filepath, 'jsn_install_folder_admin');
		$session 	= JFactory::getSession();
		$result 	= $session->get( 'jsn_install_folder_admin' );

		foreach ($result as $value)
		{
			if($value == 'no') return false;
		}
		return true;
	}

	private function _checkFolderLangFOPermission()
	{
		$filepath 	= JPATH_ROOT.DS.'language';
		$this->_checkFolderLangPermission($filepath, 'jsn_install_folder_client');
		$session 	= JFactory::getSession();
		$result 	= $session->get( 'jsn_install_folder_client' );

		foreach ($result as $value)
		{
			if($value == 'no') return false;
		}
		return true;
	}

	private function _deleteFolderThumbImage()
	{
	    jimport('joomla.filesystem.folder');
	    $thumbPath	= JPATH_ROOT . DS . 'images' . DS . 'jsn_is_thumbs' . DS;
		if (!JFolder::exists($thumbPath))
		{
			return false;
		}

	    if (is_writable($thumbPath) == false)
	    {
    		return false;
    	}

		@JFolder::delete($thumbPath);
		return true;
	}

	private function _getModuleInformation($moduleName)
	{
		$query 	= 'SELECT * FROM #__modules WHERE module = '.$this->_db->Quote($moduleName, false);
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}

	private function _setPageForModule($moduleName, $value)
	{
		$moduleInfo = $this->_getModuleInformation($moduleName);
		$query = 'SELECT COUNT(*) FROM #__modules_menu WHERE moduleid = '. (int) $moduleInfo->id;
		$this->_db->setQuery($query);
		$result = (int) $this->_db->loadResult();
		if (!$result)
		{
			$query = 'INSERT INTO #__modules_menu (moduleid, menuid) VALUES (\''.(int) $moduleInfo->id.'\', \''.(int) $value.'\')';
			$this->_db->setQuery($query);
			return $this->_db->query();
		}
		return true;
	}
}