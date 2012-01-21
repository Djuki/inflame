<?php

namespace Inflame;

use Fuel\Core\Inflector;
use Fuel\Core\Db;
use Inflame\Record;

abstract class Model {
	
	/**
	 * 
	 * Primary key(s)
	 * @var mixed
	 */
	protected static $_primary_key = 'id';
	
	/**
	 * 
	 * Tablle name
	 * @var string
	 */
	protected static $_table;
	
	/**
	 * 
	 * Model name
	 * @var string
	 */
	protected static $_model;
	
	/**
	 * 
	 * Fields
	 * @var array
	 */
	protected static $_fields = array();
	
	/**
	 * 
	 * Record object class name
	 * @var string
	 */
	protected static $_record_object = '\\Inflame\\Record';
	
	
	/**
	 * 
	 * Query object
	 * @var Database_Query
	 */
	protected $query;
	
	
	/**
	 * 
	 * Initialization
	 */
	public static function _init()
	{
		if ( ! static::$_model) {
			static::$_model = strtolower(substr(get_called_class(), 6));
		}

		if ( ! static::$_table) {

			static::$_table = Inflector::pluralize(static::$_model);
		}
		

	}
	
	/**
	 * 
	 * Model constructor
	 */
	public function __construct()
	{
		$this->reset_query();
	}
	
	/**
	*
	* Queru object with pepared select and from
	*/
	public function query()
	{
		return $this->query;
	}	
	
	/**
	 * 
	 * Reset select query object
	 */
	public function reset_query()
	{
		if (isset($this->query))
		{
			unset($this->query);
		}

		$this->query = DB::select()
		->from(static::$_table)
		->as_object(static::$_record_object);
		
	}
	
	/**
	 * 
	 * Find record by id
	 * @param mixed $id
	 */
	public function find($id = null) {
		return $this->find_by(static::$_primary_key, $id);
	}
	
	/**
	 * 
	 * Find record by ..
	 * 
	 * <code>
	 * Can be called like
	 * $model->find_by_name('Frenk');
	 * </code>
	 * @param mixed $fields
	 * @param mixed $values
	 * @param mixed $options
	 */
	public function find_by($fields, $values = null)
	{
		//echo 'find_by: '. $field. ' '. $value;
		if (is_array($fields) and is_array($values))
		{
			$key = 0;
			foreach ($fields as $field)
			{
				$this->query->where($field, '=', $values[$key++]);
			}
		}
		else if (is_array($fields) and is_null($values))
		{
			foreach ($fields as $field => $values)
			{
				$this->query->where($field, '=', $values);
			}
		}
		else
		{
			return $this->where($fields, '=', $values);
		}


		$result = $this->query->execute();
		$this->reset_query();
		return $result;
	}

	/**
	 * 
	 * Macic call for find_by{field_name}
	 * Also to make call query methods directly on model
	 * @param string $method
	 * @param mixed $args
	 */
	public function __call($method, $args)
	{
		if (substr($method, 0, 8) == 'find_by_')
		{
			$field = substr($method, 8);
			return $this->find_by($field, $args[0]);
		}
		else if ($this->query and method_exists($this->query, $method))
		{
			//echo 'Called query method : '.$method.'<br/>';
			call_user_func_array(array($this->query, $method), $args);
		}

		return $this;
	}
	
	/**
	 * 
	 * Fetch results
	 */
	public function fetch_all()
	{
		if ($result = $this->query->execute())
		{
			$this->reset_query();

			return $result;
		}

		return false;
	}

}
