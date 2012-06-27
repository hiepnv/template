<?php
/**
 * @modified JoomlaShine.com Team
 * @package JSN ImageShow
 * @version $Id: plugin.php 11375 2012-02-24 10:19:14Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// No direct access
defined('JPATH_BASE') or die;
jimport('joomla.installer.adapters.installerplugin');
require_once JPATH_LIBRARIES.DS.'joomla'.DS.'installer'.DS.'adapters'.DS.'plugin.php';
class JSNISInstallerPlugin extends JInstallerPlugin
{
	public function install()
	{
		$db = $this->parent->getDbo();
		$this->manifest = $this->parent->getManifest();
		$xml = $this->manifest;
		$name = (string)$xml->name;
		$name = JFilterInput::getInstance()->clean($name, 'string');
		$this->set('name', $name);

		$description = (string)$xml->description;
		if ($description)
		{
			$this->parent->set('message', JText::_($description));
		}
		else
		{
			$this->parent->set('message', '');
		}

		/*
		 * Backward Compatability
		 * @todo Deprecate in future version
		 */
		$type = (string)$xml->attributes()->type;

		// Set the installation path
		if (count($xml->files->children()))
		{
			foreach ($xml->files->children() as $file)
			{
				if ((string)$file->attributes()->$type)
				{
					$element = (string)$file->attributes()->$type;
					break;
				}
			}
		}
		$group = (string)$xml->attributes()->group;
		if (!empty ($element) && !empty($group)) {
			$this->parent->setPath('extension_root', JPATH_PLUGINS.DS.$group.DS.$element);
		}
		else
		{
			$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_NO_FILE', JText::_('JLIB_INSTALLER_'.$this->route)));
			return false;
		}


		/*
		 * Check if we should enable overwrite settings
		 */
		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT extension_id' .
				' FROM #__extensions' .
				' WHERE folder = '.$db->Quote($group) .
				' AND element = '.$db->Quote($element);
		$db->setQuery($query);
		try {
			$db->Query();
		}
		catch(JException $e)
		{
			// Install failed, roll back changes
			$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_ROLLBACK', JText::_('JLIB_INSTALLER_'.$this->route), $db->stderr(true)));
			return false;
		}
		$id = $db->loadResult();

		// if its on the fs...
		if (file_exists($this->parent->getPath('extension_root')) && (!$this->parent->getOverwrite() || $this->parent->getUpgrade()))
		{
			$updateElement = $xml->update;
			// upgrade manually set
			// update function available
			// update tag detected
			if ($this->parent->getUpgrade() || ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'update')) || is_a($updateElement, 'JXMLElement'))
			{
				// force these one
				$this->parent->setOverwrite(true);
				$this->parent->setUpgrade(true);
				/*if ($id) { // if there is a matching extension mark this as an update; semantics really
					$this->route = 'update';
				}*/
			}
			else if (!$this->parent->getOverwrite())
			{
				// overwrite is set
				// we didn't have overwrite set, find an udpate function or find an update tag so lets call it safe
				$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_DIRECTORY', JText::_('JLIB_INSTALLER_'.$this->route), $this->parent->getPath('extension_root')));
				return false;
			}
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Installer Trigger Loading
		 * ---------------------------------------------------------------------------------------------
		 */
		// If there is an manifest class file, lets load it; we'll copy it later (don't have dest yet)
		if ((string)$xml->scriptfile)
		{
			$manifestScript = (string)$xml->scriptfile;
			$manifestScriptFile = $this->parent->getPath('source').DS.$manifestScript;
			if (is_file($manifestScriptFile))
			{
				// load the file
				include_once $manifestScriptFile;
			}
			// Set the class name
			$classname = 'plg'.$group.$element.'InstallerScript';
			if (class_exists($classname))
			{
				// create a new instance
				$this->parent->manifestClass = new $classname($this);
				// and set this so we can copy it later
				$this->set('manifest_script', $manifestScript);
				// Note: if we don't find the class, don't bother to copy the file
			}
		}

		// run preflight if possible (since we know we're not an update)
		ob_start();
		ob_implicit_flush(false);
		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'preflight'))
		{
			if($this->parent->manifestClass->preflight($this->route, $this) === false)
			{
				// Install failed, rollback changes
				$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_PLG_INSTALL_CUSTOM_INSTALL_FAILURE'));
				return false;
			}
		}
		$msg = ob_get_contents(); // create msg object; first use here
		ob_end_clean();

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root')))
		{
			if (!$created = JFolder::create($this->parent->getPath('extension_root')))
			{
				$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_CREATE_DIRECTORY', JText::_('JLIB_INSTALLER_'.$this->route), $this->parent->getPath('extension_root')));
				return false;
			}
		}

		// if we're updating at this point when there is always going to be an extension_root find the old xml files
		if($this->route == 'update')
		{
			// Hunt for the original XML file
			$old_manifest = null;
			$tmpInstaller = new JInstaller(); // create a new installer because findManifest sets stuff; side effects!
			// look in the extension root
			$tmpInstaller->setPath('source', $this->parent->getPath('extension_root'));
			if ($tmpInstaller->findManifest())
			{
				$old_manifest = $tmpInstaller->getManifest();
				$this->oldFiles = $old_manifest->files;
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created)
		{
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parent->parseFiles($xml->files, -1, $this->oldFiles) === false)
		{
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Parse optional tags -- media and language files for plugins go in admin app
		$this->parent->parseMedia($xml->media, 1);
		$this->parent->parseLanguages($xml->languages, 1);

		// If there is a manifest script, lets copy it.
		if ($this->get('manifest_script'))
		{
			$path['src'] = $this->parent->getPath('source').DS.$this->get('manifest_script');
			$path['dest'] = $this->parent->getPath('extension_root').DS.$this->get('manifest_script');

			if (!file_exists($path['dest']))
			{
				if (!$this->parent->copyFiles(array ($path)))
				{
					// Install failed, rollback changes
					$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_MANIFEST', JText::_('JLIB_INSTALLER_'.$this->route)));
					return false;
				}
			}
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */
		$row = JTable::getInstance('extension');
		// Was there a plugin already installed with the same name?
		if ($id)
		{
			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_ALLREADY_EXISTS', JText::_('JLIB_INSTALLER_'.$this->route), $this->get('name')));
				return false;
			}
			$row->load($id);
			$row->name = $this->get('name');
			$row->manifest_cache = $this->parent->generateManifestCache();
			$row->enabled = 1;
			$row->protected = 1;
			$row->store(); // update the manifest cache and name
		}
		else
		{
			// Store in the extensions table (1.6)
			$row->name = $this->get('name');
			$row->type = 'plugin';
			$row->ordering = 0;
			$row->element = $element;
			$row->folder = $group;
			$row->enabled = 1;
			$row->protected = 1;
			$row->access = 1;
			$row->client_id = 0;
			$row->params = $this->parent->getParams();
			$row->custom_data = ''; // custom data
			$row->system_data = ''; // system data
			$row->manifest_cache = $this->parent->generateManifestCache();

			if ($group == 'editors')
			{
				$row->enabled = 1;
			}

			if (!$row->store())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_ROLLBACK', JText::_('JLIB_INSTALLER_'.$this->route), $db->stderr(true)));
				return false;
			}

			// Since we have created a plugin item, we add it to the installation step stack
			// so that if we have to rollback the changes we can undo it.
			$this->parent->pushStep(array ('type' => 'extension', 'id' => $row->extension_id));
			$id = $row->extension_id;
		}

		/*
		 * Let's run the queries for the module
		 *	If Joomla 1.5 compatible, with discreet sql files - execute appropriate
		 *	file for utf-8 support or non-utf-8 support
		 */
		// try for Joomla 1.5 type queries
		// second argument is the utf compatible version attribute
		if(strtolower($this->route) == 'install') {
			$utfresult = $this->parent->parseSQLFiles($this->manifest->install->sql);
			if ($utfresult === false)
			{
				// Install failed, rollback changes
				$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_SQL_ERROR', JText::_('JLIB_INSTALLER_'.$this->route), $db->stderr(true)));
				return false;
			}

			// Set the schema version to be the latest update version
			if($this->manifest->update) {
				$this->parent->setSchemaVersion($this->manifest->update->schemas, $row->extension_id);
			}
		} else if(strtolower($this->route) == 'update') {
			if($this->manifest->update)
			{
				$result = $this->parent->parseSchemaUpdates($this->manifest->update->schemas, $row->extension_id);
				if ($result === false)
				{
					// Install failed, rollback changes
					$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_UPDATE_SQL_ERROR', $db->stderr(true)));
					return false;
				}
			}
		}

		// Start Joomla! 1.6
		ob_start();
		ob_implicit_flush(false);
		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,$this->route))
		{
			if($this->parent->manifestClass->{$this->route}($this) === false)
			{
				// Install failed, rollback changes
				$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_PLG_INSTALL_CUSTOM_INSTALL_FAILURE'));
				return false;
			}
		}
		$msg .= ob_get_contents(); // append messages
		ob_end_clean();

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Finalization and Cleanup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest(-1))
		{
			// Install failed, rollback changes
			$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_INSTALL_COPY_SETUP', JText::_('JLIB_INSTALLER_'.$this->route)));
			return false;
		}
		// And now we run the postflight
		ob_start();
		ob_implicit_flush(false);
		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'postflight'))
		{
			$this->parent->manifestClass->postflight($this->route, $this);
		}
		$msg .= ob_get_contents(); // append messages
		ob_end_clean();
		if ($msg != '') {
			$this->parent->set('extension_message', $msg);
		}
		return $id;
	}

	public function uninstall($id)
	{
		// Initialise variables.
		$this->route 	= 'uninstall';
		$row		 	= null;
		$retval 		= true;
		$db				= $this->parent->getDbo();

		// First order of business will be to load the module object table from the database.
		// This should give us the necessary information to proceed.
		$row = JTable::getInstance('extension');
		if (!$row->load((int) $id))
		{
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_PLG_UNINSTALL_ERRORUNKOWNEXTENSION'));
			return false;
		}

		// Get the plugin folder so we can properly build the plugin path
		if (trim($row->folder) == '')
		{
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_PLG_UNINSTALL_FOLDER_FIELD_EMPTY'));
			return false;
		}

		// Set the plugin root path
		if (is_dir(JPATH_PLUGINS.DS.$row->folder.DS.$row->element)) {
			// Use 1.6 plugins
			$this->parent->setPath('extension_root', JPATH_PLUGINS.DS.$row->folder.DS.$row->element);
		}
		else {
			// Use Legacy 1.5 plugins
			$this->parent->setPath('extension_root', JPATH_PLUGINS.DS.$row->folder);
		}

		// Because plugins don't have their own folders we cannot use the standard method of finding an installation manifest
		// Since 1.6 they do, however until we move to 1.7 and remove 1.6 legacy we still need to use this method
		// when we get there it'll be something like "$this->parent->findManifest();$manifest = $this->parent->getManifest();"
		$manifestFile = $this->parent->getPath('extension_root').DS.$row->element.'.xml';

		if ( ! file_exists($manifestFile))
		{
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_PLG_UNINSTALL_INVALID_NOTFOUND_MANIFEST'));
			return false;
		}

		$xml = JFactory::getXML($manifestFile);

		$this->manifest = $xml;

		// If we cannot load the xml file return null
		if (!$xml)
		{
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_PLG_UNINSTALL_LOAD_MANIFEST'));
			return false;
		}

		/*
		 * Check for a valid XML root tag.
		 * @todo: Remove backwards compatability in a future version
		 * Should be 'extension', but for backward compatability we will accept 'install'.
		 */
		if ($xml->getName() != 'install' && $xml->getName() != 'extension')
		{
			JError::raiseWarning(100, JText::_('JLIB_INSTALLER_ERROR_PLG_UNINSTALL_INVALID_MANIFEST'));
			return false;
		}

		// Attempt to load the language file; might have uninstall strings
		$this->parent->setPath('source', JPATH_PLUGINS .'/'.$row->folder.'/'.$row->element);
		$this->loadLanguage(JPATH_PLUGINS .'/'.$row->folder.'/'.$row->element);

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Installer Trigger Loading
		 * ---------------------------------------------------------------------------------------------
		 */
		// If there is an manifest class file, lets load it; we'll copy it later (don't have dest yet)
		$manifestScript = (string)$xml->scriptfile;
		if ($manifestScript)
		{
			$manifestScriptFile = $this->parent->getPath('source').DS.$manifestScript;
			if (is_file($manifestScriptFile)) {
				// load the file
				include_once $manifestScriptFile;
			}
			// Set the class name
			$classname = 'plg'.$row->folder.$row->element.'InstallerScript';
			if (class_exists($classname))
			{
				// create a new instance
				$this->parent->manifestClass = new $classname($this);
				// and set this so we can copy it later
				$this->set('manifest_script', $manifestScript);
				// Note: if we don't find the class, don't bother to copy the file
			}
		}

		// run preflight if possible (since we know we're not an update)
		ob_start();
		ob_implicit_flush(false);
		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'preflight'))
		{
			if($this->parent->manifestClass->preflight($this->route, $this) === false)
			{
				// Install failed, rollback changes
				$this->parent->abort(JText::_('JLIB_INSTALLER_ABORT_PLG_INSTALL_CUSTOM_INSTALL_FAILURE'));
				return false;
			}
		}
		$msg = ob_get_contents(); // create msg object; first use here
		ob_end_clean();

		/*
		 * Let's run the queries for the module
		 *	If Joomla 1.5 compatible, with discreet sql files - execute appropriate
		 *	file for utf-8 support or non-utf-8 support
		 */
		// try for Joomla 1.5 type queries
		// second argument is the utf compatible version attribute
		$utfresult = $this->parent->parseSQLFiles($xml->{strtolower($this->route)}->sql);
		if ($utfresult === false)
		{
			// Install failed, rollback changes
			$this->parent->abort(JText::sprintf('JLIB_INSTALLER_ABORT_PLG_UNINSTALL_SQL_ERROR', $db->stderr(true)));
			return false;
		}

		// Start Joomla! 1.6
		ob_start();
		ob_implicit_flush(false);
		if ($this->parent->manifestClass && method_exists($this->parent->manifestClass,'uninstall'))
		{
			$this->parent->manifestClass->uninstall($this);
		}
		$msg = ob_get_contents(); // append messages
		ob_end_clean();


		// Remove the plugin files
		$this->parent->removeFiles($xml->images, -1);
		$this->parent->removeFiles($xml->files, -1);
		JFile::delete($manifestFile);

		// Remove all media and languages as well
		$this->parent->removeFiles($xml->media);
		$this->parent->removeFiles($xml->languages, 1);

		// Remove the schema version
		$query = $db->getQuery(true);
		$query->delete()->from('#__schemas')->where('extension_id = '. $row->extension_id);
		$db->setQuery($query);
		$db->Query();

		// Now we will no longer need the plugin object, so lets delete it
		$row->delete($row->extension_id);
		unset ($row);

		// If the folder is empty, let's delete it
		$files = JFolder::files($this->parent->getPath('extension_root'));

		JFolder::delete($this->parent->getPath('extension_root'));

		if ($msg) {
			$this->parent->set('extension_message',$msg);
		}

		return $retval;
	}
}