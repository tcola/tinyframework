<?php
/**
 *
 * @package DataAnalysis
 */

define('ACTIVE_CONFIG', 'YourProject');

include_once('env.php');

// Load and call the requested Controller and Method
$controller = 'Controller_' . TinyFramework::getRequest()->controller;
$method = TinyFramework::getRequest()->method;
$obj = TinyFramework::getFactory()->get($controller);
$obj->$method();
