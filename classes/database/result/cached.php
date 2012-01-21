<?php
namespace Inflame;

class Database_Result_Cached extends \Fuel\Core\Database_Result_Cached
{
	/**
	 * 
	 * Call methods on record object
	 * @param string $method
	 * @param string $param_arr
	 */
	public function __call($method, $param_arr)
	{
		
		if ($this->_as_object and $object = $this->current())
		{
			if (method_exists($object, $method))
			{
				call_user_func_array(array($object, $method), $param_arr);
			}
		}
	}
}