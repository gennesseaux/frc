/**
 * 
 *		Jocelyn GENNESSEAUX (hyperjoce)
 * 
 *		page de test
 * 
 *		License : CC BY 3.0 FR (http://creativecommons.org/licenses/by/3.0/fr/legalcode)
 * 
 */

 
 <?php
	
	// Intérogation de l'API de planetside 2
	//$url = 'https://census.soe.com/get/ps2/single_character_by_id/?c:start=0&c:limit=1000&id=5428026242696699825';
	$url = 'https://census.soe.com/s:H41/get/ps2/character/?c:resolve=stat_history(all_time,stat_name)&id=5428026242696699825';
	
	$json = file_get_contents($url);
	$json = preg_replace('/,\s*([\]}])/m', '$1', utf8_encode($json));
	$jsonObject = json_decode($json);

	header('Content-Type: application/json');
	echo $json;
?>



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
?>

<?php
	// Append associative array elements
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