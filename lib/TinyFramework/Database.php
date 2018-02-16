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
		return (isset($models[0]) ? $models[0] : NULL);
	}

	/**
	 * Returns an array of TinyFramework_Model objects
	 *
	 * @param TinyFramework_Factory $factory
	 * @param string $model_name
	 * @param string $query
	 * @param array $args numeric or associative based on query format
	 * @param string|FALSE $key_column Optional: key array on this column value
	 * @return array
	 */
	public function getModels(TinyFramework_Factory $factory, $model_name, $query, $args, $key_column=FALSE)
	{
		$stmt = $this->prepare($query);
		$stmt->execute($args);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$models = array();
		if ($key_column)
		{
			while ($row = $stmt->fetch())
			{
				$models[$row[$key_column]] = $factory->getModel($model_name, $row);
			}
		}
		else // numeric
		{
			while ($row = $stmt->fetch())
			{
				$models[] = $factory->getModel($model_name, $row);
			}
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
		// Remove auto increment column if exists but not specified
		if (!empty($auto_increment) && !isset($model->$auto_increment))
		{
			$columns = array_diff($columns, array($auto_increment));
		}
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
		$params = array_merge($columns, $columns); // need full list twice
		foreach ($params as $column)
		{
			$values[] = $model->$column;
		}
		// Execute the query
		$stmt = $this->prepare($query);
		$stmt->execute($values);
		// Handle errors
		$err = $stmt->errorInfo();
		if (intval($err[1])) throw new Exception('MySQL ERROR: ' . $err[2]);
		// If the auto_increment column is empty, set it to the last insert id
		if (!empty($auto_increment) && !isset($model->$auto_increment))
		{
			if ($this->lastInsertId()) // record inserted
			{
				$model->$auto_increment = $this->lastInsertId();
			}
			else // record updated
			{
				$query = "
					SELECT `" . $auto_increment . "` FROM `" . $model->getTableName() . "`
					WHERE `" . implode('` = ? AND `', $params) . "` = ?
				";
				$stmt = $this->prepare($query);
				$stmt->execute($values);
				$model->$auto_increment = $stmt->fetchColumn();
			}
		}
	}
}
