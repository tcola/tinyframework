<?php
/**
 * Model object. ALL columns should be listed as public properties. The 
 * following reserved properties should be protected: 
 *
 *  table_name (string)
 *  primary_key (array)
 *  auto_increment (string)
 *
 * @package TinyFramework
 */
abstract class TinyFramework_Model
{
	/**
	 * @var array of column names (public properties).
	 */
	protected $columns;

	/**
	 * Returns the database table with which the Model is associated. 
	 *
	 * @return string
	 */
	public function getTableName()
	{
		if (!isset($this->table_name))
		{
			throw new Exception(
				'No table_name set at' . __FILE__ . ':' . __LINE__
			);
		}
		return $this->table_name;
	}

	/**
	 * Returns an array of column names.
	 *
	 * @return array
	 */
	public function getColumns()
	{
		if (!isset($this->columns))
		{
			$reflection = new ReflectionObject($this);
			$properties = $reflection->getProperties(
				ReflectionProperty::IS_PUBLIC
			);
			foreach ($properties as $property) {
				$this->columns[] = $property->getName();
			}
		}

		return $this->columns;
	}

	/**
	 * Returns primary key columns names.
	 *
	 * @return array
	 */
	public function getPrimaryKey()
	{
		if (!isset($this->primary_key))
		{
			throw new Exception(
				'No primary_key set at' . __FILE__ . ':' . __LINE__
			);
		}
		if (!is_array($this->primary_key))
		{
			throw new Exception(
				'The primary_key is not an array at' . __FILE__ . ':' . __LINE__
			);
		}
		return $this->primary_key;
	}

	/**
	 * Returns auto-increment column name, or NULL if none.
	 *
	 * @return string|NULL
	 */
	public function getAutoIncrement()
	{
		return (!empty($this->auto_increment) ? $this->auto_increment : NULL);
	}

	/**
	 * Returns property value if exists. Returns method output if exists. Throws
	 * Exception if no property or method are found.
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		if (in_array($property, $this->getColumns()))
		{
			return $this->$property;
		}
		elseif (method_exists($this, $property))
		{
			return $this->$property();
		}
		else
		{
			throw new Exception($property . ' not found in ' . get_class($this));
		}
	}
}
