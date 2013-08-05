<?php
/**
 * Generic functionality for calling the requested Controller method. Note that
 * the lib/ directory should be in your include_path.
 *
 * @package yourproject
 */

define('ACTIVE_CONFIG', 'Config_YourProject');
include_once('env.php');

$controller = (isset($_REQUEST['controller']) ? $_REQUEST['controller'] : TinyFramework::getConfig()->default_controller);
$method = (isset($_REQUEST['method']) ? $_REQUEST['method'] : TinyFramework::getConfig()->default_method);

$obj = TinyFramework::getFactory()->get($controller);
$obj->$method();

