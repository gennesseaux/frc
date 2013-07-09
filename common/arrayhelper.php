<?php
	/**
	 * Conversion d'un object en array
	 **/
	function ObjectToArray($Obj,$recursive = true)
	{
		if (is_object($Obj)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$Obj = get_object_vars($Obj);
		}
 
		if (is_array($Obj) and $recursive==1) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $Obj);
		}
		else {
			// Return array
			return $Obj;
		}
	}

	/**
	 * Conversion d'un array en object
	 **/
	function ArrayToObject($Arr)
	{
		if (is_array($Arr)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(__FUNCTION__, $Arr);
		}
		else {
			// Return object
			return $Arr;
		}
	}
	
	
	/**
	 * Append associative array elements
	 **/
	function array_push_associative(&$arr)
	{
		$args = func_get_args();
	    //array_unshift($args); // remove &$arr argument
	    foreach ($args as $arg)
		{
			if(is_array($arg))
			{
				foreach($arg as $key => $value) 
				{
					$arr[$key] = $value;
	                //$ret++;
	            }
			}
			else
			{
				$arr[$arg] = "";
			}
		}
	// return $ret;	
	}
?>