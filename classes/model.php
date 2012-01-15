<?php

namespace Inflame;
use Fuel\Core\Inflector;
use Fuel\Core\Db;

abstract class Model {
	protected static $_primary_key = 'id';

	protected static $_table;

	protected static $_model;

	protected static $_fields = array();
	
	protected $query;

	public function __construct() {
		if (!static::$_model) {
			static::$_model = strtolower(substr(get_class($this), 6));
		}

		if (!static::$_table) {
			static::$_table = Inflector::pluralize(static::$_model);
		}
		
		$this->query = DB::select()->from(static::$_table);
	}

	public function test() {
		var_dump(static::$_model);
		var_dump(static::$_table);
	}

	public static function find($id = null) {
		if (is_array(self::$_primary_key))
		{
			
		}
		else
		{
			self::find_by(self::$_primary_key, $id);
		}
	}
	
	public function query()
	{
		return $this->query;
	}

	public static function find_by($fields, $value = null, $options = array())
	{
		//echo 'find_by: '. $field. ' '. $value;
		$model = new self();
		$query = $model->query->select()->from(static::$_table);
		
		if (is_array($fields))
		{
			
		}
		else
		{
			return $query->where($fields, '=', $value)->execute();
		}
	}
	

	public function __call($method, $args)
	{
		if ($this->query and method_exists($this->query, $method))
		{
			echo 'Called query method : '.$method.'<br/>';
			call_user_func_array(array($this->query, $method), $args);
		}
		
		return $this;
	}
	
	public static function __callStatic($method, $args) 
	{
		if (substr($method, 0, 8) == 'find_by_')
		{
			$field = substr($method, 8);
			return self::find_by($field, $args[0]);
		}
	}
	
	public function fetch_all($model)
	{
		return $model->query()->execute();
	}

}
