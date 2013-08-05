<?php
/**
 * TinyFramwork Controller abstract.
 *
 * @package TinyFramework
 */
abstract class TinyFramework_Controller extends TinyFramework_DependencyInjected
{
	/**
	 * Renders the specified View. Views should access tokens using the $tokens
	 * stdClass. 
	 *
	 * @param string $filename Path relative to $_SERVER['PHP_SELF']
	 * @param stdClass $tokens
	 * @return string
	 */
	protected function render($filename, stdClass $tokens)
	{
		ob_start();
		include($filename);
		$rendered = ob_get_contents();
		ob_end_clean();
		return $rendered;
	}
}
