<?php
/**
 * Default Controller
 *
 * @package yourproject
 */
class Controller_Default extends TinyFramework_Controller
{
	/**
	 * Simply displays the deault template
	 *
	 * @return NULL
	 */
	public function view()
	{
		$dir = dirname(__FILE__) . '/';

		$tokens = new stdClass;
		$tokens->template_path = $dir . $this->getConfig()->default_template;
		$tokens->controller_path = __FILE__;
		$tokens->config_path = $dir . 'Config/YourProject.php';

		echo $this->render($this->getConfig()->default_template, $tokens);
	}
}
