<?php
/**
 * This class contains static methods for access core functionality.
 *
 * @package TinyFramework
 */
class TinyFramework
{

	/**
	 * @static
	 * @var TinyFramework_Config
	 */
	private static $config;

	/**
	 * @static
	 * @var object
	 */
	private static $factory;

	/**
	 * @static
	 * @var TinyFramework_Database
	 */
	private static $database;

	/**
	 * Sets the Config object.
	 *
	 * @static
	 * @throws Excpetion if Config has already been loaded.
	 * @param TinyFramework_Config $config
	 * @return NULL
	 */
	public static function setConfig(TinyFramework_Config $config)
	{
		if (isset(self::$config))
		{
			throw new Exception('Config has already been set, cannot reset.');
		}
		self::$config = $config;
	}

	/**
	 * Returns the Config object.
	 *
	 * @static
	 * @throws Excpetion if Config has not been set.
	 * @return TinyFramework_Config
	 */
	public static function getConfig()
	{
		if (!isset(self::$config))
		{
			throw new Exception('Config must be set before it can be returned.');
		}
		return self::$config;
	}

	/**
	 * Returns the Factory specified in the Config object.
	 *
	 * @static
	 * @throws Excpetion if Config has not been set.
	 * @return object
	 */
	public static function getFactory()
	{
		if (!isset(self::$factory))
		{
			$factory_class = self::getConfig()->default_factory; 
			self::$factory = new $factory_class;
		}
		return self::$factory;
	}

	/**
	 * Returns the Database object.
	 *
	 * @static
	 * @return TinyFramework_Database
	 */
	public static function getDatabase()
	{
		if (!isset(self::$database))
		{
			self::$database = new TinyFramework_Database(
				self::getConfig()->database_dsn, 
				self::getConfig()->database_user, 
				self::getConfig()->database_pass
			);
		}
		return self::$database;
	}
}

