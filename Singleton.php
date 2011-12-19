<?php
/**
 * A reusable singleton class.
 * 
 * @author John Smith
 * @version 1.0.5
 *
 */
class framewerk_Singleton
{
	protected static $instances = array(); // Stores all current instances

	/** 
	 * @param string $class_name - the name of the class to instantiate.
	 * 
	 * @return instanceof $class_name
	 **/	
	public static function getInstance($class_name)
	{
		// Does an instance of this object already exist?
		if(!isset(self::$instances[$class_name]))
		{
			// create a new instance
			self::$instances[$class_name] = new $class_name();
		}

		return self::$instances[$class_name];
	}
}
?>