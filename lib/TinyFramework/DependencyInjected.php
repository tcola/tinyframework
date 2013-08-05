<?php
/**
 * Dependency Injected Object.
 *
 * @package TinyFramework
 */
abstract class TinyFramework_DependencyInjected
{
	/**
	 * @var TinyFramework_Factory
	 */
	private $factory;

	/**
	 * @var TinyFramework_Database
	 */
	private $database;

	/**
	 * @var TinyFramework_Config
	 */
	private $config;

	/**
	 * @param TinyFramework_Factory $factory
	 * @return NULL
	 */
	public function setFactory(TinyFramework_Factory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @param TinyFramework_Database $database
	 * @return NULL
	 */
	public function setDatabase(TinyFramework_Database $database)
	{
		$this->database = $database;
	}

	/**
	 * @param TinyFramework_Config $config
	 * @return NULL
	 */
	public function setConfig(TinyFramework_Config $config)
	{
		$this->config = $config;
	}

	/**
	 * @return TinyFramework_Factory
	 */
	protected function getFactory()
	{
		return $this->factory;
	}

	/**
	 * @return TinyFramework_Database
	 */
	protected function getDatabase()
	{
		return $this->database;
	}

	/**
	 * @return TinyFramework_Config
	 */
	protected function getConfig()
	{
		return $this->config;
	}
}
