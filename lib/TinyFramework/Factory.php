<?php
/**
 * The Factory class.
 *
 * @package TinyFramework
 */
class TinyFramework_Factory
{
	/**
	 * Returns an instance of the specified classname, and handles dependency 
	 * injection.
	 *
	 * @param string $classname
	 * @return object
	 */
	public function get($classname)
	{
		$obj = new $classname();

		if ($this->isDependencyInjected($classname))
		{
			$obj->setFactory($this);
			// If class specifies a specific database, then use it
			if ($obj->getDatabaseDSN())
			{
				$dsn = $obj->getDatabaseDSN();
				$obj->setDatabase(TinyFramework::getDatabase($dsn));
			}
			else // otherwise use the default as set in the config
			{
				$obj->setDatabase(TinyFramework::getDatabase());
			}
			$obj->setConfig(TinyFramework::getConfig());
			$obj->setRequest(TinyFramework::getRequest());
		}

		return $obj;
	}

	/**
	 * Returns bool TRUE if class (or a parent) uses DependencyInjected trait
	 *
	 * @param string $classname
	 * @return bool
	 */
	public function isDependencyInjected($classname)
	{
		$traits = class_uses($classname);
		// Get all parent traits
		while ($classname = get_parent_class($classname))
		{
			$traits = array_merge($traits, class_uses($classname));
		}
		// Get all trait traits
		foreach ($traits as $trait)
		{
			$traits = array_merge($traits, class_uses($trait));
		}
		return isset($traits['TinyFramework_DependencyInjected']);
	}

	/**
	 * Returns a Model instance populated with the passed column values.
	 *
	 * @param string $name ex: Model_DataSet
	 * @param array $column_values
	 * @return TinyFramework_Model
	 */
	public function getModel($name, $column_values=array())
	{
		// Throw Exception if $column_values is not an array
		if (!is_array($column_values))
		{
			throw new Exception(
				'property_values param must be an array at ' 
				. __FILE__ . ':' . __LINE__
			);
		}
		// Instanciate the TinyFramework_Model
		$model = $this->get($name);
		// Throw an Exception if not a valid instance
		if (!($model instanceof TinyFramework_Model))
		{
			throw new Exception($name . ' is not a TinyFramework_Model');
		}
		// Load the column
		foreach ($model->getColumns() as $column)
		{
			if (isset($column_values[$column]))
				$model->$column = $column_values[$column];
		}
		return $model;
	}
}
