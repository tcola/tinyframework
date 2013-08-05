<?php
/**
 * Wrapper for PDO object which adds convenience methods for getting and setting
 * Model objects.
 *
 * @package TinyFramework
 */
class TinyFramework_Database extends PDO
{
	public function __construct($dsn, $user, $pass)
	{
		parent::__construct($dsn, $user, $pass);
	}

	/**
	 * Returns a TinyFramework_Model object. If query returns multiple rows, the
	 * first row's data is used to populate the Model.
	 *
	 * @param TinyFramework_Factory $factory
	 * @param string $model_name
	 * @param string $query
	 * @param array $args numeric or associative based on query format
	 * @return TinyFramework_Model|NULL
	 */
	public function getModel(TinyFramework_Factory $factory, $model_name, $query, $args)
	{
		$models = $this->getModels($factory, $model_name, $query, $args);
		return $models[0];
	}

	/**
	 * Returns an array of TinyFramework_Model objects
	 *
	 * @param TinyFramework_Factory $factory
	 * @param string $model_name
	 * @param string $query
	 * @param array $args numeric or associative based on query format
	 * @return array
	 */
	public function getModels(TinyFramework_Factory $factory, $model_name, $query, $args)
	{
		$stmt = $this->prepare($query);
		$stmt->execute($args);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$models = array();
		while ($row = $stmt->fetch())
		{
			$models[] = $factory->getModel($model_name, $row);
		}
		return $models;
	}

	/**
	 * Saves the passed TinyFramework_Model object to the database
	 *
	 * @param TinyFramework_Model $model
	 * @return NULL
	 */
	public function saveModel(TinyFramework_Model $model)
	{
		// Set some short pretty variables
		$auto_increment = $model->getAutoIncrement();
		$columns = $model->getColumns();
		// Build the query
		$query = '
			INSERT INTO
				' . $model->getTableName() . '
			(`' . implode('`, `', $columns) . '`)
			VALUES
			(' . implode(', ', array_pad(array(), count($columns), '?')) . ')
			ON DUPLICATE KEY
			UPDATE
				`' . implode('` = ?, `', $columns) . '` = ?
		';
		// Build value array
		$values = array();
		$params = array_merge($columns, $columns);
		foreach ($params as $column)
		{
			$values[] = $model->$column;
		}

		// Execute the query
		$stmt = $this->prepare($query);
		$stmt->execute($values);

		// If the auto_increment column is empty, set it to the last insert id
		if (!isset($model->$auto_increment))
		{
			$model->$auto_increment = $this->lastInsertId();
		}
	}
}
