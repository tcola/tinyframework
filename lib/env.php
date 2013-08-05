<?php
/**
 * Environment. This is the boot-strap file. This file contains an autoloader
 * which will attempt to load class files from the relative lib/ directory.
 *
 * @package TinyFramework
 */

/**
 * Autoload class files from the lib directory.
 *
 * @param string $classname
 * @return NULL
 */
function __autoload($class_name) {
    $class_path = str_replace('_', '/', $class_name) . '.php';
	require_once($class_path);
}

/**
 * Loads the TinyFramwork_Config specified in ACTIVE_CONFIG
 *
 * @return NULL
 */
function loadActiveConfig($config_class)
{
	TinyFramework::setConfig(new $config_class());
}
loadActiveConfig(ACTIVE_CONFIG);



