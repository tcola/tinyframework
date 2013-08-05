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

		if ($obj instanceof TinyFramework_DependencyInjected)
		{
			$obj->setFactory($this);
			$obj->setDatabase(TinyFramework::getDatabase());
			$obj->setConfig(TinyFramework::getConfig());
		}

		return $obj;
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
		$model = new $name();
		// Throw an Exception if not a valid instance
		if (!($model instanceof TinyFramework_Model))
		{
			throw new Exception($name . ' is not a TinyFramework_Model');
		}
		// Load the column
		foreach ($model as $column => $value)
		{
			if (isset($column_values[$column]))
				$model->$column = $column_values[$column];
		}
		return $model;
	}
}
