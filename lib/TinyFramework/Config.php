<?php
/**
 * This class should be extended by all Config classes. It permits the reading
 * of property values, while preventing the setting of property values.
 *
 * @package TinyFramework
 */
class TinyFramework_Config
{
	/**
	 * @var string Name of Factory class to be used
	 */
	protected $default_factory = 'TinyFramework_Factory';

	/**
	 * @var string Name of default Controller class to be used
	 */
	protected $default_controller = 'Default';

	/**
	 * @var string Name of Controller to call in case of fatal error
	 */
	protected $error_controller = 'Error';

	/**
	 * @var string Name of default method to be called
	 */
	protected $default_method = 'view';

	/**
	 * @var string Data Source Name
	 */
	protected $database_dsn = 'mysql:dbname=test;host=localhost';

	/**
	 * @var string Database username
	 */
	protected $database_user = 'root';

	/**
	 * @var string Database password
	 */
	protected $database_pass = 'test';

	/**
	 * Return property value if set, else throw Exception
	 *
	 * @throws Exception if property does not exist
	 * @return mixed
	 */
	public function __get($name)
	{
		if (isset($this->$name))
		{
			return $this->$name;
		}

		throw new Exception($name . ' is not configured.');
	}

	/**
	 * Prevent external setting of property values
	 *
	 * @throws Exception
	 * @return NULL
	 */
	public function __set($name, $value)
	{
		throw new Exception('Configuration values cannot be set.');
	}
}
