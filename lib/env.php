<?php
/**
 * Environment. This is the boot-strap file. This file contains an autoloader
 * which will attempt to load class files from the relative lib/ directory.
 *
 * Usage ('Config_' and 'Controller_' prefixes are always optional):
 *
 *   Set ACTIVE_CONFIG prior to including this file, or defaults to TinyFramework_Config
 *
 *   Command-line usage:
 *
 *     php script.php --controller=foo --method=bar (both optional)
 *     ELSE
 *     php script.php (uses ACTIVE_CONTROLLER and/or ACTIVE_METHOD if defined)
 *     ELSE
 *     php script.php (uses default controller and method specified in Config)
 *
 *   Apache2 usage:
 *
 *     Set $_REQUEST['controller'] and/or $_REQUEST['method']
 *     ELSE
 *     Uses ACTIVE_CONTROLLER and/or ACTIVE_METHOD if defined
 *     ELSE
 *     Defaults to controller and method specified in Config
 *
 * @package TinyFramework
 */

/**
 * Autoload class files from the lib directory.
 *
 * @param string $classname
 * @return NULL
 */
function __autoload($class_name)
{
    $class_path = str_replace('_', '/', $class_name) . '.php';
	require_once($class_path);
}

/**
 * Loads the TinyFramework_Config specified in ACTIVE_CONFIG
 *
 * @return NULL
 */
function loadActiveConfig($config_class)
{
	TinyFramework::setConfig(new $config_class());
}

// Use default if no Config has been defined
if (!defined('ACTIVE_CONFIG'))
{
	loadActiveConfig('TinyFramework_Config');
}
else // If a Config has been defined, then load it
{
	loadActiveConfig('Config_' . str_replace('Config_', '', ACTIVE_CONFIG));
}


$options = getopt('', array('controller:', 'method:'));

// Set the Controller
if ('cli' === php_sapi_name() && isset($options['controller']))
{
	$controller = 'Controller_' . str_replace('Controller_', '', $options['controller']);
}
elseif (isset($_REQUEST['controller']))
{
	$controller = 'Controller_' . str_replace('Controller_', '', $_REQUEST['controller']);
}
elseif (defined('ACTIVE_CONTROLLER'))
{
	$controller = 'Controller_' . str_replace('Controller_', '', ACTIVE_CONTROLLER);
}
else
{
	$controller = 'Controller_' . str_replace('Controller_', '', TinyFramework::getConfig()->default_controller);
}

// Set the Method
if ('cli' === php_sapi_name() && isset($options['method']))
{
	$method = $options['method'];
}
elseif (isset($_REQUEST['method']))
{
	$method = $_REQUEST['method'];
}
elseif (defined('ACTIVE_METHOD'))
{
	$method = ACTIVE_METHOD;
}
else
{
	$method = TinyFramework::getConfig()->default_method;
}

$obj = TinyFramework::getFactory()->get($controller);
$obj->$method();
