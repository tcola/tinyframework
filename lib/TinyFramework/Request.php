<?php
/**
 * This class is used to access elements in the $_REQUEST super-global. In
 * addition, it performs parsing of CodeIgniter-type URLs.
 *
 * @package TinyFramework
 */
class TinyFramework_Request
{
	const REQUESTED_URI = 'path';

	private $request;

	/**
	 * @param array $request The $_REQUEST super-global
	 * @return NULL
 	 */
	public function __construct($request)
	{
		$this->request = $request;
	}

	/**
	 * Returns the specified Controller name, or the default (as specified in 
	 * the active Config) if none.
	 *
	 * @return string
	 */
	public function getController()
	{
		if (!isset($this->request['controller']))
		{
			$this->request['controller'] = TinyFramework::getConfig()
				->default_controller;
		}
		return $this->request['controller'];
	}

	/**
	 * Returns the specified method name, or the default (as specified in 
	 * the active Config) if none.
	 *
	 * @return string
	 */
	public function getMethod()
	{
		if (!isset($this->request['method']))
		{
			$this->request['method'] = TinyFramework::getConfig()
				->default_method;
		}
		return $this->request['method'];
	}


	public function __get($name)
	{
		switch ($name)
		{
			case 'controller':
				return $this->getController();

			case 'method':
				return $this->getMethod();

			default:
				if (isset($this->request[$name]))
					return $this->request[$name];
		}
	}

	public function __set($name, $value)
	{
		throw new Exception('Request values cannot be set.');
	}
}
