<?php
/**
 * Dependency Injected Trait.
 *
 * @package TinyFramework
 */
trait TinyFramework_DependencyInjected
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
	 * @var TinyFramework_Request
	 */
	private $request;

	/**
	 * @var string Overrides dsn set in config
	 */
	//protected $database_dsn;

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
		$this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
	 * @param TinyFramework_Request $request
	 * @return NULL
	 */
	public function setRequest(TinyFramework_Request $request)
	{
		$this->request = $request;
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

	/**
	 * @return TinyFramework_Request
	 */
	protected function getRequest()
	{
		return $this->request;
	}

	/**
	 * @return string|FALSE
	 */
	public function getDatabaseDSN()
	{
		return (isset($this->database_dsn) ? $this->database_dsn : FALSE);
	}
}
